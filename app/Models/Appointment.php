<?php

namespace App\Models;
use App\Enums\AppointmentEnum;
use App\Models\Scopes\VerifyRole;
use App\Models\Scopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute; // Necesitas importar Attribute
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Carbon\Carbon;

#[ScopedBy([( VerifyRole:: class)])]
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
        'status' => AppointmentEnum::class,
       
    ];


    //accesores
    public function start(): Attribute
    {
        return Attribute::make(
            get: function(){
                $date = $this->date->format('Y-m-d');
                $time = $this->start_time->format('H:i:s');
                //Retornar formato
                return Carbon::parse("{$date} {$time}");

            }
        );
    }

    public function end(): Attribute
    {
        return Attribute::make(
            get: function(){
                $date = $this->date->format('Y-m-d');
                $time = $this->end_time->format('H:i:s');
                //Retornar formato
                return Carbon::parse("{$date} {$time}");
            }
        );
    }

      //relacion uno a uno
    public function consultation(){
        return $this->hasOne(Consultation::class);
    }
        //relacion inversa
    public function patient(){
            return $this->belongsTo(Patient::class);
    }

    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }

}
