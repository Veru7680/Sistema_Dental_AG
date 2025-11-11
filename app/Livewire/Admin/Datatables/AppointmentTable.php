<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;

class AppointmentTable extends DataTableComponent
{
    public function builder(): Builder
    {
    return Appointment::query()
    ->with(['patient.user', 'doctor.user']);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Paciente", "patient.user.name")
                ->sortable(),
            Column::make("Doctor", "doctor.user.name")
                ->sortable(),
            Column::make("Fecha", "date")
                ->format(function ($value){
                    return $value->format('d/m/Y');
                })
                ->sortable(),
            Column::make("Hora inicio", "start_time")
                ->format(function ($value){
                    return $value->format('H:i');
                })
                ->sortable(),
            Column::make("Hora fin", "end_time")
                ->format(function ($value){
                    return $value->format('H:i');
                })
                ->sortable(),  
                
             Column::make("Acciones")
                ->label(function($row){
                return view('admin.appointments.actions',
                ['appointment' => $row
            ]);
            } )
        ];
       
    }
}
