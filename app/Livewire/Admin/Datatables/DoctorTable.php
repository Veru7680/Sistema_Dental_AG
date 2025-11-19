<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Builder;

class DoctorTable extends DataTableComponent
{
    public function builder(): Builder
    {
    return Doctor::query()
     ->whereHas('user', function (Builder $query){
        $query->role('doctor');
    }) 
    ->with(['user', 'speciality']);
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

        Column::make("Nombre", "user.name")
            ->sortable(),

        Column::make("Email", "user.email")
            ->sortable(),

        Column::make("Ci", "user.ci")
            ->sortable(),

        Column::make("Teléfono", "user.phone")
            ->sortable(),

        Column::make("Especialidad ", "speciality.name")
           ->format(function ($value){
            return $value ?: 'N/A';
           })
            ->sortable(),

            Column::make("Estado", "active")
            ->format(function ($value, $row) {
                if ($row->active) {
                    return '<span class="bg-green-100 text-green-800 font-bold px-2 py-1 rounded">Activo</span>';
                } else {
                    return '<span class="bg-red-100 text-red-800 font-bold px-2 py-1 rounded">Inactivo</span>';
                }
            })
            ->html()
            ->sortable(),

            

        Column::make("Acciones")
                ->label(function($row){
                return view('admin.doctors.actions',
            [   'doctor'=> $row

            ]);
                })

                

                

        ];
    }
}
