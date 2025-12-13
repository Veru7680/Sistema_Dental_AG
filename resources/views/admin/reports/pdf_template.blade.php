<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Citas Odontológicas - Consultorio AG</title>
    <style>
        @page {
            margin: 10px 8px;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 8px;
            line-height: 1.2;
            color: #000000;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }
        
        /* Encabezado simplificado */
        .header {
            text-align: center;
            padding: 8px 0;
            margin-bottom: 12px;
            border-bottom: 2px solid #000000;
        }
        
        .clinic-name {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            color: #000000;
        }
        
        .clinic-subtitle {
            font-size: 10px;
            margin: 2px 0;
            color: #333333;
        }
        
        .report-title {
            font-size: 12px;
            font-weight: bold;
            margin: 5px 0;
            color: #000000;
            text-transform: uppercase;
        }
        
        .report-info {
            font-size: 8px;
            margin: 3px 0;
            color: #555555;
        }
        
        /* Sección de información */
        .info-section {
            background: #f5f5f5;
            padding: 8px;
            margin-bottom: 12px;
            border: 1px solid #dddddd;
        }
        
        .section-title {
            font-size: 10px;
            font-weight: bold;
            margin: 0 0 6px 0;
            color: #000000;
            border-bottom: 1px solid #cccccc;
            padding-bottom: 2px;
        }
        
        .stats-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .stat-item {
            flex: 1;
            margin: 0 4px;
        }
        
        .stat-label {
            font-size: 8px;
            color: #666666;
            margin-bottom: 2px;
        }
        
        .stat-value {
            font-size: 16px;
            font-weight: bold;
            color: #000000;
        }
        
        .filters-list {
            margin-top: 6px;
        }
        
        .filter-row {
            display: flex;
            margin-bottom: 3px;
            font-size: 8px;
        }
        
        .filter-name {
            font-weight: bold;
            color: #000000;
            min-width: 80px;
        }
        
        .filter-value {
            color: #333333;
        }
        
        /* Tabla */
        .table-container {
            margin-bottom: 15px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7px;
        }
        
        th {
            background-color: #f0f0f0;
            border: 1px solid #cccccc;
            padding: 4px 3px;
            text-align: left;
            font-weight: bold;
            color: #000000;
        }
        
        td {
            border: 1px solid #cccccc;
            padding: 4px 3px;
            vertical-align: top;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .patient-cell {
            font-weight: bold;
            color: #000000;
        }
        
        .date-cell {
            text-align: center;
        }
        
        /* Pie de página */
        .footer {
            text-align: center;
            font-size: 7px;
            color: #666666;
            padding-top: 8px;
            border-top: 1px solid #cccccc;
            margin-top: 10px;
        }
        
        /* Sin datos */
        .no-data {
            text-align: center;
            padding: 20px;
            color: #666666;
            font-size: 9px;
        }
    </style>
</head>
<body>

<!-- Encabezado -->
<div class="header">
    <h1 class="clinic-name">CONSULTORIO ODONTOLÓGICO "AG"</h1>
    <p class="clinic-subtitle">Excelencia en cuidado dental</p>
    <h2 class="report-title">REPORTE DE CITAS ODONTOLÓGICAS</h2>
    <p class="report-info">Generado el: {{ $export_date ?? now()->format('d/m/Y H:i:s') }}</p>
    <p class="report-info">Generado por: {{ $requested_by ?? auth()->user()->name ?? 'Administrador del Sistema' }}</p>
</div>

<!-- Información del reporte -->
<div class="info-section">
    <h3 class="section-title">INFORMACIÓN DEL REPORTE</h3>
    
    <div class="stats-container">
        <div class="stat-item">
            <div class="stat-label">Total de Consultas</div>
            <div class="stat-value">{{ $total ?? 0 }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Período del Reporte</div>
            <div class="stat-value">Diciembre 2025</div>
        </div>
    </div>
    
    @if(!empty($filters) && count($filters) > 0)
    <div class="filters-list">
        <div class="filter-row">
            <div class="filter-name">Filtros aplicados:</div>
        </div>
        @foreach($filters as $key => $value)
        <div class="filter-row">
            <div class="filter-name">{{ $key }}:</div>
            <div class="filter-value">{{ $value }}</div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<!-- Tabla de consultas -->
@if(isset($consultations) && $consultations->count() > 0)
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="20%">Paciente</th>
                <th width="20%">Doctor</th>
                <th width="15%">Fecha Cita</th>
                <th width="20%">Diagnóstico</th>
                <th width="20%">Tratamiento</th>
            </tr>
        </thead>
        <tbody>
            @foreach($consultations as $consulta)
            <tr>
                <td class="date-cell"><strong>#{{ $consulta->id ?? 'N/A' }}</strong></td>
                <td class="patient-cell">
                    {{ $consulta->appointment->patient->user->name ?? 'N/A' }}<br>
                    <small>Cita #{{ $consulta->appointment_id ?? 'N/A' }}</small>
                </td>
                <td>
                    {{ $consulta->appointment->doctor->user->name ?? 'N/A' }}<br>
                    <small>{{ $consulta->appointment->doctor->specialty ?? 'Odontólogo General' }}</small>
                </td>
                <td class="date-cell">
                    @if($consulta->appointment && $consulta->appointment->date)
                        {{ \Carbon\Carbon::parse($consulta->appointment->date)->format('d/m/Y') }}<br>
                        <small>{{ $consulta->appointment->start_time ?? 'Sin hora' }}</small>
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $consulta->diagnosis ?? 'Sin diagnóstico' }}</td>
                <td>{{ $consulta->treatment ?? 'Sin tratamiento' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Resumen -->
<div class="info-section">
    <h3 class="section-title">RESUMEN</h3>
    <p style="font-size: 8px; margin: 0;">
        Se encontraron <strong>{{ $consultations->count() }}</strong> consultas odontológicas en el período seleccionado.
        Reporte generado con fines administrativos y de control clínico.
    </p>
</div>

@else
<div class="no-data">
    <h3 style="margin: 0 0 10px 0;">No se encontraron consultas</h3>
    <p style="margin: 0;">
        No hay datos disponibles con los filtros seleccionados.<br>
        Intenta cambiar los criterios de búsqueda o selecciona un período diferente.
    </p>
</div>
@endif

<!-- Pie de página -->
<div class="footer">
    <p><strong>© {{ date('Y') }} Consultorio Odontológico "AG"</strong></p>
    <p>Documento generado automáticamente • Página 1 de 1 • Confidencial</p>
</div>

</body>
</html>