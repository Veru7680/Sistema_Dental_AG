<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class BackupController extends Controller
{
    /**
     * Mostrar listado de backups
     */
    public function index(Request $request)
    {
        $disk = 'local';
        $storage = Storage::disk($disk);
        
        $backupFiles = [];
        
        try {
            // El paquete spatie/laravel-backup guarda en storage/app/{app_name}
            $appName = config('backup.backup.name', env('APP_NAME', 'Laravel'));
            $backupPath = $appName;
            
            // Listar todos los archivos en el directorio de backups
            if ($storage->exists($backupPath)) {
                $files = $storage->allFiles($backupPath);
                
                foreach ($files as $file) {
                    // Solo archivos ZIP (backups)
                    if (pathinfo($file, PATHINFO_EXTENSION) === 'zip') {
                        try {
                            $filename = basename($file);
                            $lastModified = $storage->lastModified($file);
                            $size = $storage->size($file);
                            
                            // Extraer fecha del nombre del archivo
                            preg_match('/(\d{4}-\d{2}-\d{2}-\d{2}-\d{2}-\d{2})/', $filename, $matches);
                            $dateFromName = isset($matches[1]) ? Carbon::createFromFormat('Y-m-d-H-i-s', $matches[1]) : null;
                            
                            $backupFiles[] = (object) [
                                'id' => $filename,
                                'path' => $file,
                                'disk' => $disk,
                                'size_in_mb' => round($size / 1024 / 1024, 2),
                                'size_bytes' => $size,
                                'created_at' => $dateFromName ?: Carbon::createFromTimestamp($lastModified),
                                'updated_at' => Carbon::createFromTimestamp($lastModified),
                                'status' => 'ok',
                                'type' => $this->getBackupType($filename),
                            ];
                        } catch (\Exception $e) {
                            continue;
                        }
                    }
                }
            }
            
            // Ordenar por fecha (más reciente primero)
            usort($backupFiles, function($a, $b) {
                return $b->created_at->timestamp <=> $a->created_at->timestamp;
            });
            
        } catch (\Exception $e) {
            Log::warning("Error al listar backups: " . $e->getMessage());
        }
        
        // Último backup
        $lastBackup = !empty($backupFiles) ? $backupFiles[0] : null;
        
        return view('admin.backups.index', [
            'backups' => $backupFiles,
            'lastBackup' => $lastBackup,
        ]);
    }
    
    /**
     * Determinar tipo de backup por nombre
     */
    private function getBackupType($filename)
    {
        if (strpos($filename, 'database') !== false) {
            return 'base de datos';
        } elseif (strpos($filename, 'files') !== false) {
            return 'archivos';
        } else {
            return 'completo';
        }
    }

    /**
     * Crear nuevo backup (para formulario tradicional)
     */
    public function create(Request $request)
    {
        try {
            $this->setMysqldumpPath();
            
            $onlyDatabase = $request->has('only_database');
            $type = $onlyDatabase ? 'solo base de datos' : 'completo';
            
            Log::info("Backup manual solicitado por usuario " . auth()->id() . " - Tipo: {$type}");
            
            // Ejecutar backup
            if ($onlyDatabase) {
                Artisan::call('backup:run', ['--only-db' => true]);
            } else {
                Artisan::call('backup:run');
            }
            
            $output = Artisan::output();
            
            Log::info("Backup {$type} creado exitosamente");
            
            return redirect()
                ->route('admin.backups.index') // ← CORREGIDO: usa 'backups.index' NO 'admin.backups.index'
                ->with('success', "Backup {$type} creado correctamente.")
                ->with('logs', $output);
                
        } catch (\Exception $e) {
            Log::error("Error al crear backup: " . $e->getMessage());
            
            return redirect()
                ->route('admin.backups.index') // ← CORREGIDO
                ->with('error', 'Error al crear backup: ' . $e->getMessage());
        }
    }

    /**
     * Crear backup por AJAX
     */
    
     public function run()
    {
        try {
            $this->setMysqldumpPath();
            
            Artisan::call('backup:run');
            $output = Artisan::output();
            
            Log::info("Backup ejecutado por AJAX por usuario " . auth()->id());
            
            return response()->json([
                'success' => true,
                'message' => 'Backup creado correctamente'
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error al ejecutar backup: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al crear backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Configurar PATH para mysqldump
     */
   /**
 * Configurar PATH para mysqldump
 */
// Añade este método en tu BackupController actual, justo después del índice:

/**
 * Configurar PATH para mysqldump en Windows
 */
private function setMysqldumpPath()
{
    try {
        // Para Windows con XAMPP
        $mysqlDumpPath = 'C:\xampp\mysql\bin';
        
        if (is_dir($mysqlDumpPath)) {
            // Configurar para Laravel Backup
            config([
                'database.connections.mysql.dump.dump_binary_path' => $mysqlDumpPath,
            ]);
            
            // También configurar PATH de sistema para Windows
            $currentPath = getenv('PATH') ?: '';
            if (strpos($currentPath, $mysqlDumpPath) === false) {
                putenv('PATH=' . $mysqlDumpPath . ';' . $currentPath);
            }
            
            Log::info("mysqldump path configurado: {$mysqlDumpPath}");
            return true;
        }
        
        Log::warning("Directorio mysqldump no encontrado: {$mysqlDumpPath}");
        return false;
        
    } catch (\Exception $e) {
        Log::error("Error configurando mysqldump path: " . $e->getMessage());
        return false;
    }
}
    public function restore($id, Request $request)
    {
        $disk = 'local';
        $logName = 'restore-' . date('Ymd-His') . '.log';
        $logPath = storage_path('logs/' . $logName);
        
        // Crear archivo de log
        file_put_contents($logPath, "=== RESTORE STARTED ===\n");
        file_put_contents($logPath, "[" . now() . "] Iniciando restauración de: {$id}\n", FILE_APPEND);
        file_put_contents($logPath, "[" . now() . "] Disco: {$disk}\n", FILE_APPEND);
        file_put_contents($logPath, "[" . now() . "] Usuario: " . auth()->id() . "\n", FILE_APPEND);
        
        try {
            // 1. Crear backup actual antes de restaurar
            file_put_contents($logPath, "[" . now() . "] Creando backup previo...\n", FILE_APPEND);
            
            try {
                $this->setMysqldumpPath();
                Artisan::call('backup:run', ['--only-db' => true]);
                file_put_contents($logPath, "[" . now() . "] Backup previo creado\n", FILE_APPEND);
            } catch (\Exception $e) {
                file_put_contents($logPath, "[" . now() . "] Advertencia: " . $e->getMessage() . "\n", FILE_APPEND);
            }
            
            // 2. Instrucciones para restaurar manualmente
            file_put_contents($logPath, "[" . now() . "] ===== INSTRUCCIONES =====\n", FILE_APPEND);
            file_put_contents($logPath, "[" . now() . "] Para restaurar manualmente:\n", FILE_APPEND);
            file_put_contents($logPath, "[" . now() . "] 1. Descarga el backup: {$id}\n", FILE_APPEND);
            file_put_contents($logPath, "[" . now() . "] 2. Extrae el archivo ZIP\n", FILE_APPEND);
            file_put_contents($logPath, "[" . now() . "] 3. Busca database.sql dentro\n", FILE_APPEND);
            file_put_contents($logPath, "[" . now() . "] 4. Importa a MySQL:\n", FILE_APPEND);
            file_put_contents($logPath, "[" . now() . "]    mysql -u root -p " . env('DB_DATABASE') . " < database.sql\n", FILE_APPEND);
            file_put_contents($logPath, "[" . now() . "] ============================\n", FILE_APPEND);
            
            file_put_contents($logPath, "[" . now() . "] ✅ RESTAURACIÓN PREPARADA - SIGUE LAS INSTRUCCIONES\n", FILE_APPEND);
            Log::info("Restauración preparada para: {$id}");
            
            return response()->json([
                'success' => true,
                'message' => 'Preparado para restaurar. Descarga el backup y sigue las instrucciones.',
                'log' => $logName,
                'instructions' => [
                    '1. Descarga el backup desde el botón de descarga',
                    '2. Extrae el archivo ZIP en tu computadora',
                    '3. Busca el archivo database.sql dentro',
                    '4. Importa a MySQL con: mysql -u ' . env('DB_USERNAME') . ' -p ' . env('DB_DATABASE') . ' < database.sql'
                ]
            ]);
            
        } catch (\Exception $e) {
            file_put_contents($logPath, "[" . now() . "] ❌ ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
            Log::error("Error en restauración: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'log' => $logName,
            ], 500);
        }
    }

    /**
     * Ver log de restauración
     */
    public function restoreLog(Request $request)
    {
        try {
            $file = $request->get('file');
            
            if (!$file) {
                return response()->json([
                    'error' => 'No se especificó archivo de log',
                    'type' => 'no_file'
                ], 400);
            }
            
            // Validar nombre seguro
            if (!preg_match('/^restore-[0-9]{8}-[0-9]{6}\.log$/', $file)) {
                return response()->json([
                    'error' => 'Nombre de archivo inválido',
                    'type' => 'invalid_filename'
                ], 400);
            }
            
            $path = storage_path('logs/' . $file);
            
            if (!file_exists($path)) {
                return response()->json([
                    'error' => 'Log no encontrado',
                    'type' => 'not_found'
                ], 404);
            }
            
            $content = file_get_contents($path);
            
            return response()->json([
                'content' => $content,
                'completed' => true,
                'file_size' => filesize($path),
                'last_modified' => date('Y-m-d H:i:s', filemtime($path))
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en restoreLog: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error interno',
                'type' => 'internal_error'
            ], 500);
        }
    }

    /**
     * Eliminar backup
     */
    public function destroy($id, Request $request)
    {
        $disk = 'local';
        $storage = Storage::disk($disk);
        
        // Buscar el archivo
        $appName = config('backup.backup.name', env('APP_NAME', 'Laravel'));
        $backupPath = $appName;
        
        if ($storage->exists($backupPath)) {
            $files = $storage->allFiles($backupPath);
            
            foreach ($files as $file) {
                if (basename($file) === $id) {
                    $storage->delete($file);
                    
                    Log::info("Backup eliminado: {$id} por usuario " . auth()->id());
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Backup eliminado correctamente'
                    ]);
                }
            }
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Backup no encontrado'
        ], 404);
    }

    /**
     * Limpiar backups antiguos
     */
    public function clean()
    {
        try {
            $this->setMysqldumpPath();
            Artisan::call('backup:clean');
            
            Log::info("Limpieza de backups ejecutada por usuario " . auth()->id());
            
            return response()->json([
                'success' => true,
                'message' => 'Limpieza de backups ejecutada'
            ]);
        } catch (\Exception $e) {
            Log::error("Error al limpiar backups: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}