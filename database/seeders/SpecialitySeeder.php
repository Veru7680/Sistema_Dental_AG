<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $specialities =[
        'Odontología general',
        'Ortodoncia',
        'Endodoncia',
        'Periodoncia',
        'Odontopediatría',
        'Rehabilitación oral / Prótesis dental',
        'Estética dental',
        'Odontología preventiva',
       ];
       foreach($specialities as $speciality){
        \App\Models\Speciality::create([
            'name'=> $speciality]);
       }
    }
}
