<x-layouts::app :title="__('Clientes')">
    <div class="space-y-8 max-w-6xl mx-auto py-6">

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
             <div>
                <flux:heading size="xl" level="1">{{ __('Clientes') }}</flux:heading>
                <flux:subheading>{{ __('Gestiona tu base de datos de clientes con rapidez.') }}</flux:subheading>
            </div>

            <flux:button variant="primary" icon="plus" wire:navigate :href="route('clients.create')">
                {{ __('Nuevo Cliente') }}
            </flux:button>
            <flux:button variant="ghost" wire:navigate :href="route('products.index')">
                {{ __('Actualizar lista') }}
            </flux:button>
        </div>

        <flux:card class="shadow-sm rounded-xl overflow-hidden">

            {{-- MODO ESCRITORIO: TABLA --}}
            <div class="hidden md:block overflow-x-auto">
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>{{ __('Nombre') }}</flux:table.column>
                        <flux:table.column>{{ __('Teléfono') }}</flux:table.column>
                        <flux:table.column>{{ __('Dirección') }}</flux:table.column>
                        <flux:table.column align="right">{{ __('Acciones') }}</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @forelse ($clients as $client)
                            <flux:table.row>
                                <flux:table.cell class="font-medium text-zinc-900 dark:text-zinc-100">{{ $client->name }}
                                </flux:table.cell>
                                <flux:table.cell class="text-zinc-500 dark:text-zinc-400">{{ $client->phone }}
                                </flux:table.cell>
                                <flux:table.cell class="text-zinc-500 dark:text-zinc-400">{{ $client->address }}
                                </flux:table.cell>
                                <flux:table.cell align="right" class="space-x-2">
                                    <flux:button variant="ghost" size="xs" icon="pencil" wire:navigate
                                        :href="route('clients.edit', $client)">Editar</flux:button>
                                    <form action="{{ route('clients.delete', $client) }}" method="POST" class="inline"
                                        onsubmit="return confirm('¿Seguro?');">
                                        @csrf @method('DELETE')
                                        <flux:button type="submit" variant="ghost" size="xs" icon="trash"
                                            class="text-red-500">Eliminar</flux:button>
                                    </form>
                                </flux:table.cell>
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="4" class="text-center py-10">No hay registros.</flux:table.cell>
</flux:table.row>
                        @endforelse
                    </flux:table.rows>
                </flux:table>
            </div>


            {{-- MODO MÓVIL: CARTAS --}}
            <div class="md:hidden divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($clients as $client)
                    <div class="p-4 space-y-3">
                        <div class="flex justify-between items-start">
                            <p class="font-bold text-zinc-900 dark:text-zinc-100">{{ $client->name }}</p>
                            <div class="flex gap-1">
                                <flux:button variant="ghost" size="xs" icon="pencil" wire:navigate
                                    :href="route('clients.edit', $client)" />
                                <form action="{{ route('clients.delete', $client) }}" method="POST"
                                    onsubmit="return confirm('¿Seguro?');">
                                    @csrf @method('DELETE')
                                    <flux:button type="submit" variant="ghost" size="xs" icon="trash"
                                        class="text-red-500" />
                                </form>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 text-sm text-zinc-500 dark:text-zinc-400">
                            <div class="flex items-center gap-2">
                                <flux:icon name="phone" class="w-4 h-4" />
                                <span>{{ $client->phone }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:icon name="map-pin" class="w-4 h-4" />
                                <span>{{ $client->address }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="p-6 text-center text-zinc-400 italic">No hay clientes registrados.</p>
                @endforelse
            </div>

            <div class="p-4 border-t border-zinc-200 dark:border-zinc-700">
                {{ $clients->links() }}
            </div>
        </flux:card>
    </div>
</x-layouts::app>