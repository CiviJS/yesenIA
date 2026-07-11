<x-layouts::app :title="__('Dashboard de Reportes')">
    <div class="space-y-8 max-w-6xl mx-auto py-6">

        {{-- Alertas y Notificaciones del Sistema --}}
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

        {{-- Encabezado Principal --}}
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <flux:heading size="xl" level="1">{{ __('Dashboard de Reportes') }}</flux:heading>
                <flux:subheading>{{ __('Análisis comercial, gestión de stock y proyecciones de IA.') }}
                </flux:subheading>
            </div>
            <div class="flex gap-2">
                <flux:button variant="ghost" icon="arrow-path" wire:navigate :href="route('dashboard')">
                    {{ __('Actualizar Reportes') }}
                </flux:button>
            </div>
        </div>

        {{-- Bloque 1: KPI de Cartera / Deudas (Datos Crudos Optimizados) --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <flux:card class="flex flex-col gap-2">
                <span
                    class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">{{ __('Total Deudas Registradas') }}</span>
                <flux:heading size="xl" class="text-zinc-800 dark:text-zinc-100">
                    ${{ number_format($totalDebt, 2) }}
                </flux:heading>
            </flux:card>

            <flux:card class="flex flex-col gap-2">
                <span
                    class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">{{ __('Total Abonado / Cobrado') }}</span>
                <flux:heading size="xl" class="text-green-600 dark:text-green-400">
                    ${{ number_format($totalPaid, 2) }}
                </flux:heading>
            </flux:card>

            <flux:card class="flex flex-col gap-2">
                <span
                    class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">{{ __('Cartera Pendiente (Por cobrar)') }}</span>
                <flux:heading size="xl" class="text-blue-600 dark:text-blue-400">
                    ${{ number_format($pendingDebt, 2) }}
                </flux:heading>
            </flux:card>
            <flux:card class="flex flex-col gap-2">
                <span
                    class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">{{ __('total vendido') }}</span>
                <flux:heading size="xl" class="text-blue-600 dark:text-blue-400">
                    ${{ number_format($totalSales, 2) }}
                </flux:heading>
            </flux:card>
        </div>

        {{-- Bloque 2: Módulo Generativo de Inteligencia Artificial (Gemini Pro) --}}
        <div
            class="border border-purple-200 dark:border-purple-900 bg-purple-50/30 dark:bg-purple-950/10 rounded-xl p-6 space-y-4">
            <div class="flex items-center gap-2 text-purple-700 dark:text-purple-400">
    
                <flux:heading size="lg" class="text-purple-800 dark:text-purple-300">
                    {{ __('Análisis Predictivo de IA') }}</flux:heading>
            </div>

            <p
                class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed bg-white dark:bg-zinc-900 p-4 rounded-lg border border-zinc-100 dark:border-zinc-800">
                <strong>Resumen Ejecutivo:</strong> {{ $aiReport['resumen_ejecutivo'] }}
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Predicciones de Restock Sugeridas por IA --}}
                <div
                    class="bg-white dark:bg-zinc-900 p-4 rounded-lg border border-zinc-100 dark:border-zinc-800 space-y-3">
                    <h4 class="text-xs font-bold text-zinc-400 uppercase tracking-wider">
                        {{ __('Proyección de Restock Preventivo') }}</h4>
                    <ul class="text-sm space-y-2">
                        @foreach($aiReport['predicciones_restock'] as $prediccion)
                            <li
                                class="flex justify-between items-center border-b border-zinc-50 dark:border-zinc-800/50 pb-2 last:border-0">
                                <span
                                    class="font-medium text-zinc-700 dark:text-zinc-300">{{ $prediccion['producto'] }}</span>
                                <div class="text-right">
                                    <flux:badge color="purple" size="sm">{{ $prediccion['fecha_estimada_sugerida'] }}
                                    </flux:badge>
                                    <p class="text-xs text-zinc-400 mt-0.5">Sugerido:
                                        {{ $prediccion['amount_sugerida'] ?? $prediccion['cantidad_sugerida'] }}</p>
                                </div>
                            </li>
                        @endforeach  b
                    </ul>
                </div>

                {{-- Recomendaciones del Negocio --}}
                <div
                    class="bg-white dark:bg-zinc-900 p-4 rounded-lg border border-zinc-100 dark:border-zinc-800 space-y-3">
                    <h4 class="text-xs font-bold text-zinc-400 uppercase tracking-wider">
                        {{ __('Estrategias de Optimización') }}</h4>
                    <div class="space-y-2">
                        @foreach($aiReport['recomendaciones_estrategicas'] as $reco)
                            <div>
                                <h5 class="text-sm font-semibold text-purple-700 dark:text-purple-400">{{ $reco['titulo'] }}
                                </h5>
                                <p class="text-xs text-zinc-600 dark:text-zinc-400 mt-0.5">{{ $reco['descripcion'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Bloque 3: Tablas Operativas del Negocio --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Alertas de Stock Crítico (Calculado directo) --}}
            <flux:card class="space-y-4">
                <div>
                    <flux:heading size="md">{{ __('Alertas de Stock Mínimo') }}</flux:heading>
                    <flux:subheading>{{ __('Insumos que requieren ejecución del módulo de Restock.') }}
                    </flux:subheading>
                </div>
                <div class="space-y-2">
                    @forelse($lowStockProducts as $product)
                        <div
                            class="flex justify-between items-center bg-zinc-50 dark:bg-zinc-900 p-3 rounded-lg border border-zinc-200/60 dark:border-zinc-800">
                            <div>
                                <p class="text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $product->name }}</p>
                                <p class="text-xs text-zinc-400">{{ $product->category->name ?? 'Sin Categoría' }}</p>
                            </div>
                            <flux:badge color="red" size="sm">Stock: {{ $product->stock }}</flux:badge>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-400 text-center py-4">
                            {{ __('Inventario estable sin niveles críticos.') }}</p>
                    @endforelse
                </div>
            </flux:card>

            {{-- Ranking de Ventas --}}
            <flux:card class="space-y-4">
                <div>
                    <flux:heading size="md">{{ __('Productos Más Vendidos') }}</flux:heading>
                    <flux:subheading>{{ __('Artículos con mayor demanda en el flujo histórico.') }}</flux:subheading>
                </div>
                <div class="space-y-2">
                    @forelse($topProducts as $index => $item)
                        <div
                            class="flex justify-between items-center bg-zinc-50 dark:bg-zinc-900 p-3 rounded-lg border border-zinc-200/60 dark:border-zinc-800">
                            <div class="flex items-center gap-3">
                                <span
                                    class="text-xs font-bold text-zinc-400 bg-zinc-200/50 dark:bg-zinc-800 p-1 rounded w-6 h-6 flex items-center justify-center">#{{ $index + 1 }}</span>
                                <p class="text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $item->name }}</p>
                            </div>
                            <span class="text-sm font-semibold text-zinc-600 dark:text-zinc-400">{{ $item->total_sold }}
                                uds.</span>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-400 text-center py-4">
                            {{ __('No se registran transacciones completadas.') }}</p>
                    @endforelse
                </div>
            </flux:card>
        </div>

        {{-- Bloque 4: Historial Reciente de Abonos / Pagos --}}
        <flux:card class="space-y-4">
            <div>
                <flux:heading size="md">{{ __('Últimos Pagos y Abonos Recibidos') }}</flux:heading>
                <flux:subheading>{{ __('Seguimiento en tiempo real de entradas físicas y deudas saneadas.') }}
                </flux:subheading>
            </div>
            <div class="divide-y divide-zinc-200 dark:divide-zinc-800">
                @forelse($recentPayments as $payment)
                    <div class="flex justify-between items-center py-3 first:pt-0 last:pb-0">
                        <div>
                            <p class="text-sm font-medium text-zinc-800 dark:text-zinc-200">
                                {{ $payment->order->client->name ?? 'Cliente Desconocido' }}
                            </p>
                            <p class="text-xs text-zinc-400">
                                {{ __('Deuda #') }}{{ $payment->order_id }} • Método: {{ $payment->payment_method }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span
                                class="text-sm font-bold text-green-600 dark:text-green-400">+${{ number_format($payment->amount, 2) }}</span>
                            <p class="text-xs text-zinc-400 mt-0.5">{{ $payment->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-zinc-400 text-center py-4">
                        {{ __('No hay transacciones monetarias registradas.') }}</p>
                @endforelse
            </div>
        </flux:card>

    </div>
</x-layouts::app>