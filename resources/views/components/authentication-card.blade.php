<div class="min-h-screen flex flex-col justify-center items-center">
    
    <!-- Logo -->
    <div class="mb-3">
        <img src="{{ asset('img/bonito.png') }}" alt="Logo" class="w-56 mx-auto">
    </div>

    <!-- Card login -->
    <div class="w-full max-w-sm mx-4 px-4 py-4 bg-transparent border-2 border-blue-500 shadow-md rounded-lg backdrop-blur-sm">
        {{ $slot }}
    </div>

</div>