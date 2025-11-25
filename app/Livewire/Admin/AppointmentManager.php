<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Carbon\Carbon;            
use Carbon\CarbonPeriod;   
use App\Models\Speciality;
use App\Models\Appointment;
use Illuminate\Validation\Rule;
use App\Services\AppointmentService;


class AppointmentManager extends Component
{

    public ?Appointment $appointmentEdit= null;

    public $search = [
        'date' => '',
        'hour' => '',
        'speciality_id' => '',

    ];

    public $selectedSchedules = [
        'doctor_id' => '',
        'schedules' => [],
    ];

    public $specialities = [];

    public $availabilities = [];

    public $appointment = [
        'patient_id' => '',
        'doctor_id' => '',
        'date' => '',
        'start_time' => '',
        'end_time' => '',
        'duration' => '',
        'reason' => ''
        
    ];

    public function mount()
    {
        $this->specialities = Speciality::all();
        $this->search['date'] = now()->hour >= 19 //hora antes de cambiae horario osea acaba a klas 7pm en ejemplo estaa en 12
            ? now()->addDay()->format('Y-m-d')
            : now()->format('Y-m-d');

         if ($this->appointmentEdit){
         $this->appointment['patient_id'] = $this->appointmentEdit->patient_id;
         } 
         
         if (auth()->user()->hasRole('Paciente')){
            $this->appointment['patient_id'] = auth()->user()->patient->id;
         }

    }

    public function updated($property, $value)
    {
        if($property === 'selectedSchedules') {
            $this->fillAppointment($value);
        }
    }

    #[Computed()]
        public function hourBlocks()
            {
                return CarbonPeriod::create(
                Carbon::createFromTimeString(config('schedule.start_time')),
                '1 hour',
                Carbon::createFromTimeString(config('schedule.end_time'))
                )->excludeEndDate();
            }
    
     #[Computed()]
        public function doctorName(){
            return $this->appointment['doctor_id']
            ? $this->availabilities[$this->appointment['doctor_id']]['doctor']->user->name 
            : 'Por definir';
        }

        public function searchAvailability(AppointmentService $service)
        {
                $this->validate([
                    'search.date' => 'required|date|after_or_equal:today',
                    'search.hour' => [
                        'required',
                        'date_format:H:i:s',
                       Rule::when($this->search['date'] === now()->format('Y-m-d'),[
                        'after_or_equal:' . now()->format('H:i:s')
                       ]),
                    ],
                    //'search.specialist_id' => 'required|exists:specialists,id',
                ]);

                $this->appointment['date'] = $this->search['date'];

                //Buscar disponibilidad
               $this->availabilities = $service->searchAvailability(...$this->search);
        }

        public function fillAppointment($selectedSchedules)
        {
            $schedules = collect($selectedSchedules['schedules'])
            ->sort()
            ->values();
            if($schedules->count()){
                $this->appointment['doctor_id'] = $selectedSchedules['doctor_id'];
                $this->appointment['start_time'] = $schedules->first();
                $this->appointment['end_time'] = Carbon::parse($schedules->last())->addMinutes(
                    config('schedule.appointment_duration')
                )->format('H:i:s');
                $this->appointment['duration'] = $schedules->count() * config('schedule.appointment_duration');
                return;
            } 
                
                 $this->appointment['doctor_id'] = ""; 
                $this->appointment['start_time'] = ""; 
                  $this->appointment['end_time'] = ""; 
                  $this->appointment['duration'] = ""; 

        }

        public function save()
        {
            $this->validate([
                'appointment.patient_id' => 'required|exists:patients,id',
                'appointment.doctor_id' => 'required|exists:doctors,id',
                'appointment.date' => 'required|date|after_or_equal:today',
                'appointment.start_time' => 'required|date_format:H:i:s',
                'appointment.end_time' => 'required|date_format:H:i:s|after:appointment.start_time',

                'appointment.reason' => 'nullable|string|max:500',
            ]);

            if($this->appointmentEdit){
                $this->appointmentEdit->update($this->appointment);
                $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Cita actualizada Existosamente',
                'text' => 'La cita ha sido actualizada correctamente.'
                ]);

                $this->searchAvailability(new AppointmentService());
                return;
            }

            Appointment::create($this->appointment)
            ->consultation()
            ->create([]);

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Cita creada Existosamente',
                'text' => 'La cita ha sido registrado correctamente.'
            ]);

            return redirect()->route('admin.appointments.index');

            //Guardar cita
            //dd($this->appointment);

        }

     public function render()
    {
        return view('livewire.admin.appointment-manager');
    }
}
