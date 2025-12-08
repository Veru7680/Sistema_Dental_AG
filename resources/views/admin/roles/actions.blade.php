<div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50">
    <!-- Icono según rol -->
    <div class="text-xl">
        @if($role->id == 1)
            👑 <!-- Corona para Admin -->
        @elseif($role->id == 2)
            🏥 <!-- Hospital para Paciente -->
        @elseif($role->id == 3)
            🩺 <!-- Estetoscopio para Doctor -->
        @elseif($role->id == 4)
            💼 <!-- Maletín para Recepcionista -->
        @else
            ⚙️ <!-- Engranaje para personalizado -->
        @endif
    </div>
    
    <!-- Información del rol -->
    <div class="flex-1">
        <div class="flex items-center space-x-2">
            <span class="font-semibold text-gray-800">{{ $role->name }}</span>
            @if($role->id <= 4)
                <span class="text-xs px-2 py-0.5 bg-amber-100 text-amber-800 rounded-full">
                    🛡️ Sistema
                </span>
            @endif
        </div>
        <p class="text-sm text-gray-600 mt-1">
            @if($role->id == 1)
                Acceso total y control completo del sistema
            @elseif($role->id == 2)
                Usuario que solicita y recibe servicios médicos
            @elseif($role->id == 3)
                Personal médico con funciones clínicas específicas
            @elseif($role->id == 4)
                Personal administrativo con acceso restringido
            @else
                Rol configurado manualmente según necesidades
            @endif
        </p>
    </div>
</div>