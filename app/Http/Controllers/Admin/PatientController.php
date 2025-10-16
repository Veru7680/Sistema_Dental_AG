<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
     return view('admin.patients.index');
    }
    
    public function show(Patient $patient)
    {
    return view('admin.patients.show', compact('patient'));

    }

    public function edit(Patient $patient)
    {
    return view('admin.patients.edit', compact('patient'));

    }

   
    public function update(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'allergias' => 'nullable|string|max:255',
            'chronic_conditions' => 'nullable|string|max:255',
            'observations' => 'nullable|string|max:255',
            'emergency_contact_name' =>'nullable|string|max:255',
            'emergency_contact_phone' =>'nullable|string|max:255',
            'emergency_contact_relationship' =>'nullable|string|max:255'

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
