<x-layouts::app :title="__('Registrar Venta')">
    <div class="space-y-8 max-w-5xl mx-auto py-6">
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

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <flux:heading size="xl" level="1">{{ __('Registrar venta') }}</flux:heading>
                <flux:subheading>{{ __('Selecciona el producto y la cantidad para completar la venta.') }}
                </flux:subheading>
            </div>
            <flux:button :href="route('sales.index')" variant="primary" wire:navigate>
                {{ __('Ir a ventas') }}
            </flux:button>
        </div>

        <flux:card class="p-6 shadow-sm rounded-xl !overflow-visible">
            <form action="{{ route('sales.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start !overflow-visible">

                    <div x-data="{ 
                        open: false, 
                        search: '', 
                        selectedId: '{{ old('items.0.product_id') }}', 
                        selectedName: '{{ old('items.0.product_id') ? ($products->firstWhere('id', old('items.0.product_id'))?->name ?? '') : '' }}',
                        init() {
                            if(this.selectedId) {
                                let option = this.$refs.productOptions.querySelector(`[data-id='${this.selectedId}']`);
                                if(option) this.selectedName = option.getAttribute('data-name');
                            }
                        },
                        filterOptions() {
                            let items = this.$refs.productOptions.querySelectorAll('.product-item');
                            items.forEach(item => {
                                let text = item.getAttribute('data-name').toLowerCase();
                                item.style.display = text.includes(this.search.toLowerCase()) ? '' : 'none';
                            });
                        }
                    }" @click.outside="open = false" class="relative space-y-2 flex flex-col z-30">

                        <flux:label>{{ __('Producto') }}</flux:label>
                        <input type="hidden" name="items[0][product_id]" :value="selectedId" required>

                        <div @click="open = !open"
                            class="flex items-center justify-between w-full px-3 py-2 text-sm border rounded-lg shadow-sm cursor-pointer border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <span x-text="selectedName || '{{ __('Selecciona un producto...') }}'"
                                :class="selectedName ? 'text-zinc-900 dark:text-zinc-100' : 'text-zinc-400'"></span>
                            <span class="text-zinc-400 text-xs">▼</span>
                        </div>

                        <div x-show="open" x-transition
                            class="absolute top-full left-0 w-full mt-1 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg shadow-2xl max-h-60 overflow-hidden flex flex-col z-50">

                            <div
                                class="p-2 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950/50">
                                <input x-model="search" @input="filterOptions()" type="text"
                                    placeholder="Escribe para filtrar..."
                                    class="w-full px-3 py-1.5 text-sm rounded-md border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            </div>

                            <div x-ref="productOptions"
                                class="overflow-y-auto divide-y divide-zinc-100 dark:divide-zinc-800/50">
                                @foreach ($products as $product)
                                    <div @click="selectedId = '{{ $product->id }}'; selectedName = '{{ $product->name }}'; open = false; search = ''"
                                        data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                        class="product-item px-4 py-2.5 text-sm hover:bg-zinc-100 dark:hover:bg-zinc-800 cursor-pointer text-zinc-700 dark:text-zinc-300 transition-colors flex justify-between items-center">
                                        <span>{{ $product->name }}</span>
                                        <span
                                            class="text-xs px-2 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-500">Quedan:
                                            {{ number_format($product->stock, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @error('items.0.product_id')
                            <flux:description class="text-red-500">{{ $message }}</flux:description>
                        @enderror
                    </div>

                    <div class="flex flex-col space-y-2 relative z-20">
                        <flux:label>{{ __('Cantidad') }}</flux:label>
                        <flux:input name="items[0][quantity]" type="number" min="1"
                            value="{{ old('items.0.quantity', 1) }}" required class="w-full" />

                        @error('items.0.quantity')
                            <flux:description class="text-red-500">{{ $message }}</flux:description>
                        @enderror
                    </div>

                </div>

                <div
                    class="flex flex-col gap-3 sm:flex-row sm:justify-end pt-6 border-t border-zinc-100 dark:border-zinc-800">
                    <flux:button :href="route('sales.index')" variant="ghost" wire:navigate>
                        {{ __('Cancelar') }}
                    </flux:button>
                    <flux:button type="submit" variant="primary">{{ __('Registrar venta') }}</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>