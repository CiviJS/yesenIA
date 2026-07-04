<x-layouts::app :title="__('Deudas')">
    <div class="space-y-8 max-w-6xl mx-auto py-6">
        @if (session('success'))
            <div class="bg-green-50 dark:bg-green-950/20 border border-green-200 dark:border-green-800 p-4 rounded-md text-green-700 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800 p-4 rounded-md text-red-700 dark:text-red-400">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <flux:heading size="xl" level="1">{{ __('Deudas') }}</flux:heading>
                <flux:subheading>{{ __('Revisa y gestiona las deudas registradas.') }}</flux:subheading>
            </div>
            <div class="flex gap-2">
                <flux:button variant="primary" icon="plus" wire:navigate :href="route('orders.create')">
                    {{ __('Nueva deuda') }}
                </flux:button>
                <flux:button variant="ghost" icon="arrow-path" wire:navigate :href="route('orders.index')">
                    {{ __('Actualizar lista') }}
                </flux:button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($orders as $order)
                <flux:card class="flex flex-col gap-4 {{ (bool) $order->is_canceled ? 'opacity-60' : '' }}">
                    <div class="flex justify-between items-center">
                        <flux:subheading># Deuda: {{ $order->id }}</flux:subheading>
                        <flux:badge color="{{ (bool) $order->is_canceled ? 'red' : 'green' }}" size="sm">
                            {{ (bool) $order->is_canceled ? 'Cancelada' : 'Pendiente' }}
                        </flux:badge>
                    </div>

                    <div class="space-y-2 border-y border-zinc-200 dark:border-zinc-700 py-3">
                        <flux:subheading size="sm">{{ __('Cliente') }}</flux:subheading>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $order->client->name ?? 'Sin cliente' }}
                        </p>

                        <flux:subheading size="sm">{{ __('Detalle') }}</flux:subheading>
                        <ul class="text-sm text-zinc-600 dark:text-zinc-400">
                            @foreach($order->items as $item)
                                <li class="flex justify-between">
                                    <span>{{ $item->quantity }}x {{ $item->product->name ?? 'Producto' }}</span>
                                    <span>${{ number_format($item->unit_price * $item->quantity, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-xs text-zinc-400">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        <flux:heading size="md" class="text-blue-600 dark:text-blue-400">
                            ${{ number_format($order->total_amount, 2) }}
                        </flux:heading>
                    </div>

                    <div class="flex justify-end pt-2">
                        @if (! (bool) $order->is_canceled)
                            <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('¿Seguro?');">
                                @csrf @method('DELETE')
                                <flux:button type="submit" variant="ghost" icon="trash" class="text-red-500">Cancelar</flux:button>
                            </form>
                        @else
                            <form action="{{ route('orders.restore', $order) }}" method="POST">
                                @csrf @method('PUT')
                                <flux:button type="submit" variant="ghost" icon="arrow-path" class="text-green-500">Restaurar</flux:button>
                            </form>
                        @endif
                    </div>
                </flux:card>
            @empty
                <div class="md:col-span-2 lg:col-span-3 rounded-lg border border-dashed border-zinc-300 dark:border-zinc-700 p-8 text-center text-zinc-500">
                    No hay deudas registradas por el momento.
                </div>
            @endforelse
        </div>

        <div>{{ $orders->links() }}</div>
    </div>
</x-layouts::app>
