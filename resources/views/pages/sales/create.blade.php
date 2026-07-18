<x-layouts::app :title="__('Registrar Venta')">
    <div class="space-y-8 max-w-5xl mx-auto py-6">
        
        <!-- Mensajes de éxito -->
        @if (session('success'))
            <flux:card class="bg-green-50 dark:bg-green-950/20 border-green-200 dark:border-green-800/50 p-4">
                <div class="flex items-center gap-3 text-green-700 dark:text-green-400">
                    <flux:icon name="check-circle" class="w-5 h-5 flex-shrink-0" />
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </flux:card>
        @endif

        <!-- Mensajes de error (Globales) -->
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

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <flux:heading size="xl" level="1">{{ __('Registrar venta') }}</flux:heading>
                <flux:subheading>{{ __('Selecciona los productos y las cantidades para completar la venta.') }}
                </flux:subheading>
            </div>
            <flux:button :href="route('sales.index')" variant="primary" wire:navigate>
                {{ __('Ir a ventas') }}
            </flux:button>
        </div>

        <flux:card class="p-6 shadow-sm rounded-xl !overflow-visible">
            <form action="{{ route('sales.store') }}" method="POST" class="space-y-6"
                x-data="{
                    rows: {{ Js::from(old('items', [['product_id' => '', 'quantity' => 1]])) }}.map((item, i) => ({ id: Date.now() + i, product_id: item.product_id, quantity: item.quantity })),
                    addRow() {
                        this.rows.push({ id: Date.now(), product_id: '', quantity: 1 });
                    },
                    removeRow(id) {
                        if (this.rows.length > 1) {
                            this.rows = this.rows.filter(row => row.id !== id);
                        }
                    }
                }">
                @csrf

                <div class="space-y-8 !overflow-visible">
                    <template x-for="(row, index) in rows" :key="row.id">
                        
                        <div :style="'z-index: ' + (50 - index)" class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start !overflow-visible relative pb-8 border-b border-zinc-100 dark:border-zinc-800/50 last:border-0 last:pb-0">
                            
                            <!-- SELECTOR DE PRODUCTO -->
                            <div x-data="{ 
                                open: false, 
                                search: '', 
                                selectedId: row.product_id, 
                                selectedName: '', 
                                init() {
                                    this.$watch('selectedId', value => row.product_id = value);
                                    
                                    this.$nextTick(() => {
                                        if(this.selectedId) {
                                            let option = this.$refs.productOptions.querySelector(`[data-id='${this.selectedId}']`);
                                            if(option) this.selectedName = option.getAttribute('data-name');
                                        }
                                    });
                                },
                                filterOptions() {
                                    let items = this.$refs.productOptions.querySelectorAll('.product-item');
                                    items.forEach(item => {
                                        let text = item.getAttribute('data-name').toLowerCase();
                                        item.style.display = text.includes(this.search.toLowerCase()) ? '' : 'none';
                                    });
                                }
                            }" @click.outside="open = false" class="relative space-y-2 flex flex-col">

                                <flux:label>{{ __('Producto') }}</flux:label>
                                
                                <!-- Usamos x-bind:name para evitar conflictos con Blade -->
                                <input type="hidden" x-bind:name="'items[' + index + '][product_id]'" x-model="selectedId" required>

                                <div @click="open = !open"
                                    class="flex items-center justify-between w-full px-3 py-2 text-sm border rounded-lg shadow-sm cursor-pointer border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <span x-text="selectedName || '{{ __('Selecciona un producto...') }}'"
                                        :class="selectedName ? 'text-zinc-900 dark:text-zinc-100' : 'text-zinc-400'"></span>
                                    <span class="text-zinc-400 text-xs">▼</span>
                                </div>

                                <div x-show="open" x-transition x-cloak
                                    class="absolute top-full left-0 w-full mt-1 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg shadow-2xl max-h-60 overflow-hidden flex flex-col z-50">

                                    <div class="p-2 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950/50">
                                        <input x-model="search" @input="filterOptions()" type="text"
                                            placeholder="Escribe para filtrar..."
                                            class="w-full px-3 py-1.5 text-sm rounded-md border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                    </div>

                                    <div x-ref="productOptions" class="overflow-y-auto divide-y divide-zinc-100 dark:divide-zinc-800/50">
                                        @foreach ($products as $product)
                                            <div @click="selectedId = '{{ $product->id }}'; selectedName = '{{ addslashes($product->name) }}'; open = false; search = ''"
                                                data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                                class="product-item px-4 py-2.5 text-sm hover:bg-zinc-100 dark:hover:bg-zinc-800 cursor-pointer text-zinc-700 dark:text-zinc-300 transition-colors flex justify-between items-center">
                                                <span>{{ $product->name }}</span>
                                                <span class="text-xs px-2 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-500">
                                                    Quedan: {{ number_format($product->stock, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- CANTIDAD -->
                            <div class="flex flex-col space-y-2 relative">
                                <flux:label>{{ __('Cantidad') }}</flux:label>
                                <!-- Usamos x-bind:name para que Blade no intente procesarlo -->
                                <flux:input x-bind:name="'items[' + index + '][quantity]'" type="number" min="1" x-model="row.quantity" required class="w-full" />
                            </div>

                            <!-- BOTÓN ELIMINAR FILA -->
                            <div class="md:col-span-2 flex justify-end" x-show="rows.length > 1">
                                <button type="button" @click="removeRow(row.id)" class="text-sm font-medium text-red-500 hover:text-red-400 transition-colors">
                                    &times; {{ __('Eliminar producto') }}
                                </button>
                            </div>

                        </div>
                    </template>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:justify-between pt-6 border-t border-zinc-100 dark:border-zinc-800">
                    <flux:button type="button" variant="outline" @click="addRow()">
                        + {{ __('Agregar otro producto') }}
                    </flux:button>
                    
                    <div class="flex gap-3 justify-end">
                        <flux:button :href="route('sales.index')" variant="ghost" wire:navigate>
                            {{ __('Cancelar') }}
                        </flux:button>
                        <flux:button type="submit" variant="primary">{{ __('Registrar venta') }}</flux:button>
                    </div>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>