<?php

namespace App\Services;

use Illuminate\Support\Carbon; // Usar Carbon de Laravel
use App\Models\Doctor;

class AppointmentService
{
  public function searchAvailability($date, $hour, $speciality_id)
  {
     $date = Carbon::parse($date);
     $hourStart = Carbon::parse($hour)->format('H:i:s');
     $hourEnd = Carbon::parse($hour)->addHour()->format('H:i:s');

     $doctors = Doctor::whereHas('schedules', function ($q) use($date, $hourStart, $hourEnd) {
       $q->where('day_of_week', $date->dayOfWeek)
        ->whereTime('start_time', '>=', $hourStart)
        ->whereTime('start_time', '<', $hourEnd);
     })
     ->when($speciality_id, function ($q, $speciality_id) {
      return $q->where('speciality_id', $speciality_id);
     })
     ->with([
      'user',
      'speciality',
      'schedules' => function ($q) use($date, $hourStart, $hourEnd){
        $q->where('day_of_week', $date->dayOfWeek)
          ->whereTime('start_time', '>=', $hourStart)
          ->whereTime('start_time', '<', $hourEnd);
      },
      'appointments'=>function($q) use($date, $hourStart, $hourEnd){
        $q->whereDate('date', $date)
          ->whereTime('start_time', '>=', $hourStart)
          ->whereTime('start_time', '<', $hourEnd);
      }
     ])
     ->get();
     
     return $this->processResults($doctors);
     
  }

  public function processResults($doctors)
  {
    return $doctors->mapWithKeys(function($doctor){
      return [
        $doctor->id => [
          'doctor' => $doctor,
        'schedules' => $doctor->schedules->map(function($schedule){
          return [
            'start_time' => $schedule->start_time->format('H:i:s'),   
          ];
        })->toArray(),
        ]
      ];
    });

  }
}
