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

        {{-- Header Principal --}}
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

            {{-- Encabezado de la Deuda --}}
            <div class="flex justify-between items-center pb-2">
                <flux:heading size="lg"># Deuda: {{ $order->id }}</flux:heading>
                <flux:badge color="{{ $order->getStatus() ? 'green' : 'red' }}">
                    {{ $order->getStatus() ? 'Pendiente' : 'Cancelada' }}
                </flux:badge>
            </div>

            <flux:separator />

            {{-- Información resumida --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <flux:subheading>{{ __('Cliente') }}</flux:subheading>
                    <p class="text-zinc-800 dark:text-zinc-200 font-medium mt-1">
                        {{ $order->client->name ?? 'Sin cliente' }}
                    </p>
                </div>
                <div>
                    <flux:subheading>{{ __('Fecha de Creación') }}</flux:subheading>
                    <p class="text-zinc-600 dark:text-zinc-400 mt-1">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="sm:text-right">
                    <flux:subheading>{{ __('Monto rinicial de deuda') }}</flux:subheading>
                    <p class="text-zinc-800 dark:text-zinc-200 font-semibold mt-1">
                        ${{ number_format($order->total_amount, 2) }}</p>
                </div>
            </div>



            <flux:separator />

            {{-- Sección 1: Registrar nuevo pago (Condicional limpio usando el Atributo de Eloquent) --}}
            @if($order->remainingAmount > 0 && $order->getStatus())
                <div>
                    <flux:heading size="md" class="mb-3">{{ __('Registrar Abono / Pago') }}</flux:heading>
                    <form action="{{ route('payments.pay', $order) }}" method="POST"
                        class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        @csrf
                        <div class="md:col-span-2">
                            <flux:input name="amount" label="Monto a pagar" type="number" step="0.01" placeholder="0.00"
                                value="{{ $order->remaining_amount }}" required />
                        </div>

                        <div>
                            <flux:select label="Método de pago" name="payment_method" required>
                                <flux:select.option value="">{{ __('Selecciona...') }}</flux:select.option>
                                <flux:select.option value="efectivo">Efectivo</flux:select.option>
                                <flux:select.option value="transferencia">Transferencia</flux:select.option>
                            </flux:select>
                        </div>

                        <div>
                            <flux:button type="submit" variant="primary" class="w-full">
                                {{ __('Registrar Pago') }}
                            </flux:button>
                        </div>
                        <flux:input hidden name="order_id" value="{{ $order->id }}"></flux:input>

                    </form>
                </div>
                <flux:separator />
            @endif

            {{-- Sección 2: Productos --}}
            <div>
                <flux:heading size="md" class="mb-3" icon="shopping-bag">{{ __('Productos en esta deuda') }}
                </flux:heading>
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Producto</flux:table.column>
                        <flux:table.column>Estado</flux:table.column>
                        <flux:table.column align="right">Total</flux:table.column>
                        <flux:table.column align="right">Acción</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @foreach($order->items as $item)
                            <flux:table.row>
                                <flux:table.cell class="font-medium text-zinc-800 dark:text-zinc-200">
                                    {{ $item->quantity }}x {{ $item->product->name ?? 'Producto' }}
                                </flux:table.cell>

                                <flux:table.cell>
                                    <flux:badge color="{{ $item->getStatus() ? 'green' : 'red' }}" size="sm">
                                        {{ $item->getStatus() ? 'Activo' : 'Cancelado' }}
                                    </flux:badge>
                                </flux:table.cell>

                                <flux:table.cell align="right" class="text-zinc-700 dark:text-zinc-300">
                                    ${{ number_format($item->unit_price * $item->quantity, 2) }}
                                </flux:table.cell>

                                <flux:table.cell align="right">
                                    @if ($item->getStatus())
                                        <form action="{{ route('orderItem.cancel', $item) }}" method="POST"
                                            onsubmit="return confirm('¿Cancelar este producto?');">
                                            @csrf @method('DELETE')
                                            <flux:button type="submit" variant="ghost" icon="trash"
                                                class="text-red-500 hover:text-red-600" size="sm">Cancelar</flux:button>
                                        </form>
                                    @else
                                        <form action="{{ route('orderItem.restore', $item) }}" method="POST"
                                            onsubmit="return confirm('¿Restaurar este producto?');">
                                            @csrf @method('PUT')
                                            <flux:button type="submit" variant="ghost" icon="arrow-path"
                                                class="text-green-500 hover:text-green-600" size="sm">Restaurar</flux:button>
                                        </form>
                                    @endif
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            </div>



            <flux:separator />

            {{-- Sección 3 : Historial de Pagos --}}
            <div>
                <flux:heading size="md" class="mb-3" icon="credit-card">{{ __('Historial de Pagos Realizados') }}
                </flux:heading>
                @if($order->payments->isEmpty())
                    <p class="text-sm text-zinc-500 italic py-2 pl-1">{{ __('No hay pagos registrados para esta deuda.') }}
                    </p>
                @else
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>Fecha</flux:table.column>
                            <flux:table.column>Método</flux:table.column>
                            <flux:table.column align="right">Monto</flux:table.column>
                            <flux:table.column align="right">Acción</flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @foreach($order->payments as $payment)
                                <flux:table.row>
                                    <flux:table.cell class="text-zinc-600 dark:text-zinc-400">
                                        {{ $payment->created_at->format('d/m/Y H:i') }}
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        <flux:badge color="zinc" class="capitalize">{{ $payment->payment_method }}</flux:badge>
                                    </flux:table.cell>
                                    <flux:table.cell align="right" class="text-green-600 dark:text-green-400 font-medium">
                                        +${{ number_format($payment->amount, 2) }}
                                    </flux:table.cell>
                                    <flux:table.cell align="right">
                                        <form action="{{ route('payments.cancel', $payment) }}" method="POST"
                                            onsubmit="return confirm('¿Estás seguro de que deseas cancelar este pago?');">
                                            @csrf
                                            @method('DELETE')
                                            <flux:button type="submit" variant="ghost" icon="x-mark"
                                                class="text-red-500 hover:text-red-600" size="sm">
                                                {{ __('Eliminar Pago') }}
                                            </flux:button>
                                        </form>
                                    </flux:table.cell>
                                </flux:table.row>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
                @endif
            </div>
            
            <flux:separator />
            {{-- Totales y Balances --}}
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 pt-2">
                <div class="text-sm text-zinc-500">
                    <span>Total Pagado: ${{ number_format($order->paid_amount, 2) }}</span>
                </div>
                <div class="font-semibold text-right">
                    <span
                        class="{{ $order->remaining_amount > 0 ? 'text-blue-600 dark:text-green-400' : 'text-zinc-500' }} text-lg">
                        {{ __('Total Restante Pendiente:') }}
                        ${{ number_format($order->remaining_amount, 2) }}
                    </span>
                </div>
            </div>
        </flux:card>
    </div>
</x-layouts::app>