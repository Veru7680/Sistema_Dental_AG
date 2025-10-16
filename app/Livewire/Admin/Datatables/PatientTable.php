<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;


class PatientTable extends DataTableComponent
{
     public function builder(): Builder
    {
    return Patient::query()
    ->with('user');
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
            Column::make("Telefono", "user.phone")
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
                return view('admin.patients.actions',
            [   'patient'=> $row

            ]);
                })
          
        ];
    }
}
