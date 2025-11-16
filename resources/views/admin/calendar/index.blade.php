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

    <div x-data="data()">
        <div x-ref='calendar'></div>
    </div>

         @push('js')
         <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js'></script>
         
         <script>
            function data(){
                return {
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
                                  alert('Hubo un errror al cargar los eventos');  
                                }
                            },
                              srollTime:"{{ date('H:i:s')}}",
                            });
                            calendar.render();  
                    }
                }
            }
         </script>
         @endpush
</x-admin-layout>