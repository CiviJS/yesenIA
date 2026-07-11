<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('Bienvenido') }} - {{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            .animate-fade-in-up {
                animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
            .animation-delay-200 {
                animation-delay: 200ms;
            }
            .animation-delay-400 {
                animation-delay: 400ms;
            }
            /* Patrón de confeti sutil de fondo */
            .bg-carnaval-pattern {
                background-color: #09090b;
                background-image: 
                    radial-gradient(#ca8a04 1px, transparent 0), 
                    radial-gradient(#7c3aed 1px, transparent 0),
                    radial-gradient(#dc2626 1px, transparent 0);
                background-size: 40px 40px;
                background-position: 0 0, 20px 20px, 10px 30px;
                background-opacity: 0.15;
            }
        </style>
    </head>
    <body class="antialiased min-h-screen w-full relative flex flex-col justify-between bg-zinc-950 text-white overflow-x-hidden selection:bg-amber-400 selection:text-zinc-950 bg-carnaval-pattern">
        
        <!-- Capa de fondo con la imagen del estadero y gradiente -->
        <div class="absolute inset-0 w-full h-full z-0 overflow-hidden pointer-events-none">
            <img src="{{ asset('assets/estadero1.png') }}" 
                 alt="Fondo Estadero" 
                 class="w-full h-full object-cover object-center opacity-15 mix-blend-luminosity">
            <div class="absolute inset-0 bg-gradient-to-b from-zinc-950/80 via-zinc-950/95 to-zinc-950"></div>
        </div>

        <header class="relative z-20 w-full max-w-7xl mx-auto px-4 sm:px-6 py-5 flex justify-between items-center opacity-0 animate-fade-in-up">
            <div class="flex items-center gap-3">
                <!-- Logotipo con borde de neón púrpura carnavalero -->
                <div class="w-10 h-10 bg-zinc-900 border-2 border-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-600/20">
                    <span class="font-black text-base text-amber-400">EF</span>
                </div>
                <span class="text-xl font-black tracking-widest uppercase bg-clip-text text-transparent bg-gradient-to-r from-amber-400 to-purple-400 hidden sm:block">
                    {{ config('app.name', 'Estadero') }}
                </span>
            </div>

            @if (Route::has('login'))
                <nav class="flex items-center gap-2 sm:gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-xs sm:text-sm font-black uppercase tracking-wider text-white bg-zinc-900 hover:bg-zinc-800 transition-colors px-4 py-2.5 rounded-xl border border-zinc-800 shadow-md">
                            {{ __('Panel') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs sm:text-sm font-black uppercase tracking-wider text-zinc-950 bg-amber-400 hover:bg-amber-300 transition-all px-4 py-2.5 rounded-xl shadow-lg shadow-amber-400/20 active:scale-95">
                            {{ __('Iniciar Sesión') }}
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-xs sm:text-sm font-black uppercase tracking-wider text-white bg-purple-700 hover:bg-purple-600 transition-all px-4 py-2.5 rounded-xl shadow-lg shadow-purple-700/20 active:scale-95">
                                {{ __('Registrarse') }}
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <main class="relative z-10 my-auto w-full max-w-4xl mx-auto px-4 sm:px-6 flex flex-col items-center text-center py-12">
            
            <!-- Badge con los colores insignia de la fiesta -->
            <div class="opacity-0 animate-fade-in-up inline-flex items-center gap-2 bg-zinc-900/90 border-2 border-amber-500/30 px-5 py-2 rounded-full text-xs font-black tracking-widest text-amber-400 uppercase mb-8 shadow-xl">
                <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-ping"></span>
                {{ __('Sistema de Control Operativo') }}
            </div>

            <!-- Título principal con gradiente de pura gozadera -->
            <h1 class="opacity-0 animate-fade-in-up animation-delay-200 text-4xl sm:text-7xl font-black tracking-tight text-white leading-none max-w-3xl uppercase">
                {{ __('Administración e') }}
                <span class="block mt-3 text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-red-500 to-purple-500 dropping-shadow">
                    {{ __('Inventario Real') }}
                </span>
            </h1>

            <p class="opacity-0 animate-fade-in-up animation-delay-400 text-zinc-400 text-base sm:text-lg max-w-xl mx-auto mt-8 font-medium leading-relaxed">
                {{ __('Lleva el registro de las ventas diarias, gestiona las existencias y supervisa de manera segura los saldos pendientes de tus clientes.') }}
            </p>

            <div class="opacity-0 animate-fade-in-up animation-delay-400 w-full max-w-xl mx-auto mt-10 px-2">
                @auth
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full">
                        
                        <!-- Registrar Venta - Verde esmeralda encendido -->
                        <a href="{{ route('sales.store') }}" class="group flex items-center justify-center font-black uppercase tracking-wider text-sm px-6 py-4 rounded-xl bg-emerald-600 text-white hover:bg-emerald-500 shadow-xl shadow-emerald-600/30 transition-all duration-300 hover:-translate-y-1 active:scale-95 border-b-4 border-emerald-800">
                            <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            {{ __('Registrar Venta') }}
                        </a>

                        <!-- Control de Deudas - Púrpura encendido -->
                        <a href="{{ route('orders.index') }}" class="group flex items-center justify-center font-black uppercase tracking-wider text-sm px-6 py-4 rounded-xl bg-purple-600 text-white hover:bg-purple-500 shadow-xl shadow-purple-600/30 transition-all duration-300 hover:-translate-y-1 active:scale-95 border-b-4 border-purple-800">
                            <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            {{ __('Control de Deudas') }}
                        </a>
                        
                    </div>
                @else
                    <!-- Botón de Entrada triunfal - Amarillo oro con borde inferior rojo -->
                    <div class="w-full">
                        <a href="{{ route('login') }}" class="group flex items-center justify-center w-full font-black uppercase tracking-wider text-base px-8 py-4 rounded-xl bg-amber-400 text-zinc-950 hover:bg-amber-300 shadow-2xl shadow-amber-400/40 transition-all duration-300 hover:-translate-y-1 active:scale-95 border-b-4 border-red-600">
                            {{ __('Ingresar al Panel de Control') }}
                            <svg class="w-5 h-5 ml-3 transition-transform group-hover:translate-x-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                @endauth
            </div>

        </main>

        <div class="h-5 w-full pointer-events-none select-none"></div>

    </body>
</html>