<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable =[
        'patient_id',
        'doctor_id',
        'date',
        'start_time',
        'end_time',
        'duration',
        'reason',
        'status',
    ];

    protected $casts =[
        'dates' =>'date',
        'tart_time' =>'datetime',
        'end_timek' =>'time',
        'status' => AppointmentEnum::class,
    ];

    public function patient(){
            return $this->belongsTo(Patient::class);
    }

    public function dotor(){
        return $this->belongsTo(Doctor::class);
    }


}
