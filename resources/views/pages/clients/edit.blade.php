<x-layouts::app :title="__('Editar Cliente')">
    <div class="space-y-8 max-w-2xl mx-auto py-6">
        @if (session('success'))
            <flux:card class="bg-green-50 dark:bg-green-950/20 border-green-200 dark:border-green-800/50 p-4">
                <div class="flex items-center gap-3 text-green-700 dark:text-green-400">
                    <flux:icon name="check-circle" class="w-5 h-5 flex-shrink-0" />
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </flux:card>
        @endif

        @if ($errors->any())
            <flux:card class="bg-red-50 dark:bg-red-950/20 border-red-200 dark:border-red-800/50 p-4">
                <div class="text-red-700 dark:text-red-400">
                    <p class="font-bold text-sm mb-2">{{ __('Por favor corrige los siguientes errores:') }}</p>
                    <ul class="list-disc pl-5 text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </flux:card>
        @endif

        <div>
            <flux:heading size="xl" level="1">{{ __('Editar Cliente') }}</flux:heading>
            <flux:subheading>{{ __('Modifica los datos y guarda los cambios.') }}</flux:subheading>
        </div>

        <flux:card class="p-6 shadow-sm rounded-xl">
            <form action="{{ route('clients.update', $client) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <flux:field>
                    <flux:label>{{ __('Nombre') }}</flux:label>
                    <flux:input name="name" value="{{ old('name', $client->name) }}" />
                    @error('name') <flux:description class="text-red-500">{{ $message }}</flux:description> @enderror
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Teléfono') }}</flux:label>
                    <flux:input name="phone" value="{{ old('phone', $client->phone) }}" />
                    @error('phone') <flux:description class="text-red-500">{{ $message }}</flux:description> @enderror
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Dirección') }}</flux:label>
                    <flux:input name="address" value="{{ old('address', $client->address) }}" />
                    @error('address') <flux:description class="text-red-500">{{ $message }}</flux:description> @enderror
                </flux:field>

                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <flux:button href="{{ route('clients.index') }}" variant="ghost" wire:navigate>
                        {{ __('Cancelar') }}
                    </flux:button>
                    <flux:button type="submit" variant="primary">{{ __('Guardar Cambios') }}</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>