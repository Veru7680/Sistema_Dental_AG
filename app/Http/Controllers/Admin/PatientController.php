<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PatientController extends Controller
{
    public function index()
    {
        Gate::authorize('read_paciente');
     return view('admin.patients.index');
    }
    
    public function show(Patient $patient)
    {
    return view('admin.patients.show', compact('patient'));

    }

    public function edit(Patient $patient)
{
    Gate::authorize('update_paciente');
    
    // Primero verifica si existe la relación
    try {
        $previusConsultations = \App\Models\Consultation::whereIn('appointment_id', 
            \App\Models\Appointment::where('patient_id', $patient->id)->pluck('id')
        )
        ->with(['appointment.doctor.user'])
        ->orderBy('created_at', 'desc')
        ->get();
        
        // Intenta cargar prescriptions si existe
        $previusConsultations->load('prescriptions');
        
    } catch (\Exception $e) {
        // Si falla, carga sin prescriptions
        $previusConsultations = \App\Models\Consultation::whereIn('appointment_id', 
            \App\Models\Appointment::where('patient_id', $patient->id)->pluck('id')
        )
        ->with(['appointment.doctor.user'])
        ->orderBy('created_at', 'desc')
        ->get();
    }

    return view('admin.patients.edit', compact('patient', 'previusConsultations'));
}
    public function update(Request $request, Patient $patient)
    {
        Gate::authorize('update_paciente');
        $data = $request->validate([
            'allergias' => 'nullable|string|max:255',
            'chronic_conditions' => 'nullable|string|max:255',
            'observations' => 'nullable|string|max:255',
            'emergency_contact_name' =>'nullable|string|max:255',
            'emergency_contact_phone' =>'nullable|string|max:255',
            'emergency_contact_relationship' =>'nullable|string|max:255',
            'active'=> 'boolean'

            ]);

            session()->flash('swal',[
                'icon'=>'success',
                'title'=>'Paciente Actualizado',
                'text'=>'Los datos de paciente fueron actualizados correctamente',
            ]);

        $patient->update($data);
        return redirect()->route('admin.patients.edit', $patient);
    }

}
