<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $user = User::factory()->create([
            'name' => 'Vero Vargas',
            'email' => 'veru6198@gmail.com',
            'password'=> bcrypt('10631189'),
            'ci'=> '10631189',
            'phone'=> '76802026',
            'address'=> 'calle 14 esquina 109, Barrio Nuevo',
         ]);
         
         $user->assignRole('Admin');
         $user->doctor()->create();
    }
}
