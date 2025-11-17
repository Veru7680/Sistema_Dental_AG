<x-admin-layout
title=" Calendario | Dental AG"

:breadcrumbs="[
    [
        'name'=>'Dashboard',
        'href'=> route('admin.dashboard'),
    ],

    [
        'name'=>'Calendario',
    ]
    
    ]"  >

        @push('css')
        <style>
            .fc-event{
                cursor:pointer;
            }
        </style>
        @endpush

    <div x-data="data()">
        <x-wire-modal-card 
            title="Cita Medica" 
            name="appointmentModal"
            width="md"
            align="center"
            >

            <div class="space-y-4 mb-4">
                <div>
                    <strong>Hora y Fecha</strong>
                    <span x-text="selectedEvent.dateTime"></span>
                </div>

                <div>
                    <strong>Paciente</strong>
                    <span x-text="selectedEvent.patient"></span>
                </div>

                <div>
                    <strong>Medico</strong>
                    <span x-text="selectedEvent.doctor"></span>
                </div>

                <div>
                    <strong>Estado</strong>
                    <span x-text="selectedEvent.status"></span>
                </div>
                
            </div>

            <a x-bind:href="selectedEvent.url" class="w-full">
                <x-wire-button class="w-full">
                Gestionar Cita
                </x-wire-button>
           </a>
        </x-wire-modal-card>

        <div x-ref='calendar'></div>
    </div>

         @push('js')
         <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js'></script>
         
         <script>
            function data(){
                return {
                    selectedEvent:{
                        dateTime: null,
                        patient: null,
                        doctor: null,
                        status: null, 
                        color: null, 
                        url: null,
                    },

                    openModal(info)
                    {
                        this.selectedEvent = {
                                    dateTime: info.event.extendedProps.dateTime, 
                                    patient: info.event.extendedProps.patient, 
                                    doctor: info.event.extendedProps.doctor, 
                                    status: info.event.extendedProps.status, 
                                    color: info.event.extendedProps.color, 
                                    url: info.event.extendedProps.url,
                                };
                         $openModal('appointmentModal');
                    },

                    init(){
                      var calendarEl = this.$refs.calendar;
                       var calendar = new FullCalendar.Calendar(calendarEl, {
                       headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                        },
                        locale: 'es',
                            buttonText:{
                                today: 'Hoy',
                                month: 'Mes',
                                week: 'Semana',
                                day: 'Dia',
                                list: 'Lista'
                            },
                            allDayText: 'Todo el dia',
                            noEventsText: 'No hay eventos para mostrar',
                            
                            initialView: 'timeGridWeek',
                            slotDuration:'00:15:00',
                            slotMinTime:"{{ config('schedule.start_time')}}",
                            slotMaxTime:"{{ config('schedule.end_time')}}",
                                
                            events:{
                                url:"{{ route('api.appointments.index')}}",
                                failure: function(){
                                  alert('Hubo un error al cargar los eventos');  
                                }
                            },
                            eventClick: (info)=> this.openModal(info),

                              scrollTime:"{{ date('H:i:s')}}",
                            });
                            calendar.render();  
                    }
                }
            }
         </script>
         @endpush
</x-admin-layout>