<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Sistema de Gestión del Consultorio Odontológico AG - Salud dental de calidad con tecnología avanzada">

        <title>Consultorio Odt. AG</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /* Estilos base de Tailwind */
                /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */
                @layer theme {:root,:host{--font-sans:'Instrument Sans',ui-sans-serif,system-ui,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";--color-blue-50:#eff6ff;--color-blue-100:#dbeafe;--color-blue-200:#bfdbfe;--color-blue-300:#93c5fd;--color-blue-400:#60a5fa;--color-blue-500:#3b82f6;--color-blue-600:#2563eb;--color-blue-700:#1d4ed8;--color-blue-800:#1e40af;--color-blue-900:#1e3a8a;--color-purple-50:#faf5ff;--color-purple-100:#f3e8ff;--color-purple-200:#e9d5ff;--color-purple-300:#d8b4fe;--color-purple-400:#c084fc;--color-purple-500:#a855f7;--color-purple-600:#9333ea;--color-purple-700:#7e22ce;--color-purple-800:#6b21a8;--color-purple-900:#581c87;--color-teal-100:#d1fae5;--color-teal-200:#a7f3d0;--color-teal-300:#6ee7b7;--color-teal-400:#34d399;--color-teal-500:#10b981;--color-teal-600:#059669;--color-teal-700:#047857;--color-teal-800:#065f46;--color-teal-900:#064e3b;--color-cyan-50:#ecfeff;--color-cyan-100:#cffafe;--color-cyan-200:#a5f3fc;--color-cyan-300:#67e8f9;--color-cyan-400:#22d3ee;--color-cyan-500:#06b6d4;--color-cyan-600:#0891b2;--color-cyan-700:#0e7490;--color-cyan-800:#155e75;--color-cyan-900:#164e63;--color-white:#ffffff;--color-gray-50:#f9fafb;--color-gray-100:#f3f4f6;--color-gray-200:#e5e7eb;--color-gray-300:#d1d5db;--color-gray-400:#9ca3af;--color-gray-500:#6b7280;--color-gray-600:#4b5563;--color-gray-700:#374151;--color-gray-800:#1f2937;--color-gray-900:#111827;--color-zinc-900:#18181b;--color-emerald-100:#d1fae5;--color-emerald-200:#a7f3d0;--color-emerald-300:#6ee7b7;--color-emerald-400:#34d399;--color-emerald-500:#10b981;--color-emerald-600:#059669;--color-emerald-700:#047857;--color-emerald-800:#065f46;--color-emerald-900:#064e3b;--spacing:.25rem;--radius-lg:.5rem;--radius-xl:.75rem;--radius-2xl:1rem;--shadow-lg:0 10px 15px -3px rgba(0,0,0,0.1),0 4px 6px -4px rgba(0,0,0,0.1);--shadow-xl:0 20px 25px -5px rgba(0,0,0,0.1),0 8px 10px -6px rgba(0,0,0,0.1);--text-lg:1.125rem;--text-xl:1.25rem;--text-2xl:1.5rem;--text-3xl:1.875rem;--text-4xl:2.25rem;--font-weight-medium:500;--font-weight-semibold:600;--font-weight-bold:700;--default-transition-duration:.15s;--default-transition-timing-function:cubic-bezier(.4,0,.2,1)}}
                *,:after,:before{box-sizing:border-box;border:0 solid;margin:0;padding:0}
                html,:host{-webkit-text-size-adjust:100%;line-height:1.5;font-family:var(--font-sans);-webkit-tap-highlight-color:transparent}
                body{line-height:inherit}
                .bg-gradient-blue{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%)}
                .bg-gradient-teal{background:linear-gradient(135deg,#6ee7b7 0%,#3b82f6 100%)}
                .bg-gradient-purple{background:linear-gradient(135deg,#a855f7 0%,#9333ea 100%)}
                .bg-gradient-soft{background:linear-gradient(135deg,#a8edea 0%,#fed6e3 100%)}
                .bg-gradient-professional{background:linear-gradient(135deg,#e0f2fe 0%,#fef3c7 100%)}
                .animate-float{animation:float 3s ease-in-out infinite}
                @keyframes float{0%,100%{transform:translateY(0px)}50%{transform:translateY(-10px)}}
            </style>
        @endif

        <style>
            .hero-gradient {
                background: linear-gradient(135deg, rgba(59, 130, 246, 0.9) 0%, rgba(16, 185, 129, 0.9) 100%);
            }
            .card-hover {
                transition: all 0.3s ease;
            }
            .card-hover:hover {
                transform: translateY(-5px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            }
            .section-pattern {
                background-image: radial-gradient(#3b82f6 1px, transparent 1px);
                background-size: 20px 20px;
                background-color: #f8fafc;
            }
            .why-choose-us-bg {
                background: linear-gradient(135deg, #f3e8ff 0%, #e0f2fe 100%);
                border-radius: 2rem;
                border: 1px solid rgba(168, 85, 247, 0.1);
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            }
        </style>
    </head>
    <body class="bg-gray-50 text-gray-800">
        <!-- Header/Navigation -->
        <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm shadow-sm">
            <div class="container mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <!-- Logo clini.png -->
                        <div class="w-12 h-12 rounded-xl overflow-hidden shadow-md">
                            <img src="{{ asset('img/clini.png') }}" alt="Consultorio Odontológico AG" 
                                 class="w-full h-full object-cover" 
                                 onerror="this.onerror=null; this.src='https://img.icons8.com/color/96/000000/dental-braces.png';">
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">Consultorio Odt. AG</h1>
                            <p class="text-sm text-gray-600">Dra. Adela García Chacón</p>
                        </div>
                    </div>
                    
                    <nav class="hidden md:flex items-center space-x-8">
                        <a href="#inicio" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Inicio</a>
                        <a href="#servicios" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Servicios</a>
                        <a href="#nosotros" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Nosotros</a>
                        <a href="#contacto" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Contacto</a>
                    </nav>
                    
                    <div class="flex items-center space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" 
                                   class="px-6 py-2 bg-gradient-teal text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
                                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="px-5 py-2 text-blue-600 hover:text-blue-800 font-medium transition-colors">
                                    Iniciar Sesión
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" 
                                        class="px-6 py-2 bg-gradient-to-r from-purple-500 to-purple-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all hover:from-purple-600 hover:to-purple-700">
                                        <i class="fas fa-user-plus mr-2"></i>Registrarse
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <!-- Services Section -->
        <section id="servicios" class="py-20 bg-white">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Nuestros <span class="text-blue-600">Servicios</span>
                    </h2>
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                        Ofrecemos una gama completa de servicios odontológicos con la más alta tecnología
                    </p>
                </div>
                
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="card-hover bg-gradient-soft rounded-2xl p-8 shadow-lg">
                        <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center mb-6 shadow-md">
                            <i class="fas fa-smile text-2xl text-blue-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Ortodoncia y Estética</h3>
                        <p class="text-gray-700 mb-6">
                            Sonrisas perfectas con tratamientos de ortodoncia avanzada y diseño de sonrisa digital.
                        </p>
                        <ul class="space-y-2">
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Brackets estéticos
                            </li>
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Blanqueamiento dental
                            </li>
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Carillas dentales
                            </li>
                        </ul>
                    </div>
                    
                    <div class="card-hover bg-gradient-professional rounded-2xl p-8 shadow-lg">
                        <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center mb-6 shadow-md">
                            <i class="fas fa-teeth text-2xl text-teal-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Rehabilitación Oral</h3>
                        <p class="text-gray-700 mb-6">
                            Restauramos la función y estética dental con prótesis e implantes de última generación.
                        </p>
                        <ul class="space-y-2">
                            <li class="flex items-center text-gray-600">
                                 <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Implantes dentales
                            </li>
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Prótesis fijas
                            </li>
                            <li class="flex items-center text-gray-600">
                                 <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Coronas y puentes
                            </li>
                        </ul>
                    </div>
                    
                    <div class="card-hover bg-gradient-to-r from-blue-600 to-purple-700 rounded-2xl p-8 shadow-lg">
                        <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center mb-6 shadow-md backdrop-blur-sm">
                        <i class="fas fa-hand-holding-medical text-2xl text-purple-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Odontología General</h3>
                            <p class="text-gray-100 mb-6">
                                Cuidado dental integral para toda la familia con tecnología de vanguardia.
                            </p>
                        <ul class="space-y-2">
                            <li class="flex items-center text-gray-100">
                                 <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Limpieza dental
                            </li>
                            <li class="flex items-center text-gray-100">
                                 <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Tratamiento de caries
                            </li>
                            <li class="flex items-center text-gray-100">
                                 <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Extracciones
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Us Section -->
        <section id="nosotros" class="py-20 section-pattern">
            <div class="container mx-auto px-6">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                            <span class="text-blue-600">Dra. Adela García Chacón</span><br>
                            Nuestra Filosofía
                        </h2>
                        
                        <div class="space-y-8">
                            <div class="bg-white rounded-xl p-6 shadow-lg border-l-4 border-blue-500">
                                <h3 class="text-xl font-bold text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-bullseye text-blue-500 mr-3"></i>
                                    Misión
                                </h3>
                                <p class="text-gray-700">
                                    Proveer servicios odontológicos de excelencia mediante tecnología avanzada, 
                                    personal calificado y atención humana personalizada, contribuyendo a mejorar 
                                    la salud bucal y calidad de vida de nuestros pacientes.
                                </p>
                            </div>
                            
                            <div class="bg-white rounded-xl p-6 shadow-lg border-l-4 border-teal-500">
                                <h3 class="text-xl font-bold text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-eye text-teal-500 mr-3"></i>
                                    Visión
                                </h3>
                                <p class="text-gray-700">
                                    Ser el consultorio odontológico de referencia en Bolivia, reconocido por 
                                    nuestra innovación tecnológica, excelencia clínica y compromiso con la 
                                    salud dental integral de cada paciente.
                                </p>
                            </div>
                            
                            <div class="bg-white rounded-xl p-6 shadow-lg border-l-4 border-emerald-500">
                                <h3 class="text-xl font-bold text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-star text-emerald-500 mr-3"></i>
                                    Valores
                                </h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-heart text-red-400 mr-2"></i>
                                        <span class="text-gray-700">Empatía</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-shield-alt text-blue-400 mr-2"></i>
                                        <span class="text-gray-700">Seguridad</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-gem text-purple-400 mr-2"></i>
                                        <span class="text-gray-700">Excelencia</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-users text-green-400 mr-2"></i>
                                        <span class="text-gray-700">Trabajo en equipo</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sección "Por qué elegirnos" con nuevo fondo -->
                    <div class="relative">
                        <div class="why-choose-us-bg p-8 text-gray-800">
                            <h3 class="text-2xl font-bold mb-8 text-center text-purple-700">
                                <i class="fas fa-star text-yellow-500 mr-2"></i>
                                Por qué elegirnos
                            </h3>
                            <div class="space-y-8">
                                <div class="flex items-start">
                                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-100 to-orange-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0 shadow-md">
                                        <i class="fas fa-award text-xl text-yellow-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-lg mb-1 text-gray-900">15+ Años de Experiencia</h4>
                                        <p class="text-gray-700">Trayectoria comprobada en salud dental con miles de pacientes satisfechos.</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="w-14 h-14 bg-gradient-to-br from-green-100 to-emerald-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0 shadow-md">
                                        <i class="fas fa-clock text-xl text-green-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-lg mb-1 text-gray-900">Horarios Flexibles</h4>
                                        <p class="text-gray-700">Adaptados a tu disponibilidad, incluyendo horarios extendidos y citas de emergencia.</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="w-14 h-14 bg-gradient-to-br from-pink-100 to-rose-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0 shadow-md">
                                        <i class="fas fa-hand-holding-heart text-xl text-pink-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-lg mb-1 text-gray-900">Atención Personalizada</h4>
                                        <p class="text-gray-700">Cada paciente es único para nosotros. Planes de tratamiento personalizados y seguimiento continuo.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact & System Access -->
        <section id="contacto" class="py-20 bg-gradient-to-br from-blue-50 to-cyan-50">
            <div class="container mx-auto px-6">
                <div class="grid md:grid-cols-2 gap-12">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                            <span class="text-blue-600">Accede al Sistema</span><br>
                            Gestión Digitalizada
                        </h2>
                        <p class="text-lg text-gray-700 mb-8">
                            Nuestro sistema de gestión integral te permite administrar toda la información 
                            de tu consulta de manera segura, eficiente y desde cualquier lugar.
                        </p>
                        
                        <div class="grid grid-cols-2 gap-6 mb-8">
                            <div class="bg-white rounded-xl p-5 shadow-md">
                                <i class="fas fa-lock text-2xl text-blue-500 mb-3"></i>
                                <h4 class="font-bold text-gray-900 mb-2">Seguridad Garantizada</h4>
                                <p class="text-sm text-gray-600">Datos encriptados y respaldos automáticos</p>
                            </div>
                            <div class="bg-white rounded-xl p-5 shadow-md">
                                <i class="fas fa-mobile-alt text-2xl text-teal-500 mb-3"></i>
                                <h4 class="font-bold text-gray-900 mb-2">Acceso Multiplataforma</h4>
                                <p class="text-sm text-gray-600">Desde computadora, tablet o smartphone</p>
                            </div>
                        </div>
                        
                       
                    </div>
                    
                    <div class="bg-white rounded-2xl p-8 shadow-xl">
                        <h3 class="text-2xl font-bold text-gray-900 mb-8 text-center">
                            <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                            Visítanos
                        </h3>
                        
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                    <i class="fas fa-map-marked-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-1">Ubicación</h4>
                                    <p class="text-gray-700">Barrio Nuevo, Av. Uyuni N° 510</p>
                                    <p class="text-sm text-gray-600">Tarija-Yacuiba, Bolivia</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-teal-50 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                    <i class="fas fa-phone-alt text-teal-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-1">WhatsApp</h4>
                                    <a href="https://wa.me/59174547514" 
                                       class="text-lg font-bold text-green-600 hover:text-green-700 transition-colors">
                                        <i class="fab fa-whatsapp mr-2"></i> +591 74547514
                                    </a>
                                    <p class="text-sm text-gray-600 mt-1">Atención inmediata por mensaje</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                    <i class="fas fa-clock text-purple-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-1">Horarios de Atención</h4>
                                    <p class="text-gray-700">Lunes a Viernes: 8:00 - 20:00</p>
                                    <p class="text-gray-700">Sábados: 9:00 - 14:00</p>
                                    <p class="text-sm text-gray-600 mt-1">Citas previa coordinación</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-cyan-50 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                    <i class="fas fa-user-md text-cyan-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-1">Dra. Adela García Chacón</h4>
                                    <p class="text-gray-700">Odontóloga General</p>
                                    <p class="text-sm text-gray-600">Especialista en Rehabilitación Oral</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
         <div class="container mx-auto px-6 text-center">
    <p class="text-gray-400">
        © 2024 Consultorio Odontológico AG. Todos los derechos reservados. 
        <span class="block mt-2 text-sm">
            Sistema de Gestión desarrollado con Laravel y Tailwind CSS
        </span>
    </p>
</div>
        </footer>

        <!-- Floating WhatsApp Button -->
        <a href="https://wa.me/59174547514" 
           target="_blank"
           class="fixed bottom-6 right-6 w-14 h-14 bg-green-500 rounded-full flex items-center justify-center shadow-xl hover:shadow-2xl transition-all z-50 animate-float">
            <i class="fab fa-whatsapp text-white text-2xl"></i>
        </a>

        <script>
            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    if(targetId === '#') return;
                    
                    const targetElement = document.querySelector(targetId);
                    if(targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Header scroll effect
            window.addEventListener('scroll', function() {
                const header = document.querySelector('header');
                if(window.scrollY > 50) {
                    header.classList.add('shadow-lg');
                } else {
                    header.classList.remove('shadow-lg');
                }
            });

            // Manejo de error de imagen del logo
            document.addEventListener('DOMContentLoaded', function() {
                const logoImages = document.querySelectorAll('img[src*="clini.png"]');
                logoImages.forEach(img => {
                    img.onerror = function() {
                        this.src = 'https://img.icons8.com/color/96/000000/dental-braces.png';
                        this.style.objectFit = 'contain';
                        this.style.padding = '8px';
                    };
                });
            });
        </script>
    </body>
</html>