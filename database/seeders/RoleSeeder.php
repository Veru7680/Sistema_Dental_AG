<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
        'name' => 'Admin',
        ])->givePermissionTo(Permission::all());
        $roles=[
            'Paciente' =>[
                'access_dashboard',

                'create_appointment',
                'read_appointment',
               
            ],
            'Doctor'=>[
                'access_dashboard',

                'create_appointment',
                'read_appointment',
                'delete_appointment',
                'update_appointment',

                
            ],
            'Recepcionista'=>[
                'access_dashboard',

                'create_user',
                'read_user',
                'update_user',
                'delete_user',

                'read_paciente',
                'update_paciente',

                'read_doctor',
                'update_doctor',

                'create_appointment',
                'read_appointment',
                'update_appointment',
                'delete_appointment',

                'read_calendar', 
            ],
            

        ];
        foreach($roles as $role => $permissions){
            Role::create([
            'name'=>$role,
            ])
            ->givePermissionTo($permissions);
        }
    }
}
