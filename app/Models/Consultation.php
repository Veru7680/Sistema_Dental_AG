<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable =[
        'appointment_id',
        'diagnosis',
        'treatment',
        'notes',
        'service_cost', // <-- AÑADE ESTA LÍNEA
        'prescriptions'

    ];

    protected $casts =[
       
        'prescriptions' => 'array',
         'service_cost' => 'decimal:2', // <-- AÑADE ESTA LÍNEA PARA FORMATEO AUTOMÁTICO

    ];

    public function appointment(){
        return $this->belongsTo(Appointment::class);
    }
    
}
