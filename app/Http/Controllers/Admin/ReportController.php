<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Consultation;
use App\Models\Patient;
use App\Models\Doctor;

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
    
    public function export(Request $request)
    {
        return redirect()->route('admin.reports.index')
                         ->with('info', 'Función de exportación en desarrollo');
    }
}