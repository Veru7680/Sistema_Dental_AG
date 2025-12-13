<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Consultation;
use App\Models\Patient;
use App\Models\Doctor;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // 1. Obtener datos para los filtros
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user')->get();
        
        // 2. Consulta base
        $query = Consultation::with([
            'appointment.patient.user',
            'appointment.doctor.user'
        ]);
        
        // 3. FILTRO: Por usuario que solicita (si se seleccionó)
        // COMENTADO porque no tienes columna 'created_by'
        // if ($request->filled('filter_user')) {
        //     $query->where('created_by', $request->filter_user); // ESTA LÍNEA CAUSA EL ERROR
        // }
        
        // Si quieres filtrar por usuario, necesitas definir cómo:
        // Opción A: Si quieres filtrar por usuario que CREÓ la consulta
        // Primero asegúrate de que tu tabla consultations tenga columna 'user_id' o 'created_by'
        
        // Opción B: Filtrar por usuario relacionado con paciente/doctor
        if ($request->filled('filter_user')) {
            $userId = $request->filter_user;
            
            $query->where(function($q) use ($userId) {
                // Filtrar por paciente relacionado
                $q->whereHas('appointment.patient.user', function($q) use ($userId) {
                    $q->where('id', $userId);
                })
                // O filtrar por doctor relacionado
                ->orWhereHas('appointment.doctor.user', function($q) use ($userId) {
                    $q->where('id', $userId);
                });
            });
        }
        
        // 4. FILTROS POR PERIODO
        $periodType = $request->get('period_type', 'month');
        
        if ($periodType === 'month') {
            // Usar valores por defecto si no se especifican
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
        
        // 5. FILTRO: Por búsqueda de texto
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
        
        // 6. Paginar resultados
        $consultations = $query->orderBy('created_at', 'desc')
                               ->paginate(15)
                               ->appends($request->query());
        
        // 7. Pasar a la vista
        return view('admin.reports.index', compact('consultations', 'patients', 'doctors'));
    }
    
public function exportToPdf(Request $request)
{
    try {
        // PRIMERO: Obtener datos REALES (quita los datos de prueba)
        $consultations = $this->getRealConsultations($request);
        
        if ($consultations->isEmpty()) {
            // Si no hay datos, mostrar mensaje en la vista
            $data = [
                'consultations' => collect(),
                'filters' => $this->formatFilters($request),
                'total' => 0,
                'export_date' => now()->format('d/m/Y H:i:s'),
                'requested_by' => auth()->user()->name,
                'mensaje_error' => 'No hay consultas con los filtros aplicados',
            ];
        } else {
            // Si hay datos, pasarlos normalmente
            $data = [
                'consultations' => $consultations,
                'filters' => $this->formatFilters($request),
                'total' => $consultations->count(),
                'export_date' => now()->format('d/m/Y H:i:s'),
                'requested_by' => auth()->user()->name,
            ];
        }
        
        // Cargar la vista
        $pdf = Pdf::loadView('admin.reports.pdf_template', $data);
        
        // Configurar
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('defaultFont', 'DejaVu Sans');
        
        // ✅ IMPORTANTE: Usar STREAM para ver en navegador
        // Cambia esta línea:
        return $pdf->stream('reporte-consultas.pdf');
        
        // ❌ NO usar download() si quieres ver primero
        // return $pdf->download('reporte-consultas.pdf');
        
    } catch (\Exception $e) {
        // Para depuración, muestra el error
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
}



// Método para VER PDF en navegador
public function viewPdf(Request $request)
{
    try {
        // Usar EXACTAMENTE la misma lógica que index()
        $consultations = $this->getConsultationsWithFilters($request, true);
        
        $data = [
            'consultations' => $consultations,
            'filters' => $this->formatFiltersForPdf($request),
            'total' => $consultations->count(),
            'export_date' => Carbon::now()->format('d/m/Y H:i:s'),
            'requested_by' => auth()->user()->name,
            'filtros_texto' => $this->getFiltersAsText($request),
        ];
        
        $pdf = Pdf::loadView('admin.reports.pdf_template', $data);
        $pdf->setPaper('A4', 'portrait'); // Landscape para mejor tabla
        
        // VER en navegador
        return $pdf->stream('reporte-consultas.pdf');
        
    } catch (\Exception $e) {
        \Log::error('Error en viewPdf: ' . $e->getMessage());
        return redirect()->route('admin.reports.index')
            ->with('error', 'Error al generar PDF: ' . $e->getMessage());
    }
}

// Método para DESCARGAR PDF
public function downloadPdf(Request $request)
{
    try {
        // Usar EXACTAMENTE la misma lógica que index()
        $consultations = $this->getConsultationsWithFilters($request, true);
        
        $data = [
            'consultations' => $consultations,
            'filters' => $this->formatFiltersForPdf($request),
            'total' => $consultations->count(),
            'export_date' => Carbon::now()->format('d/m/Y H:i:s'),
            'requested_by' => auth()->user()->name,
            'filtros_texto' => $this->getFiltersAsText($request),
        ];
        
        $pdf = Pdf::loadView('admin.reports.pdf_template', $data);
        $pdf->setPaper('A4', 'portrait'); // Landscape para mejor tabla
        
        // DESCARGAR directamente
        $filename = 'reporte-consultas-' . date('Y-m-d-His') . '.pdf';
        return $pdf->download($filename);
        
    } catch (\Exception $e) {
        \Log::error('Error en downloadPdf: ' . $e->getMessage());
        return redirect()->route('admin.reports.index')
            ->with('error', 'Error al descargar PDF: ' . $e->getMessage());
    }
}

private function getFiltersAsText(Request $request)
{
    $text = [];
    
    try {
        // Usuario
        if ($request->filled('filter_user')) {
            $user = \App\Models\User::find($request->filter_user);
            if ($user) {
                $text[] = "Usuario: " . $user->name;
            } else {
                $text[] = "Usuario ID: " . $request->filter_user;
            }
        }
        
        // Periodo
        $periodType = $request->get('period_type', 'month');
        
        if ($periodType === 'month') {
            $month = $request->filled('filter_month') ? (int)$request->filter_month : null;
            $year = $request->filled('filter_year') ? (int)$request->filter_year : null;
            
            if ($month && $year) {
                // CORRECCIÓN: Usar createFromDate con enteros
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
            } else {
                $text[] = "Semana: $week";
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
        
        // Búsqueda
        if ($request->filled('search')) {
            $text[] = "Búsqueda: " . $request->search;
        }
        
    } catch (\Exception $e) {
        \Log::warning('Error en getFiltersAsText: ' . $e->getMessage());
        // Continuar sin este texto
    }
    
    return implode(' | ', $text);
}

/**
 * MÉTODO ÚNICO DE FILTRADO - COPIA EXACTA DEL index()
 */
private function getConsultationsWithFilters(Request $request, $forPdf = true)
{
    // COPIAR EXACTAMENTE la lógica del método index()
    $query = Consultation::with([
        'appointment.patient.user',
        'appointment.doctor.user'
    ]);
    
    // 1. FILTRO POR USUARIO (igual que index())
    if ($request->filled('filter_user')) {
        $userId = $request->filter_user;
        
        $query->where(function($q) use ($userId) {
            // Filtrar por paciente relacionado
            $q->whereHas('appointment.patient.user', function($q) use ($userId) {
                $q->where('id', $userId);
            })
            // O filtrar por doctor relacionado
            ->orWhereHas('appointment.doctor.user', function($q) use ($userId) {
                $q->where('id', $userId);
            });
        });
    }
    
    // 2. FILTROS POR PERIODO (igual que index())
    $periodType = $request->get('period_type', 'month');
    
    if ($periodType === 'month') {
        // Usar valores por defecto si no se especifican
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
    
    // 3. FILTRO: Por búsqueda de texto (igual que index())
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
    
    // Ordenar igual que index()
    $query->orderBy('created_at', 'desc');
    
    // Para PDF: obtener TODOS los resultados (sin paginación)
    if ($forPdf) {
        return $query->get();
    }
    
    // Para web: paginar (como en index())
    return $query->paginate(15)->appends($request->query());
}

/**
 * Formatear filtros para mostrar en PDF
 */
private function formatFiltersForPdf(Request $request)
{
    $filters = [];
    
    // Usuario filtrado
    if ($request->filled('filter_user')) {
        $user = \App\Models\User::find($request->filter_user);
        if ($user) {
            $filters['Usuario'] = $user->name;
        }
    }
    
    // Periodo
    $periodType = $request->get('period_type', 'month');
    
    if ($periodType === 'month') {
        $month = $request->filled('filter_month') ? $request->filter_month : date('n');
        $year = $request->filled('filter_year') ? $request->filter_year : date('Y');
        
        if ($month && $year) {
$monthName = Carbon::create()->month((int)$month)->locale('es')->monthName;            $filters['Periodo'] = "$monthName $year";
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
    
    // Búsqueda
    if ($request->filled('search')) {
        $filters['Búsqueda'] = $request->search;
    }
    
    return $filters;
}

}