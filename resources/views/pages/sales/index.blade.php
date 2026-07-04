<x-layouts::app :title="__('Ventas')">
    <div class="space-y-8 max-w-6xl mx-auto py-6">
        {{-- En lugar de flux:alert --}}
        @if (session('success'))
            <div
                class="bg-green-50 dark:bg-green-950/20 border border-green-200 dark:border-green-800 p-4 rounded-md text-green-700 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div
                class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800 p-4 rounded-md text-red-700 dark:text-red-400">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{-- Cabecera --}}
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <flux:heading size="xl" level="1">{{ __('Ventas') }}</flux:heading>
                <flux:subheading>{{ __('Registra y revisa tus ventas realizadas.') }}</flux:subheading>
            </div>
            <div class="flex gap-2">
                <flux:button variant="primary" icon="plus" wire:navigate :href="route('sales.create')">
                    {{ __('Nueva venta') }}
                </flux:button>
                <flux:button variant="ghost" icon="arrow-path" wire:navigate :href="route('products.index')">
                    {{ __('Actualizar lista') }}
                </flux:button>
            </div>
        </div>

        {{-- Grid de Ventas --}}
        <flux:heading size="lg" class="mb-4">Ventas del Día</flux:heading>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($sales as $sale)
                <flux:card class="flex flex-col gap-4 {{ $sale->trashed() ? 'opacity-60' : '' }}">

                    <div class="flex justify-between items-center">
                        <flux:subheading># Venta: {{ $sale->id }}</flux:subheading>

                        {{-- Badge inteligente de Flux --}}
                        <flux:badge color="{{ $sale->trashed() ? 'red' : 'green' }}" size="sm">
                            {{ $sale->trashed() ? 'Cancelada' : 'Activa' }}
                        </flux:badge>
                    </div>

                    {{-- Lista de productos --}}
                    <div class="space-y-2 border-y border-zinc-200 dark:border-zinc-700 py-3">
                        <flux:subheading size="sm">{{ __('Productos:') }}</flux:subheading>
                        <ul class="text-sm text-zinc-600 dark:text-zinc-400">
                            @foreach($sale->items as $item)
                                <li class="flex justify-between">
                                    <span>{{ $item->quantity }}x {{ $item->product->name }}</span>
                                    <span>${{ number_format($item->unit_price * $item->quantity, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Total y Acciones --}}
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-zinc-400">{{ $sale->created_at->format('d/m/Y H:i') }}</span>
                        <flux:heading size="md" class="text-blue-600 dark:text-blue-400">
                            ${{ number_format($sale->items->sum(fn($i) => $i->unit_price * $i->quantity), 2) }}
                        </flux:heading>
                    </div>

                    <div class="flex justify-end pt-2">
                        @if (!$sale->trashed())
                            <form action="{{ route('sales.delete', $sale) }}" method="POST"
                                onsubmit="return confirm('¿Seguro?');">
                                @csrf @method('DELETE')
                                <flux:button type="submit" variant="ghost" icon="trash" class="text-red-500">Cancelar
                                </flux:button>
                            </form>
                        @else
                            <form action="{{ route('sales.restore', $sale) }}" method="POST">
                                @csrf @method('PUT')
                                <flux:button type="submit" variant="ghost" icon="arrow-path" class="text-green-500">Restaurar
                                </flux:button>
                            </form>
                        @endif
                    </div>
                </flux:card>
            @endforeach
        </div>

        <div>{{ $sales->links() }}</div>
    </div>
</x-layouts::app>