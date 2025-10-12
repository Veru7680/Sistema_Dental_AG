<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
     return view('admin.patients.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
    return view('admin.patients.show', compact('patient'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
    return view('admin.patients.edit', compact('patient'));

    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        //
    }
}
