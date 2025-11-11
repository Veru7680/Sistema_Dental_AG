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
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime', // Cambiado de 'time' a 'datetime'
        'status' => 'string',
       
    ];

    public function patient(){
            return $this->belongsTo(Patient::class);
    }

    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }


}
