<x-layouts::app :title="__('Registrar deuda')">
    <div class="space-y-8 max-w-5xl mx-auto py-6"
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
                <flux:heading size="xl" level="1">{{ __('Registrar deuda') }}</flux:heading>
                <flux:subheading>{{ __('Selecciona el cliente y los productos para crear una deuda.') }}
                </flux:subheading>
            </div>
            <flux:button :href="route('orders.index')" variant="primary" wire:navigate>
                {{ __('Ir a deudas') }}
            </flux:button>
        </div>

        <flux:card class="p-6 shadow-sm rounded-xl !overflow-visible">
            <form action="{{ route('orders.store') }}" method="POST" class="space-y-8">
                @csrf

                <!-- SECCIÓN DEL CLIENTE (Estructura intacta) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start !overflow-visible">
                    <div x-data="{ 
                        open: false, 
                        search: '', 
                        selectedId: '{{ old('client_id') }}', 
                        selectedName: '{{ old('client_id') ? ($clients->firstWhere('id', old('client_id'))?->name ?? '') : '' }}',
                        init() {
                            if(this.selectedId) {
                                let option = this.$refs.clientOptions.querySelector(`[data-id='${this.selectedId}']`);
                                if(option) this.selectedName = option.getAttribute('data-name');
                            }
                        },
                        filterOptions() {
                            let items = this.$refs.clientOptions.querySelectorAll('.client-item');
                            items.forEach(item => {
                                let text = item.getAttribute('data-name').toLowerCase();
                                item.style.display = text.includes(this.search.toLowerCase()) ? '' : 'none';
                            });
                        }
                    }"
                    @click.outside="open = false"
                    class="relative space-y-2 flex flex-col z-50">
                        
                        <flux:label>{{ __('Cliente') }}</flux:label>
                        <input type="hidden" name="client_id" :value="selectedId" required>

                        <div @click="open = !open" class="flex items-center justify-between w-full px-3 py-2 text-sm border rounded-lg shadow-sm cursor-pointer border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <span x-text="selectedName || '{{ __('Selecciona un cliente...') }}'" :class="selectedName ? 'text-zinc-900 dark:text-zinc-100' : 'text-zinc-400'"></span>
                            <span class="text-zinc-400 text-xs">▼</span>
                        </div>

                        <div x-show="open" 
                             x-transition
                             class="absolute top-full left-0 w-full mt-1 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg shadow-2xl max-h-60 overflow-hidden flex flex-col z-[100]">
                            <div class="p-2 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950/50">
                                <input x-model="search" @input="filterOptions()" type="text" placeholder=" Escribe para filtrar..." class="w-full px-3 py-1.5 text-sm rounded-md border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            </div>
                            <div x-ref="clientOptions" class="overflow-y-auto divide-y divide-zinc-100 dark:divide-zinc-800/50">
                                @foreach ($clients as $client)
                                    <div @click="selectedId = '{{ $client->id }}'; selectedName = '{{ $client->name }}'; open = false; search = ''" 
                                         data-id="{{ $client->id }}" 
                                         data-name="{{ $client->name }}"
                                         class="client-item px-4 py-2.5 text-sm hover:bg-zinc-100 dark:hover:bg-zinc-800 cursor-pointer text-zinc-700 dark:text-zinc-300 transition-colors flex justify-between items-center">
                                        <span>{{ $client->name }}</span>
                                        <span class="text-xs text-zinc-400">{{ $client->phone }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN DE PRODUCTOS DINÁMICOS -->
                <div class="space-y-6 pt-6 border-t border-zinc-100 dark:border-zinc-800 !overflow-visible">
                    <template x-for="(row, index) in rows" :key="row.id">
                        <!-- Añadimos z-index dinámico para que los dropdowns no se superpongan mal -->
                        <div :style="'z-index: ' + (40 - index)" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start relative pb-6 border-b border-zinc-100 dark:border-zinc-800/50 last:border-0 last:pb-0 !overflow-visible">
                            
                            <!-- PRODUCTO (Estructura intacta) -->
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
                            }" 
                            @click.outside="open = false" 
                            class="relative space-y-2 col-span-1 md:col-span-7 flex flex-col">
                                
                                <flux:label>{{ __('Producto') }}</flux:label>
                                <!-- Nombre dinámico bindeado -->
                                <input type="hidden" x-bind:name="'items[' + index + '][product_id]'" x-model="selectedId" required>

                                <div @click="open = !open" class="flex items-center justify-between w-full px-3 py-2 text-sm border rounded-lg shadow-sm cursor-pointer border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <span x-text="selectedName || '{{ __('Selecciona un producto...') }}'" :class="selectedName ? 'text-zinc-900 dark:text-zinc-100' : 'text-zinc-400'"></span>
                                    <span class="text-zinc-400 text-xs">▼</span>
                                </div>

                                <div x-show="open" 
                                     x-transition
                                     class="absolute top-full left-0 w-full mt-1 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg shadow-2xl max-h-60 overflow-hidden flex flex-col z-[100]">
                                    <div class="p-2 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950/50">
                                        <input x-model="search" @input="filterOptions()" type="text" placeholder=" Escribe para filtrar..." class="w-full px-3 py-1.5 text-sm rounded-md border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                    </div>
                                    <div x-ref="productOptions" class="overflow-y-auto divide-y divide-zinc-100 dark:divide-zinc-800/50">
                                        @foreach ($products as $product)
                                            <div @click="selectedId = '{{ $product->id }}'; selectedName = '{{ $product->name }}'; open = false; search = ''" 
                                                 data-id="{{ $product->id }}" 
                                                 data-name="{{ $product->name }}"
                                                 class="product-item px-4 py-2.5 text-sm hover:bg-zinc-100 dark:hover:bg-zinc-800 cursor-pointer text-zinc-700 dark:text-zinc-300 transition-colors flex justify-between items-center">
                                                <span>{{ $product->name }}</span>
                                                <span class="text-xs px-2 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-500">Quedan: {{ number_format($product->stock, 0, ',', '.') }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- CANTIDAD -->
                            <div class="flex flex-col space-y-2 col-span-1 md:col-span-3">
                                <flux:label>{{ __('Cantidad') }}</flux:label>
                                <!-- Nombre dinámico bindeado -->
                                <flux:input x-bind:name="'items[' + index + '][quantity]'" x-model="row.quantity" type="number" min="1" required class="w-full" />
                            </div>

                            <!-- BOTÓN ELIMINAR FILA -->
                            <div class="col-span-1 md:col-span-2 flex items-end justify-start md:justify-end pb-1 md:pb-2" x-show="rows.length > 1">
                                <button type="button" @click="removeRow(row.id)" class="text-sm font-medium text-red-500 hover:text-red-400 transition-colors">
                                    {{ __('Eliminar') }}
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
                        <flux:button :href="route('orders.index')" variant="ghost" wire:navigate>
                            {{ __('Cancelar') }}
                        </flux:button>
                        <flux:button type="submit" variant="primary">{{ __('Registrar deuda') }}</flux:button>
                    </div>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>