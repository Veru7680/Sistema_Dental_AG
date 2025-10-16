<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\Speciality;


class DoctorController extends Controller
{
    public function index()
    {
        return view('admin.doctors.index');
    }

   
    public function edit(Doctor $doctor)
    {
        $specialities= Speciality::all();
        return view('admin.doctors.edit', compact('doctor', 'specialities'));
    }

  
    public function update(Request $request, Doctor $doctor)
    {
        
       $data= $request->validate([
            'speciality_id'=> 'nullable|exists:specialities,id',
            'active'=> 'boolean',
        ]);

        $doctor->update($data);

        session()->flash('swal',[
        'icon'=>'success',
        'title'=>'Doctor Actualizado',
        'text'=>'Los datos del Doctor fueron actualizados correctamente',
    ]);
       return redirect()->route('admin.doctors.edit',$doctor);
    }

    public function schedules(Doctor $doctor){
        return view('admin.doctors.schedules', compact('doctor'));
    }

}
