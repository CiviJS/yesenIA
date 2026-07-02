<x-layouts::app :title="__('Inventario')">
    <div class="max-w-3xl mx-auto space-y-8 p-4">
        
        <flux:heading size="xl">Inventario de Productos</flux:heading>

        {{-- Formulario (se queda igual) --}}
        <flux:card class="p-5 shadow-sm border-zinc-800">
            <form action="{{ route('products.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input size="md" name="name" label="Nombre del producto" value="{{ old('name') }}" required />
                    <flux:select size="md" label="Categoría" name="product_category_id" placeholder="Selecciona...">
                        @foreach ($categories as $category)
                            <flux:select.option value="{{ $category->id }}" :selected="old('product_category_id') == $category->id">
                                {{ $category->name }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input size="md" name="price" label="Precio" type="number" step="any" value="{{ old('price') }}" required />
                    <flux:input size="md" name="stock" label="Stock Inicial" type="number" value="{{ old('stock') }}" required />
                </div>
                <div class="flex justify-end">
                    <flux:button type="submit" variant="primary" icon="plus">Guardar</flux:button>
                </div>
            </form>
        </flux:card>

        {{-- Tabla Compactada --}}
        <flux:card class="p-0 shadow-sm overflow-hidden border-zinc-800">
            <div class="overflow-x-auto">
                <flux:table size="sm">
                    <flux:table.columns>
                        <flux:table.column class="px-2 py-2">Producto</flux:table.column>
                        <flux:table.column class="px-2 py-2">Cat.</flux:table.column>
                        <flux:table.column class="px-2 py-2">Precio</flux:table.column>
                        <flux:table.column class="px-2 py-2">Stock</flux:table.column>
                        <flux:table.column class="px-2 py-2 text-right">Acción</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @forelse ($products as $product)
                            <flux:table.row>
                                <flux:table.cell class="px-2 py-1 text-xs font-medium">{{ $product->name }}</flux:table.cell>
                                <flux:table.cell class="px-2 py-1 text-xs">
                                    <flux:badge size="xs" color="zinc">{{ $product->category->name ?? 'N/A' }}</flux:badge>
                                </flux:table.cell>
                                <flux:table.cell class="px-2 py-1 text-xs text-zinc-400">${{ number_format($product->price, 0, ',', '.') }}</flux:table.cell>
                                <flux:table.cell class="px-2 py-1 text-xs">
                                    <span class="{{ $product->stock < 5 ? 'text-red-500 font-bold' : 'text-zinc-300' }}">
                                        {{ $product->stock }}
                                    </span>
                                </flux:table.cell>
                                <flux:table.cell class="px-2 py-1 text-right">
                                    <flux:button variant="ghost" size="xs" icon="pencil-square" />
                                </flux:table.cell>
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="5" class="text-center py-4 text-xs text-zinc-500">Sin productos.</flux:table.cell>
                            </flux:table.row>
                        @endforelse
                    </flux:table.rows>
                </flux:table>
            </div>
        </flux:card>
    </div>
</x-layouts::app>