<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Consultas - Sistema Dental AG</title>
    <style>
        @page {
            margin: 15px 10px;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            line-height: 1.2;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #e53e3e;
            padding-bottom: 10px;
        }
        
        .title {
            color: #e53e3e;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .subtitle {
            color: #666;
            font-size: 11px;
            margin-bottom: 3px;
        }
        
        .info-box {
            background: #f7fafc;
            padding: 8px;
            border-radius: 3px;
            margin-bottom: 10px;
            border-left: 2px solid #4299e1;
            font-size: 8px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 8px;
        }
        
        .table th {
            background: #e53e3e;
            color: white;
            padding: 6px 5px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
            font-size: 8px;
        }
        
        .table td {
            padding: 5px 6px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
            font-size: 8px;
        }
        
        .table tr:nth-child(even) {
            background: #f9fafb;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 8px;
            color: #718096;
        }
        
        .no-data {
            text-align: center;
            padding: 20px;
            color: #718096;
            font-style: italic;
            font-size: 9px;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .mt-2 { margin-top: 10px; }
        .mb-2 { margin-bottom: 10px; }
        
        /* Nuevas clases para texto pequeño */
        .text-xs { font-size: 7px; }
        .text-sm { font-size: 8px; }
        .text-base { font-size: 9px; }
        
        /* Para texto dentro de celdas pequeñas */
        small, .small {
            font-size: 7px !important;
            color: #666;
        }
        
        /* Para que las celdas sean más compactas */
        .compact-cell {
            padding: 3px 4px !important;
        }
        
        /* Para texto truncado */
        .truncate {
            max-width: 150px; /* Aumentado porque hay más espacio ahora */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
        }
        
        /* Para diagnósticos y tratamientos más largos */
        .truncate-long {
            max-width: 180px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
        }
    </style>
</head>
<body>

<div class="header">
    <h1 class="title">Sistema Dental AG</h1>
    <h2 class="subtitle">Reporte de Consultas Médicas</h2>
    <p class="text-sm">Generado el: {{ $export_date ?? now()->format('d/m/Y H:i:s') }}</p>
    <p class="text-sm">Generado por: {{ $requested_by ?? auth()->user()->name ?? 'Usuario' }}</p>
</div>

<!-- Información del reporte -->
<div class="info-box">
    <h3 class="text-base font-bold">📊 Información del Reporte</h3>
    <p class="text-sm"><strong>Total de consultas:</strong> {{ $total ?? 0 }}</p>
    
    @if(!empty($filters) && count($filters) > 0)
        <p class="text-sm font-bold mb-1">Filtros aplicados:</p>
        <ul style="margin-left: 15px; margin-top: 2px; margin-bottom: 2px;" class="text-xs">
            @foreach($filters as $key => $value)
                <li><strong>{{ $key }}:</strong> {{ $value }}</li>
            @endforeach
        </ul>
    @endif
    
    @if(isset($mensaje_error))
        <p class="text-xs" style="color: #e53e3e; font-weight: bold;">⚠️ {{ $mensaje_error }}</p>
    @endif
</div>

<!-- Tabla de consultas -->
@if(isset($consultations) && $consultations->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th width="4%" class="compact-cell">ID</th>
                <th width="20%" class="compact-cell">Paciente</th>
                <th width="20%" class="compact-cell">Doctor</th>
                <th width="12%" class="compact-cell">Fecha Cita</th>
                <th width="22%" class="compact-cell">Diagnóstico</th>
                <th width="22%" class="compact-cell">Tratamiento</th>
            </tr>
        </thead>
        <tbody>
            @foreach($consultations as $consulta)
            <tr>
                <td class="text-center compact-cell">#{{ $consulta->id ?? 'N/A' }}</td>
                <td class="compact-cell">
                    <div class="font-bold text-sm">{{ $consulta->appointment->patient->user->name ?? 'N/A' }}</div>
                    <small>Cita #{{ $consulta->appointment_id ?? 'N/A' }}</small>
                </td>
                <td class="compact-cell">
                    <div class="text-sm">{{ $consulta->appointment->doctor->user->name ?? 'N/A' }}</div>
                    <small>{{ $consulta->appointment->doctor->specialty ?? 'Especialista' }}</small>
                </td>
                <td class="compact-cell">
                    @if($consulta->appointment && $consulta->appointment->date)
                        <div class="text-sm">{{ \Carbon\Carbon::parse($consulta->appointment->date)->format('d/m/Y') }}</div>
                        <small>{{ $consulta->appointment->start_time ?? 'Sin hora' }}</small>
                    @else
                        <span class="text-xs">N/A</span>
                    @endif
                </td>
                <td class="compact-cell">
                    <div class="truncate-long" title="{{ $consulta->diagnosis ?? '' }}">
                        {{ $consulta->diagnosis ?? 'Sin diagnóstico' }}
                    </div>
                </td>
                <td class="compact-cell">
                    <div class="truncate-long" title="{{ $consulta->treatment ?? '' }}">
                        {{ $consulta->treatment ?? 'Sin tratamiento' }}
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Resumen final -->
    <div class="mt-2">
        <p class="text-sm"><strong>Resumen:</strong> Se encontraron {{ $consultations->count() }} consultas en el reporte.</p>
    </div>
    
@else
    <div class="no-data">
        <h3 class="text-base font-bold">📭 No se encontraron consultas</h3>
        <p class="text-sm">No hay datos disponibles con los filtros seleccionados.</p>
        <p class="text-xs">Intenta cambiar los criterios de búsqueda.</p>
    </div>
@endif

<!-- Pie de página -->
<div class="footer">
    <p class="text-xs">© {{ date('Y') }} Sistema Dental AG - Todos los derechos reservados</p>
    <p class="text-xs">Este es un documento generado automáticamente - Página 1 de 1</p>
</div>

</body>
</html>