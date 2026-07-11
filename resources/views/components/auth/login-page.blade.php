<x-layouts::auth :title="__('Iniciar Sesión')">
    
    <div class="absolute inset-0 w-full h-full z-0 overflow-hidden pointer-events-none">
        <img src="{{ asset('assets/estadero1.png') }}" 
             alt="Fondo Estadero" 
             class="w-full h-full object-cover object-center opacity-30 mix-blend-luminosity">
        <div class="absolute inset-0 bg-gradient-to-b from-zinc-950 via-zinc-950/90 to-zinc-950"></div>
    </div>

    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 min-h-screen w-full box-border">
        
        <div class="w-full max-w-md border border-zinc-800 rounded-3xl p-6 sm:p-8 shadow-[0_35px_60px_-15px_rgba(0,0,0,0.95)] relative overflow-hidden flex flex-col justify-between"
             style="background-color: #000000ba !important; opacity: 1 !important;">
            
            <div class="absolute top-0 left-6 right-6 h-px bg-gradient-to-r from-transparent via-blue-500/40 to-transparent"></div>

            <div class="flex flex-col items-center mb-6">
                <div class="w-12 h-12 bg-zinc-950 border border-zinc-800 rounded-2xl flex items-center justify-center shadow-md">
                    <span class="font-black text-lg text-zinc-100">EF</span>
                </div>
                <h2 class="text-xl font-black text-white uppercase tracking-tight mt-3">
                    {{ __('YesenIA: ¿quien es usted?') }}
                </h2>
                <p class="text-xs text-zinc-400 mt-1 text-center">
                    {{ __('Ingrese sus credenciales de acceso: (inicia sesion)') }}
                </p>
            </div>

            <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5 w-full">
                @csrf

                <div class="w-full block">
                    <flux:input
                        name="email"
                        :label="__('Correo Electrónico')"
                        :value="old('email')"
                        type="email"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="ejemplo@correo.com"
                        class="text-white focus:border-blue-500 w-full"
                        style="background-color: #09090b !important; opacity: 1 !important;"
                    />
                </div>

                <div class="relative flex flex-col gap-1 w-full">
                    <flux:input
                        name="password"
                        :label="__('Contraseña')"
                        type="password"
                        required
                        autocomplete="current-password"
                        :placeholder="__('Introduce tu contraseña')"
                        viewable
                        class="text-white focus:border-blue-500 w-full"
                        style="background-color: #09090b !important; opacity: 1 !important;"
                    />

                    @if (Route::has('password.request'))
                        <div class="text-right mt-1">
                            <flux:link class="text-xs font-semibold text-zinc-400 hover:text-blue-400 transition-colors" :href="route('password.request')" wire:navigate>
                                {{ __('¿Olvidaste tu contraseña?') }}
                            </flux:link>
                        </div>
                    @endif
                </div>

                <div class="flex items-center my-1">
                    <flux:checkbox 
                        name="remember" 
                        :label="__('Mantener sesión iniciada')" 
                        :checked="old('remember')" 
                        class="text-zinc-300"
                    />
                </div>

                <div class="mt-1 w-full">
                    <flux:button 
                        type="submit" 
                        class="w-full font-black uppercase tracking-wider py-3.5 bg-blue-600 hover:bg-blue-500 text-white border-none rounded-xl shadow-lg shadow-blue-600/20 active:scale-95 transition-all duration-200 cursor-pointer block text-center"
                        data-test="login-button"
                    >
                        {{ __('Ingresar Ahora') }}
                    </flux:button>
                </div>
            </form>

            @if (Route::has('register'))
                <div class="mt-6 text-xs sm:text-sm text-center text-zinc-500 border-t border-zinc-800/80 pt-4 w-full">
                    <span>{{ __('¿No tienes una cuenta operativa?') }}</span>
                    <flux:link class="font-bold text-red-400 hover:text-red-300 transition-colors ml-1" :href="route('register')" wire:navigate>
                        {{ __('Regístrate aquí') }}
                    </flux:link>
                </div>
            @endif

        </div>
    </div>

</x-layouts::auth>