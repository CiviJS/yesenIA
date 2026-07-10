<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'YesenIA' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  
</head>

<body class="min-h-screen bg-zinc-50 dark:bg-zinc-900">

    {{-- HEADER PROFESIONAL --}} 
    <flux:header container
        class="border-b border-zinc-200 dark:border-zinc-700 bg-white/80 dark:bg-zinc-900/80 backdrop-blur">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:brand href="#" name="YesenIA" class="max-lg:hidden" />

        <flux:spacer />

        {{-- Usamos flux:navbar para los ítems de navegación --}}
        <flux:navbar class="max-lg:hidden">
            <flux:navbar.item icon="home" href="{{ route('dashboard') }}" :current="request()->routeIs('dashboard')">
                Dashboard</flux:navbar.item>
            <flux:navbar.item icon="user" href="{{ route('clients.index') }}"
                :current="request()->routeIs('clients.index')">Clientes</flux:navbar.item>
            <flux:navbar.item icon="banknotes" href="{{ route('sales.index') }}"
                :current="request()->routeIs('sales.index')">Ventas</flux:navbar.item>
            <flux:navbar.item icon="credit-card" href="{{ route('orders.index') }}"
                :current="request()->routeIs('orders.index')">Deudas</flux:navbar.item>
            <flux:navbar.item icon="beer" href="{{ route('products.index') }}"
                :current="request()->routeIs('products.index')">Productos</flux:navbar.item>
        </flux:navbar>

        <flux:spacer />

        <flux:dropdown>
            <flux:profile name="{{ auth()->user()->name ?? 'User' }}" />

            <flux:menu>
                <flux:menu.item icon="cog-6-tooth" href="{{ route('profile.edit') }}">Perfil</flux:menu.item>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <flux:menu.item icon="arrow-right-start-on-rectangle" as="button" type="submit">Cerrar sesión
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

<flux:sidebar closable stashable class="lg:hidden dark:bg-zinc-900">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
    
    <flux:brand href="#" name="YesenIA" class="px-2" />

    <flux:sidebar.nav class="mt-6 ">
        <flux:sidebar.item icon="home" href="{{ route('dashboard') }}" :current="request()->routeIs('dashboard')">Dashboard</flux:sidebar.item>
        <flux:sidebar.item icon="user" href="{{ route('clients.index') }}" :current="request()->routeIs('clients.index')">Clientes</flux:sidebar.item>
        <flux:sidebar.item icon="banknotes" href="{{ route('sales.index') }}" :current="request()->routeIs('sales.index')">Ventas</flux:sidebar.item>
        <flux:sidebar.item icon="credit-card" href="{{ route('orders.index') }}" :current="request()->routeIs('orders.index')">Deudas</flux:sidebar.item>
        <flux:sidebar.item icon="beer" href="{{ route('products.index') }}" :current="request()->routeIs('products.index')">Productos</flux:sidebar.item>
    </flux:sidebar.nav>
</flux:sidebar>



    {{-- CONTENIDO PRINCIPAL (Donde vive la magia) --}}
    <flux:main container class="p-6 lg:p-10">
        
            {{ $slot }}
     
    </flux:main>

    @fluxScripts
</body>

</html>