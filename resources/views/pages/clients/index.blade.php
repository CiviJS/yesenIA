<x-layouts::app :title="__('Clientes')">
    @if (session('success'))
        <flux:card class="bg-green-50 dark:bg-green-950/20 border-green-200 dark:border-green-800/50 p-4">
            <div class="flex items-center gap-3 text-green-700 dark:text-green-400">
                <flux:icon name="check-circle" class="w-5 h-5 flex-shrink-0" />
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        </flux:card>
    @endif
  @if ($errors->any())
        <flux:card class="bg-red-50 dark:bg-red-950/20 border-red-200 dark:border-red-800/50 p-4 mb-6">
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
    <div class="space-y-8">
        {{-- Header Section --}}
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">{{ __('Clientes') }}</flux:heading>
                <flux:subheading>{{ __('Gestiona tu base de datos de clientes.') }}</flux:subheading>
            </div>

            <flux:button variant="primary" icon="plus" wire:navigate :href="route('clients.create')">
                {{ __('Nuevo Cliente') }}
            </flux:button>
        </div>

        <flux:separator />

        {{-- Main Table Card --}}
        <flux:card class="shadow-sm rounded-xl">
            <flux:table>
                {{-- Corregido para Flux v2: Todo lleva el prefijo table. --}}
                <flux:table.columns>
                    <flux:table.column>{{ __('Nombre') }}</flux:table.column>
                    <flux:table.column>{{ __('Teléfono') }}</flux:table.column>
                    <flux:table.column>{{ __('Dirección') }}</flux:table.column>
                    <flux:table.column align="right">{{ __('Acciones') }}</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($clients as $client)
                        <flux:table.row>
                            <flux:table.cell class="font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $client->name }}
                            </flux:table.cell>
                            <flux:table.cell class="text-zinc-500 dark:text-zinc-400">
                                {{ $client->phone }}
                            </flux:table.cell>
                            <flux:table.cell class="text-zinc-500 dark:text-zinc-400">
                                {{ $client->address }}
                            </flux:table.cell>
                            <flux:table.cell align="right">
                                <flux:button variant="ghost" icon="pencil" wire:navigate
                                    :href="route('clients.edit', $client)">
                                    {{ __('Editar') }}
                                </flux:button>
                            </flux:table.cell>
                            <flux:table.cell align="right">
                                <form action="{{ route('clients.delete', $client) }}" method="POST" class="inline"
                                    onsubmit="return confirm('¿Seguro que deseas eliminar este cliente?');">
                                    @csrf
                                    @method('DELETE')

                                    <flux:button type="submit" variant="ghost" icon="trash"
                                        class="text-red-500 hover:text-red-600">
                                        {{ __('Eliminar') }}
                                    </flux:button>
                                </form>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="4" class="text-center py-10 text-zinc-400 italic">
                                {{ __('No hay clientes registrados.') }}
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

            {{-- Pagination Wrapper --}}
            <div class="pt-6">
                {{ $clients->links() }}
            </div>
        </flux:card>
    </div>
</x-layouts::app>