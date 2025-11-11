<?php

namespace App\Enums;

enum AppointmentEnum: int
{
        case SCHEDULED = 1;
        case COMPLETED = 2;
        case CANCELLED = 3;

        public function label(): string 
        {
                return match($this) {
                self::SCHEDULED => 'Programado',
                self::COMPLETED => 'Completado',
                self::CANCELLED => 'Cancelado',

                };

        }

        public function color(): string 
        {
                return match($this) {
                self::SCHEDULED => 'blue',
                self::COMPLETED => 'green',
                self::CANCELLED => 'red',

                };

        }

        
}
