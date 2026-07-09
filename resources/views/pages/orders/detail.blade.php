<x-layouts::app :title="__('Detalle de deuda')">
    <div class="space-y-6 max-w-4xl mx-auto py-6">

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
        {{-- Header --}}
        <div class="flex justify-between items-center">
            <div>
                <flux:heading size="xl">{{ __('Detalle de deuda') }}</flux:heading>
                <flux:subheading>{{ __('Revisa los detalles y gestiona el pago.') }}</flux:subheading>
            </div>
            <flux:button variant="ghost" icon="arrow-left" wire:navigate :href="route('orders.index')">
                {{ __('Volver') }}
            </flux:button>
        </div>

        <flux:card class="space-y-6 {{ !$order->getStatus() ? 'opacity-70' : '' }}">
            <div class="flex justify-between items-center">
                <flux:heading size="lg"># Deuda: {{ $order->id }}</flux:heading>
                <flux:badge color="{{ $order->getStatus() ? 'green' : 'red' }}">
                    {{ $order->getStatus() ? 'Pendiente' : 'Cancelada' }}
                </flux:badge>
            </div>

            <flux:separator />

            <div class="flex justify-between items-center">
                <div>
                    <flux:subheading>{{ __('Cliente') }}</flux:subheading>
                    <p class="text-zinc-600 dark:text-zinc-400">{{ $order->client->name ?? 'Sin cliente' }}</p>
                </div>
                <div class="text-right">
                    <flux:subheading>{{ __('Fecha') }}</flux:subheading>
                    <p class="text-zinc-600 dark:text-zinc-400">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

         <flux:table>
    <flux:table.columns>
        <flux:table.column>Producto</flux:table.column>
        <flux:table.column>Estado</flux:table.column> <flux:table.column align="right">Total</flux:table.column>
        <flux:table.column align="right">Acción</flux:table.column>
    </flux:table.columns>

    <flux:table.rows>
        @foreach($order->items as $item)
            <flux:table.row>
                <flux:table.cell>{{ $item->quantity }}x {{ $item->product->name ?? 'Producto' }}</flux:table.cell>
                
                {{-- Columna de Estado --}}
                <flux:table.cell>
                    <flux:badge color="{{ $item->getStatus() ? 'green' : 'red' }}" size="sm">
                        {{ $item->getStatus() ? 'Activo' : 'Cancelado' }}
                    </flux:badge>
                </flux:table.cell>

                <flux:table.cell align="right">${{ number_format($item->unit_price * $item->quantity, 2) }}</flux:table.cell>
                
                <flux:table.cell align="right">
                    @if ($item->getStatus())
                        <form action="{{ route('orderItem.cancel', $item) }}" method="POST"
                            onsubmit="return confirm('¿Cancelar este producto?');">
                            @csrf @method('DELETE')
                            <flux:button type="submit" variant="ghost" icon="trash" class="text-red-500">Cancelar</flux:button>
                        </form>
                    @else
                        <form action="{{ route('orderItem.restore', $item) }}" method="POST"
                            onsubmit="return confirm('¿Restaurar este producto?');">
                            @csrf @method('PUT')
                            <flux:button type="submit" variant="ghost" icon="arrow-path" class="text-green-500">Restaurar</flux:button>
                        </form>
                    @endif
                </flux:table.cell>
            </flux:table.row>
        @endforeach
    </flux:table.rows>
</flux:table>

            <flux:separator />

            {{-- Formulario de Pago --}}

            <form action="{{ route('payments.pay', $order) }}" method="POST"
                class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                @csrf
                <flux:input hidden name="order_id" value="{{ $order->id }}"></flux:input>


                <div class="md:col-span-2">

                    <flux:input name="amount" label="Monto a pagar" type="number" step="0.01" placeholder="0.00"
                        value="{{ $order->total_amount }}" required />
                </div>

                {{-- Método (Ocupa 1 columna) --}}
                <flux:select label="Método" name="payment_method">
                    <flux:select.option value="">{{ __('Selecciona...') }}</flux:select.option>
                    <flux:select.option value="efectivo">Efectivo</flux:select.option>
                    <flux:select.option value="transferencia">Transferencia</flux:select.option>
                </flux:select>

                {{-- Botón (Ocupa 1 columna) --}}
                <flux:button type="submit" variant="primary" class="w-full">
                    {{ __('Pagar') }}
                </flux:button>

            </form>

            <div class="flex justify-between items-center font-semibold pt-2">
                <span class="text-blue-600 dark:text-green-400 text-lg">{{ __('Total pendiente') }}
                    ${{ number_format($order->total_amount, 2) }}</span>
            </div>
        </flux:card>
    </div>
</x-layouts::app>