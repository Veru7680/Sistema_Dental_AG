<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
   protected $fillable=[
    'user_is',
    'speciality_id',  
   ];
   //relaciones
   public function user(){
   return $this->belongsTo(User::class);
   }

   public function speciality(){
   return $this->belongsTo(Speciality::class);
   }


}
