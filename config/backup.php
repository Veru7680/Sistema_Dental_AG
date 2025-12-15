<?php

return [

    'backup' => [
        'name' => env('APP_NAME', 'laravel-backup'),

        'source' => [
            'files' => [
                /*
                 * Solo incluir directorios específicos que necesites, 
                 * o dejarlo vacío para no incluir archivos
                 */
                'include' => [
                    // Deja vacío para no incluir archivos, solo DB
                    // Si quieres incluir algún directorio específico, agréguelo aquí
                    // Ejemplo: base_path('storage/app/important-files'),
                ],

                'exclude' => [
                    base_path('vendor'),
                    base_path('node_modules'),
                    base_path('.git'),
                    base_path('storage'),
                    base_path('bootstrap/cache'),
                    base_path('tests'),
                    base_path('public'),
                    base_path('resources'),
                    base_path('config'),
                    base_path('database/migrations'),
                    base_path('database/seeders'),
                    base_path('routes'),
                    base_path('app'),
                ],

                'follow_links' => false,
                'ignore_unreadable_directories' => false,
                'relative_path' => null,
            ],

            /*
             * Solo incluir la base de datos
             */
            'databases' => [
                env('DB_CONNECTION', 'mysql'),
            ],
        ],

        'database_dump_compressor' => null,
        'database_dump_file_timestamp_format' => 'Y-m-d-H-i-s',
        'database_dump_filename_base' => 'database',
        'database_dump_file_extension' => '',

        'destination' => [
            'compression_method' => ZipArchive::CM_DEFAULT,
            'compression_level' => 9,
            
            /*
             * Agregar prefijo con timestamp para identificar fácilmente
             */
            'filename_prefix' => 'backup-',
            
            /*
             * Disco local donde se guardarán las copias
             */
            'disks' => [
                'local',
            ],
        ],

        'temporary_directory' => storage_path('app/backup-temp'),
        
        /*
         * Opcional: agregar contraseña para mayor seguridad
         */
        'password' => env('BACKUP_ARCHIVE_PASSWORD', null),
        'encryption' => 'default',
        'tries' => 1,
        'retry_delay' => 0,
    ],

    'notifications' => [
        'notifications' => [
            \Spatie\Backup\Notifications\Notifications\BackupHasFailedNotification::class => [],
            \Spatie\Backup\Notifications\Notifications\UnhealthyBackupWasFoundNotification::class => [],
            \Spatie\Backup\Notifications\Notifications\CleanupHasFailedNotification::class => [],
            \Spatie\Backup\Notifications\Notifications\BackupWasSuccessfulNotification::class => [],
            \Spatie\Backup\Notifications\Notifications\HealthyBackupWasFoundNotification::class => [],
            \Spatie\Backup\Notifications\Notifications\CleanupWasSuccessfulNotification::class => [],
        ],

        'notifiable' => \Spatie\Backup\Notifications\Notifiable::class,

        'mail' => [
            'to' => env('BACKUP_NOTIFICATION_EMAIL', 'your@example.com'),

            'from' => [
                'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                'name' => env('MAIL_FROM_NAME', 'Example'),
            ],
        ],
    ],

    /*
     * Monitoreo de backups locales
     */
    'monitor_backups' => [
        [
            'name' => env('APP_NAME', 'laravel-backup'),
            'disks' => ['local'],
            'health_checks' => [
                /*
                 * Máximo 1 día de antigüedad (ajusta según tus necesidades)
                 */
                \Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumAgeInDays::class => 1,
                
                /*
                 * Máximo 1 GB de almacenamiento (ajusta según espacio disponible)
                 */
                \Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumStorageInMegabytes::class => 1024,
            ],
        ],
    ],

    'cleanup' => [
        'strategy' => \Spatie\Backup\Tasks\Cleanup\Strategies\DefaultStrategy::class,

        'default_strategy' => [
            /*
             * Mantener todos los backups por 3 días
             */
            'keep_all_backups_for_days' => 3,
            
            /*
             * Mantener backups diarios por 7 días
             */
            'keep_daily_backups_for_days' => 7,
            
            /*
             * Mantener backups semanales por 4 semanas
             */
            'keep_weekly_backups_for_weeks' => 4,
            
            /*
             * Mantener backups mensuales por 2 meses
             */
            'keep_monthly_backups_for_months' => 2,
            
            /*
             * Mantener backups anuales por 1 año
             */
            'keep_yearly_backups_for_years' => 1,
            
            /*
             * Límite de almacenamiento: 5 GB
             */
            'delete_oldest_backups_when_using_more_megabytes_than' => 5120,
        ],

        'tries' => 1,
        'retry_delay' => 0,
    ],

];