<x-admin-layout
    title="Copias de Seguridad | Dental AG"
    
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('dashboard'),
        ],
        [
            'name' => 'Copias de Seguridad',
        ]
    ]"
>

    <style>
        .backup-file {
            border-left: 4px solid #3b82f6;
            padding-left: 15px;
            margin-bottom: 15px;
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }
        
        .dark .backup-file {
            background-color: #1f2937;
            border-left-color: #60a5fa;
        }
        
        .backup-file:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .backup-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            flex-wrap: wrap;
        }
        
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 1060;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }
        
        .loading-overlay.active {
            display: flex;
        }
        
        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 4px solid #3b82f6;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <div class="space-y-6">
        <!-- ========== ENCABEZADO ========== -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-database mr-2 text-blue-500"></i>
                    Copias de Seguridad
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    Gestiona y administra las copias de seguridad del sistema
                </p>
            </div>
            
            <div>
                <!-- FORMULARIO SIMPLE PARA CREAR BACKUP -->
                <form id="createBackupForm" action="{{ route('admin.backups.create') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                
                <button 
                    type="button" 
                    class="inline-flex items-center px-5 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition shadow-lg"
                    id="createBackupBtn"
                >
                    <i class="fas fa-plus-circle mr-3 text-lg"></i>
                    Crear Nuevo Backup
                </button>
            </div>
        </div>

        <!-- Alertas -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 dark:bg-green-900/20 dark:border-green-800">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-300">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 dark:bg-red-900/20 dark:border-red-800">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800 dark:text-red-300">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Lista de Backups -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                        <i class="fas fa-file-archive mr-2 text-blue-500"></i>
                        Backups Disponibles
                    </h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ count($backups ?? []) }} encontrados
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if(isset($backups) && count($backups) > 0)
                    <div class="space-y-4">
                        @foreach($backups as $backup)
                            @php
                                $backupType = $backup->type ?? 'completo';
                                $typeIcon = match($backupType) {
                                    'base de datos' => 'fa-database',
                                    'archivos' => 'fa-folder',
                                    default => 'fa-server'
                                };
                            @endphp
                            
                            <div class="backup-file">
                                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <!-- Header con nombre y tipo -->
                                        <div class="flex items-center mb-3 flex-wrap gap-2">
                                            <i class="fas {{ $typeIcon }} text-blue-500 text-lg"></i>
                                            <span class="font-bold text-gray-900 dark:text-white text-lg">
                                                {{ $backup->id ?? 'Sin nombre' }}
                                            </span>
                                            
                                            <!-- Tipo de backup -->
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                                @if($backupType == 'completo') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                                @elseif($backupType == 'base de datos') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                                @else bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                                @endif">
                                                <i class="fas {{ $typeIcon }} mr-1 text-xs"></i>
                                                {{ $backupType }}
                                            </span>
                                        </div>
                                        
                                        <!-- Información del archivo -->
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-3">
                                            <div class="flex items-center">
                                                <i class="fas fa-weight-hanging text-gray-400 mr-2"></i>
                                                <div>
                                                    <div class="text-sm text-gray-500">Tamaño:</div>
                                                    <div class="font-medium">{{ $backup->size_in_mb ?? '0' }} MB</div>
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center">
                                                <i class="far fa-calendar-alt text-gray-400 mr-2"></i>
                                                <div>
                                                    <div class="text-sm text-gray-500">Fecha:</div>
                                                    <div class="font-medium">
                                                        @if(isset($backup->created_at))
                                                            {{ \Carbon\Carbon::parse($backup->created_at)->format('d/m/Y H:i:s') }}
                                                        @else
                                                            Desconocida
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center">
                                                <i class="fas fa-clock text-gray-400 mr-2"></i>
                                                <div>
                                                    <div class="text-sm text-gray-500">Hace:</div>
                                                    <div class="font-medium">
                                                        @if(isset($backup->created_at))
                                                            {{ \Carbon\Carbon::parse($backup->created_at)->diffForHumans() }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- BOTONES DE ACCIÓN -->
                                    <div class="backup-actions mt-4 md:mt-0">
                                        <!-- Botón Descargar -->
                                        <a href="{{ route('admin.backups.download', $backup->id) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition"
                                           title="Descargar este backup">
                                            <i class="fas fa-download mr-2"></i>
                                            Descargar
                                        </a>
                                        
                                        <!-- Botón Restaurar -->
                                        <button 
                                            type="button"
                                            class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:border-emerald-900 focus:ring focus:ring-emerald-300 disabled:opacity-25 transition restore-btn"
                                            data-id="{{ $backup->id }}"
                                            title="Restaurar este backup">
                                            <i class="fas fa-history mr-2"></i>
                                            Restaurar
                                        </button>
                                        
                                        <!-- Botón Eliminar -->
                                        <button 
                                            type="button"
                                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 disabled:opacity-25 transition delete-btn"
                                            data-id="{{ $backup->id }}"
                                            title="Eliminar este backup">
                                            <i class="fas fa-trash-alt mr-2"></i>
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Estado vacío -->
                    <div class="text-center py-12">
                        <div class="mb-6">
                            <i class="fas fa-database text-6xl text-gray-300 dark:text-gray-600"></i>
                        </div>
                        <h3 class="text-xl font-medium text-gray-700 dark:text-gray-300 mb-2">
                            No hay copias de seguridad disponibles
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto">
                            Crea tu primera copia de seguridad para proteger los datos importantes del sistema.
                        </p>
                        
                        <button 
                            type="button"
                            id="firstBackupBtn"
                            class="inline-flex items-center px-5 py-2.5 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Crear Primer Backup
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- LOADING OVERLAY -->
    <div id="loadingOverlay" class="loading-overlay">
        <div>
            <div class="spinner"></div>
            <h3 class="text-xl font-medium mb-2">Procesando...</h3>
            <p class="text-gray-300">Por favor, espera. Esto puede tardar varios minutos.</p>
            <p class="text-gray-400 text-sm mt-2">No cierres esta ventana.</p>
        </div>
    </div>

    <script>
    // Función para mostrar loading
    function showLoading() {
        document.getElementById('loadingOverlay').classList.add('active');
    }
    
    // Función para crear backup
    function createBackup() {
        if (confirm('¿Estás seguro de que deseas crear un nuevo backup?\n\nEsta operación puede tardar varios minutos.')) {
            showLoading();
            document.getElementById('createBackupForm').submit();
        }
    }
    
    // Función para restaurar backup
    function restoreBackup(id) {
        if (confirm('¿Estás seguro de restaurar este backup?\n\nEsto reemplazará la base de datos actual.')) {
            showLoading();
            
            // Crear formulario dinámico para restaurar
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ url('admin/backups') }}/" + id + "/restore";
            form.style.display = 'none';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = "{{ csrf_token() }}";
            
            const diskInput = document.createElement('input');
            diskInput.type = 'hidden';
            diskInput.name = 'disk';
            diskInput.value = 'local';
            
            form.appendChild(csrfToken);
            form.appendChild(diskInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    // Función para eliminar backup
    function deleteBackup(id) {
        if (confirm('¿Estás seguro de eliminar este backup?\n\nEsta acción no se puede deshacer.')) {
            showLoading();
            
            // Crear formulario dinámico para eliminar
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ url('admin/backups') }}/" + id;
            form.style.display = 'none';
            
            // Agregar método DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = "{{ csrf_token() }}";
            
            form.appendChild(methodInput);
            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    // Event listeners cuando el DOM esté cargado
    document.addEventListener('DOMContentLoaded', function() {
        // Botón crear backup
        document.getElementById('createBackupBtn')?.addEventListener('click', createBackup);
        document.getElementById('firstBackupBtn')?.addEventListener('click', createBackup);
        
        // Botones restaurar (manejo de eventos delegados)
        document.addEventListener('click', function(e) {
            // Restaurar backup
            if (e.target.classList.contains('restore-btn') || e.target.closest('.restore-btn')) {
                const button = e.target.classList.contains('restore-btn') 
                    ? e.target 
                    : e.target.closest('.restore-btn');
                const id = button.getAttribute('data-id');
                restoreBackup(id);
            }
            
            // Eliminar backup
            if (e.target.classList.contains('delete-btn') || e.target.closest('.delete-btn')) {
                const button = e.target.classList.contains('delete-btn') 
                    ? e.target 
                    : e.target.closest('.delete-btn');
                const id = button.getAttribute('data-id');
                deleteBackup(id);
            }
        });
        
        // Auto cerrar alertas después de 5 segundos
        setTimeout(() => { 
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.display = 'none';
            });
        }, 5000);
    });
    </script>

</x-admin-layout>