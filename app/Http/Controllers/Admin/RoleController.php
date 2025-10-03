<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $request -> validate([
        'name'=> 'required|unique:roles,name',
       ]);

       Role::create(['name'=> $request->name]);
       session()->flash('swal',[
        'title'=>'Rol creado correctamente',
        'text'=>'El Rol ha sido creado exitosamente',
        'icon'=> 'success',
       ]);
            return redirect()->route('admin.roles.index');

            
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
    return view('admin.roles.show',compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        if ($role->id <= 4) {
        session()->flash('swal', [
            'title' => 'Error',
            'text'  => 'No puedes editar este Rol',
            'icon'  => 'error',
        ]);

        return back();

        }
    return view('admin.roles.edit',compact('role'));
  
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
             $request -> validate([
        'name'=> 'required|unique:roles,name,' . $role->id,
       ]);

       $role->update(['name'=> $request->name]);
       session()->flash('swal',[
        'title'=>'Rol Actualizado correctamente',
        'text'=>'El Rol ha sido Actualizado exitosamente',
        'icon'=> 'success',
       ]);
            return redirect()->route('admin.roles.edit', $role);

         
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {

        
 if ($role->id <= 4) {
        session()->flash('swal', [
            'title' => 'Error',
            'text'  => 'No puedes eliminar este Rol',
            'icon'  => 'error',
        ]);

        return back();}
  


        $roleName = $role->name;
        $role->delete();
        session()->flash('swal',[
        'title'=>"Rol $roleName eliminado",
        'text'=>'Se eliminó correctamente',
        'icon'=> 'success',
       ]);

        return redirect()->route('admin.roles.index');

    }
}
