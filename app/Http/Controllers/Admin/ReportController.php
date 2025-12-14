<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Consultation;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = auth()->user();
        $currentUserId = $currentUser->id;

        // 1. Obtener usuarios según rol
        $usersForFilter = $this->getUsersForFilter($currentUser);

        // 2. Consulta base
        $query = Consultation::with([
            'appointment.patient.user',
            'appointment.doctor.user'
        ]);

        // 3. RESTRICCIONES POR ROL desde el inicio
        if ($currentUser->hasRole('Paciente')) {
            // Paciente solo ve SUS consultas
            $query->whereHas('appointment.patient.user', function($q) use ($currentUserId) {
                $q->where('id', $currentUserId);
            });
        }
        elseif ($currentUser->hasRole('Doctor')) {
            // Doctor solo ve SUS consultas
            $query->whereHas('appointment.doctor.user', function($q) use ($currentUserId) {
                $q->where('id', $currentUserId);
            });
        }
        // Admin/Recepcionista no tiene restricciones iniciales

        // 4. FILTRO ADICIONAL por usuario seleccionado
        if ($request->filled('filter_user')) {
            $filteredUserId = $request->filter_user;

            if ($currentUser->hasRole('Paciente')) {
                // Paciente filtra por doctor específico
                $query->whereHas('appointment.doctor.user', function($q) use ($filteredUserId) {
                    $q->where('id', $filteredUserId);
                });
            }
            elseif ($currentUser->hasRole('Doctor')) {
                // Doctor filtra por paciente específico (sus propios pacientes)
                $query->whereHas('appointment.patient.user', function($q) use ($filteredUserId) {
                    $q->where('id', $filteredUserId);
                });
            }
            elseif ($currentUser->hasRole(['Admin', 'Recepcionista'])) {
                // Admin/Recepcionista filtra por cualquier usuario
                $query->where(function($q) use ($filteredUserId) {
                    $q->whereHas('appointment.patient.user', function($q) use ($filteredUserId) {
                        $q->where('id', $filteredUserId);
                    })->orWhereHas('appointment.doctor.user', function($q) use ($filteredUserId) {
                        $q->where('id', $filteredUserId);
                    });
                });
            }
        }

        // 5. FILTROS POR PERIODO
        $periodType = $request->get('period_type', 'month');

        if ($periodType === 'month') {
            // Usar valores por defecto o los enviados
            $month = $request->filled('filter_month') ? $request->filter_month : date('n');
            $year = $request->filled('filter_year') ? $request->filter_year : date('Y');

            if ($month && $year) {
                $query->whereHas('appointment', function($q) use ($month, $year) {
                    $q->whereMonth('date', $month)
                      ->whereYear('date', $year);
                });
            }
        }
        elseif ($periodType === 'week' && $request->filled('filter_week')) {
            $weekData = explode('-W', $request->filter_week);
            if (count($weekData) === 2) {
                $year = $weekData[0];
                $week = $weekData[1];

                $query->whereHas('appointment', function($q) use ($year, $week) {
                    $q->whereYear('date', $year)
                      ->whereRaw('WEEK(date, 1) = ?', [$week]);
                });
            }
        }
        elseif ($periodType === 'day' && $request->filled('filter_day')) {
            // Filtro por día específico
            $query->whereHas('appointment', function($q) use ($request) {
                $q->whereDate('date', $request->filter_day);
            });
        }
        elseif ($periodType === 'range' && $request->filled('start_date') && $request->filled('end_date')) {
            $query->whereHas('appointment', function($q) use ($request) {
                $q->whereBetween('date', [$request->start_date, $request->end_date]);
            });
        }
        // Si no hay filtro de fecha y es paciente/doctor, mostrar últimos 30 días
        elseif (!$request->anyFilled(['filter_month', 'filter_week', 'filter_day', 'start_date'])) {
            if ($currentUser->hasRole(['Paciente', 'Doctor'])) {
                $query->whereHas('appointment', function($q) {
                    $q->where('date', '>=', Carbon::now()->subDays(30));
                });
            }
        }

        // 6. FILTRO de búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('diagnosis', 'like', "%{$search}%")
                  ->orWhere('treatment', 'like', "%{$search}%")
                  ->orWhereHas('appointment.patient.user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('appointment.doctor.user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 7. Paginación
        $consultations = $query->orderBy('created_at', 'desc')
                               ->paginate(15)
                               ->appends($request->query());

        // 8. Retornar vista
        return view('admin.reports.index', compact('consultations', 'usersForFilter'));
    }

    /**
     * Obtener usuarios para el filtro según el rol del usuario actual
     */
    private function getUsersForFilter($currentUser)
    {
        // Todos los usuarios excepto el actual
        $allUsers = User::where('id', '!=', $currentUser->id)
                       ->orderBy('name')
                       ->get();
        
        // Para Admin/Recepcionista: todos los usuarios
        if ($currentUser->hasRole(['Admin', 'Recepcionista'])) {
            return $allUsers;
        }
        
        // Para Paciente: solo doctores que le han atendido
        if ($currentUser->hasRole('Paciente')) {
            return $allUsers->filter(function($user) use ($currentUser) {
                if (!$user->hasRole('Doctor')) {
                    return false;
                }
                
                // Verificar si este doctor ha atendido al paciente
                return Consultation::whereHas('appointment', function($q) use ($currentUser, $user) {
                    $q->whereHas('patient.user', function($q) use ($currentUser) {
                        $q->where('id', $currentUser->id);
                    })->whereHas('doctor.user', function($q) use ($user) {
                        $q->where('id', $user->id);
                    });
                })->exists();
            });
        }
        
        // Para Doctor: solo sus pacientes
        if ($currentUser->hasRole('Doctor')) {
            return $allUsers->filter(function($user) use ($currentUser) {
                if (!$user->hasRole('Paciente')) {
                    return false;
                }
                
                // Verificar si este paciente ha sido atendido por el doctor
                return Consultation::whereHas('appointment', function($q) use ($currentUser, $user) {
                    $q->whereHas('doctor.user', function($q) use ($currentUser) {
                        $q->where('id', $currentUser->id);
                    })->whereHas('patient.user', function($q) use ($user) {
                        $q->where('id', $user->id);
                    });
                })->exists();
            });
        }
        
        return collect();
    }

    public function viewPdf(Request $request)
    {
        try {
            $consultations = $this->getConsultationsForPdf($request);
            $usersForFilter = $this->getUsersForFilter(auth()->user());
            
            $data = [
                'consultations' => $consultations,
                'filters' => $this->formatFiltersForPdf($request),
                'total' => $consultations->count(),
                'export_date' => Carbon::now()->format('d/m/Y H:i:s'),
                'requested_by' => auth()->user()->name,
                'filtros_texto' => $this->getFiltersAsText($request),
                'usersForFilter' => $usersForFilter,
            ];
            
            $pdf = Pdf::loadView('admin.reports.pdf_template', $data);
            $pdf->setPaper('A4', 'portrait');
            
            return $pdf->stream('reporte-consultas-' . date('Y-m-d-His') . '.pdf');
            
        } catch (\Exception $e) {
            \Log::error('Error en viewPdf: ' . $e->getMessage());
            return redirect()->route('admin.reports.index')
                ->with('error', 'Error al generar PDF: ' . $e->getMessage());
        }
    }

    public function downloadPdf(Request $request)
    {
        try {
            $consultations = $this->getConsultationsForPdf($request);
            $usersForFilter = $this->getUsersForFilter(auth()->user());
            
            $data = [
                'consultations' => $consultations,
                'filters' => $this->formatFiltersForPdf($request),
                'total' => $consultations->count(),
                'export_date' => Carbon::now()->format('d/m/Y H:i:s'),
                'requested_by' => auth()->user()->name,
                'filtros_texto' => $this->getFiltersAsText($request),
                'usersForFilter' => $usersForFilter,
            ];
            
            $pdf = Pdf::loadView('admin.reports.pdf_template', $data);
            $pdf->setPaper('A4', 'portrait');
            
            $filename = 'reporte-consultas-' . date('Y-m-d-His') . '.pdf';
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            \Log::error('Error en downloadPdf: ' . $e->getMessage());
            return redirect()->route('admin.reports.index')
                ->with('error', 'Error al descargar PDF: ' . $e->getMessage());
        }
    }

    private function getConsultationsForPdf(Request $request)
    {
        $currentUser = auth()->user();
        $currentUserId = $currentUser->id;
        
        $query = Consultation::with([
            'appointment.patient.user',
            'appointment.doctor.user'
        ]);
        
        if ($currentUser->hasRole('Paciente')) {
            $query->whereHas('appointment.patient.user', function($q) use ($currentUserId) {
                $q->where('id', $currentUserId);
            });
        }
        elseif ($currentUser->hasRole('Doctor')) {
            $query->whereHas('appointment.doctor.user', function($q) use ($currentUserId) {
                $q->where('id', $currentUserId);
            });
        }
        
        if ($request->filled('filter_user')) {
            $filteredUserId = $request->filter_user;
            
            if ($currentUser->hasRole('Paciente')) {
                $query->whereHas('appointment.doctor.user', function($q) use ($filteredUserId) {
                    $q->where('id', $filteredUserId);
                });
            }
            elseif ($currentUser->hasRole('Doctor')) {
                $query->whereHas('appointment.patient.user', function($q) use ($filteredUserId) {
                    $q->where('id', $filteredUserId);
                });
            }
            elseif ($currentUser->hasRole(['Admin', 'Recepcionista'])) {
                $query->where(function($q) use ($filteredUserId) {
                    $q->whereHas('appointment.patient.user', function($q) use ($filteredUserId) {
                        $q->where('id', $filteredUserId);
                    })->orWhereHas('appointment.doctor.user', function($q) use ($filteredUserId) {
                        $q->where('id', $filteredUserId);
                    });
                });
            }
        }
        
        $periodType = $request->get('period_type', 'month');
        
        if ($periodType === 'month') {
            $month = $request->filled('filter_month') ? $request->filter_month : date('n');
            $year = $request->filled('filter_year') ? $request->filter_year : date('Y');
            
            if ($month && $year) {
                $query->whereHas('appointment', function($q) use ($month, $year) {
                    $q->whereMonth('date', $month)
                    ->whereYear('date', $year);
                });
            }
        }
        elseif ($periodType === 'week' && $request->filled('filter_week')) {
            $weekData = explode('-W', $request->filter_week);
            if (count($weekData) === 2) {
                $year = $weekData[0];
                $week = $weekData[1];
                
                $query->whereHas('appointment', function($q) use ($year, $week) {
                    $q->whereYear('date', $year)
                    ->whereRaw('WEEK(date, 1) = ?', [$week]);
                });
            }
        }
        elseif ($periodType === 'day' && $request->filled('filter_day')) {
            $query->whereHas('appointment', function($q) use ($request) {
                $q->whereDate('date', $request->filter_day);
            });
        }
        elseif ($periodType === 'range' && $request->filled('start_date') && $request->filled('end_date')) {
            $query->whereHas('appointment', function($q) use ($request) {
                $q->whereBetween('date', [$request->start_date, $request->end_date]);
            });
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('diagnosis', 'like', "%{$search}%")
                ->orWhere('treatment', 'like', "%{$search}%")
                ->orWhereHas('appointment.patient.user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('appointment.doctor.user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }
        
        // SOLO ESTA LÍNEA DEBE QUEDAR, ELIMINA LA DUPLICADA
        // $query = Consultation::with([...])->select('*'); // ¡ELIMINA ESTA LÍNEA!
        
        return $query->orderBy('created_at', 'desc')->get();
    }

    private function getFiltersAsText(Request $request)
    {
        $text = [];
        
        try {
            if ($request->filled('filter_user')) {
                $user = User::find($request->filter_user);
                if ($user) {
                    $text[] = "Usuario: " . $user->name;
                }
            }
            
            $periodType = $request->get('period_type', 'month');
            
            if ($periodType === 'month') {
                $month = $request->filled('filter_month') ? (int)$request->filter_month : null;
                $year = $request->filled('filter_year') ? (int)$request->filter_year : null;
                
                if ($month && $year) {
                    $date = Carbon::createFromDate($year, $month, 1);
                    $monthName = $date->locale('es')->monthName;
                    $text[] = "Mes: $monthName $year";
                }
            }
            
            if ($periodType === 'week' && $request->filled('filter_week')) {
                $week = $request->filter_week;
                if (preg_match('/^(\d{4})-W(\d{2})$/', $week, $matches)) {
                    $year = $matches[1];
                    $weekNum = $matches[2];
                    $text[] = "Semana: $weekNum de $year";
                }
            }
            
            if ($periodType === 'day' && $request->filled('filter_day')) {
                try {
                    $date = Carbon::parse($request->filter_day);
                    $text[] = "Día: " . $date->format('d/m/Y');
                } catch (\Exception $e) {
                    $text[] = "Día: " . $request->filter_day;
                }
            }
            
            if ($periodType === 'range') {
                if ($request->filled('start_date')) {
                    try {
                        $start = Carbon::parse($request->start_date)->format('d/m/Y');
                        $text[] = "Desde: $start";
                    } catch (\Exception $e) {
                        $text[] = "Desde: " . $request->start_date;
                    }
                }
                if ($request->filled('end_date')) {
                    try {
                        $end = Carbon::parse($request->end_date)->format('d/m/Y');
                        $text[] = "Hasta: $end";
                    } catch (\Exception $e) {
                        $text[] = "Hasta: " . $request->end_date;
                    }
                }
            }
            
            if ($request->filled('search')) {
                $text[] = "Búsqueda: " . $request->search;
            }
            
        } catch (\Exception $e) {
            \Log::warning('Error en getFiltersAsText: ' . $e->getMessage());
        }
        
        return implode(' | ', $text);
    }

    private function formatFiltersForPdf(Request $request)
    {
        $filters = [];
        
        if ($request->filled('filter_user')) {
            $user = User::find($request->filter_user);
            if ($user) {
                $filters['Usuario'] = $user->name;
            }
        }
        
        $periodType = $request->get('period_type', 'month');
        
        if ($periodType === 'month') {
            $month = $request->filled('filter_month') ? $request->filter_month : date('n');
            $year = $request->filled('filter_year') ? $request->filter_year : date('Y');
            
            if ($month && $year) {
                $monthName = Carbon::create()->month((int)$month)->locale('es')->monthName;
                $filters['Periodo'] = "$monthName $year";
            }
        }
        elseif ($periodType === 'week' && $request->filled('filter_week')) {
            $weekData = explode('-W', $request->filter_week);
            if (count($weekData) === 2) {
                $year = $weekData[0];
                $week = $weekData[1];
                $filters['Semana'] = "Semana $week de $year";
            }
        }
        elseif ($periodType === 'day' && $request->filled('filter_day')) {
            $filters['Día'] = Carbon::parse($request->filter_day)->format('d/m/Y');
        }
        elseif ($periodType === 'range' && $request->filled('start_date') && $request->filled('end_date')) {
            $start = Carbon::parse($request->start_date)->format('d/m/Y');
            $end = Carbon::parse($request->end_date)->format('d/m/Y');
            $filters['Rango'] = "$start al $end";
        }
        
        if ($request->filled('search')) {
            $filters['Búsqueda'] = $request->search;
        }
        
        return $filters;
    }

    public function export(Request $request)
    {
        return redirect()->route('admin.reports.index')
                         ->with('info', 'Función de exportación en desarrollo');
    }
}