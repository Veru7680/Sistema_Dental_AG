<?php
 return [
    [   'type' => 'link',
        'title' => 'Menu de Inicio',
        'icon' => 'fa-solid fa-chart-pie',
        'route' => 'admin.dashboard',
        'active' => 'admin.dashboard',
        'can' => ['access_dashboard'],
    ],

    [   'type' => 'header',
        'title' => 'Gestion',
        'can' => [
            'read_role',
            'read_user',
            'read_paciente',
            'read_doctor',
            'read_appointment',
            'read_calendar',
        ],
    ],

    [   'type' => 'link',
        'title' => 'Roles y Permisos',
        'icon' => 'fa-solid fa-shield-halved',
        'route' => 'admin.roles.index',
        'active' => 'admin.roles.*',
        'can' => [
            'read_role',
        ],
    ],

    [   'type' => 'link',
        'title' => 'Usuarios',
        'icon' => 'fa-solid fa-users',
        'route' => 'admin.users.index',
        'active' => 'admin.users.*',
        'can' => [
            'read_user',
        ],
    ],

     [   'type' => 'link',
        'title' => 'Pacientes',
        'icon' => 'fa-solid fa-user-injured',
        'route' => 'admin.patients.index',
        'active' => 'admin.patients.*',
        'can' => [
            'read_paciente',
        ],
    ],

    [   'type' => 'link',
        'title' => 'Doctores',
        'icon' => 'fa-solid fa-user-doctor',
        'route' => 'admin.doctors.index',
        'active' => 'admin.doctors.*',
        'can' => [
            'read_doctor',
        ],
    ],

    [   'type' => 'link',
        'title' => 'Citas Medicas',
        'icon' => 'fa-solid fa-calendar-check',
        'route' => 'admin.appointments.index',
        'active' => 'admin.appointments.*',
        'can' => [
            'read_appointment',
        ],
    ],

    [   'type' => 'link',
        'title' => 'Calendario',
        'icon' => 'fa-solid fa-calendar-days',
        'route' => 'admin.calendar.index',
        'active' => 'admin.calendar.*',
        'can' => [
            'read_calendar',
        ],
    ],

    [   'type' => 'link',
        'title' => 'Reportes',
        'icon' => 'fa-solid fa-chart-line', 
        'route' => 'admin.reports.index', 
        'active' => 'admin.reports.*', 
        
    ],
    
];