<x-layouts::app :title="__('Clientes')">
    <div class="space-y-6 max-w-2xl mx-auto py-6">
        
        {{-- Mensaje de Éxito --}}
        @if (session('success'))
            <flux:card class="bg-green-50 dark:bg-green-950/20 border-green-200 dark:border-green-800/50 p-4">
                <div class="flex items-center gap-3 text-green-700 dark:text-green-400">
                    <flux:icon name="check-circle" class="w-5 h-5 flex-shrink-0" />
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </flux:card>
        @endif
    
        {{-- Captura el error crítico de la excepción del controlador o validaciones globales --}}
        @if ($errors->any())
            <flux:card class="bg-red-50 dark:bg-red-950/20 border-red-200 dark:border-red-800/50 p-4">
                <div class="text-red-700 dark:text-red-400">
                    <p class="font-bold text-sm mb-2">{{ __('Error al registrar el cliente:') }}</p>
                    <ul class="list-disc pl-5 text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </flux:card>
        @endif

        {{-- Encabezado --}}
        <div>
            <flux:heading size="xl" level="1">{{ __('Nuevo Cliente') }}</flux:heading>
            <flux:subheading>{{ __('Ingresa los datos para registrar un nuevo cliente en el sistema.') }}</flux:subheading>
        </div>

        {{-- Formulario --}}
        <flux:card class="p-6 shadow-sm rounded-xl">
            <form action="{{ route('clients.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Input Nombre: Con value old para no perder el texto escrito --}}
                <div>
                    <flux:input 
                        name="name" 
                        label="Nombre completo" 
                        value="{{ old('name') }}" 
                        required 
                    />
                </div>

                {{-- Input Teléfono: Muestra su error específico abajo si ya existe en la Base de Datos --}}
                <div>
                    <flux:input 
                        name="phone" 
                        label="Teléfono" 
                        value="{{ old('phone') }}" 
                        required
                    />
                </div>

                {{-- Input Dirección --}}
                <div>
                    <flux:input 
                        name="address" 
                        label="Dirección" 
                        value="{{ old('address') }}" 
                    />
                </div>

                {{-- Botones de Acción --}}
                <div class="flex justify-end gap-3 pt-4">
                    <flux:button :href="route('clients.index')" variant="ghost" wire:navigate>
                        {{ __('Cancelar') }}
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        {{ __('Guardar cliente') }}
                    </flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>
