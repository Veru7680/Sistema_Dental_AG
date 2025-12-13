<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Consultation;
use App\Models\User;
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
            elseif ($currentUser->hasRole(['admin', 'Recepcionista'])) {
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
        return view('admin.reports.index', compact(
            'consultations', 
            'usersForFilter'
        ));
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
        if ($currentUser->hasRole(['admin', 'Recepcionista'])) {
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
        
        return collect(); // Por defecto, colección vacía
    }
    
    public function export(Request $request)
    {
        return redirect()->route('admin.reports.index')
                         ->with('info', 'Función de exportación en desarrollo');
    }
}