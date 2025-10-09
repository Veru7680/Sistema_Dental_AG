<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/* Relaciones uno a uno*/
class Patient extends Model
{

    protected $fillable=[
        'user_id',
        'allergias',
        'chronic_conditions',
        'observations',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }
}
