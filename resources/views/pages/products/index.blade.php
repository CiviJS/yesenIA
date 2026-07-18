<x-layouts::app :title="__('Inventario')">
    <div class="space-y-8 max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

      
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <flux:heading size="xl" level="1">{{ __('Inventario de Productos') }}</flux:heading>
                <flux:subheading>{{ __('Agrega y administra tus productos desde un panel moderno.') }}</flux:subheading>
            </div>

            <div class="flex flex-wrap items-center gap-2 sm:justify-end">
                <flux:button variant="primary" icon="plus" wire:navigate :href="route('products-category.index')">
                    {{ __('Nueva categoria') }}
                </flux:button>

                <flux:button variant="ghost" wire:navigate :href="route('products.index')">
                    {{ __('Actualizar lista') }}
                </flux:button>
            </div>
        </div>

        <flux:card class="p-6 shadow-sm rounded-xl">
            <form action="{{ route('products.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <flux:input size="md" name="name" label="{{ __('Nombre del producto') }}" value="{{ old('name') }}"
                        required />

                    <flux:select size="md" label="{{ __('Categoría') }}" name="product_category_id"
                        placeholder="{{ __('Selecciona...') }}" required>
                        <flux:select.option value="">{{ __('Selecciona una categoría') }}</flux:select.option>
                        @foreach ($categories as $category)
                            <flux:select.option value="{{ $category->id }}"
                                :selected="old('product_category_id') == $category->id">
                                {{ $category->name }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <flux:input size="md" name="price" label="{{ __('Precio') }}" type="number" step="any"
                        value="{{ old('price') }}" required />
                    <flux:input size="md" name="stock" label="{{ __('Stock Inicial') }}" type="number"
                        value="{{ old('stock') }}" required />
                </div>

                <div class="flex justify-end">
                    <flux:button type="submit" variant="primary" icon="plus">{{ __('Guardar producto') }}</flux:button>
                </div>
            </form>
        </flux:card>

        <flux:card class="overflow-hidden shadow-sm rounded-xl">

            {{-- MODO ESCRITORIO: TABLA --}}
            <div class="hidden md:block overflow-x-auto">
                <flux:table size="sm">
                    <flux:table.columns>
                        <flux:table.column>{{ __('Producto') }}</flux:table.column>
                        <flux:table.column>{{ __('Categoría') }}</flux:table.column>
                        <flux:table.column>{{ __('Precio') }}</flux:table.column>
                        <flux:table.column>{{ __('Stock') }}</flux:table.column>
                        <flux:table.column class="text-right">{{ __('Acción') }}</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @forelse ($products as $product)
                            <flux:table.row>
                                <flux:table.cell>{{ $product->name }}</flux:table.cell>
                                <flux:table.cell>
                                    <flux:badge size="xs" color="zinc">{{ $product->category->name ?? __('N/A') }}
                                        </flux:badge>
                                </flux:table.cell>
                                <flux:table.cell>${{ number_format($product->price, 0, ',', '.') }}</flux:table.cell>
                                <flux:table.cell>
                                    <span class="{{ $product->stock < 5 ? 'text-red-500 font-bold' : 'text-zinc-400' }}">
                                        {{ $product->stock }}
                                    </span>
                                </flux:table.cell>
                                <flux:table.cell class="text-right">

                                    <flux:button variant="ghost" icon="pencil" wire:navigate
                                        :href="route('products.edit', $product)">
                                        {{ __('Editar') }}
                                    </flux:button>
                                    <form action="{{ route('products.delete', $product) }}" method="POST" class="inline"
                                        onsubmit="return confirm('YesenIA:¿Seguro que deseas eliminar este producto?');">
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
                                <flux:table.cell colspan="5" class="text-center">{{ __('Sin productos.') }}
                                </flux:table.cell>
                            </flux:table.row>
                        @endforelse
                    </flux:table.rows>
                </flux:table>
            </div>

            {{-- MODO MÓVIL: CARTAS --}}
            <div class="md:hidden divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($products as $product)
                    <div class="p-4 space-y-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-zinc-900 dark:text-white">{{ $product->name }}</p>
                                <flux:badge size="xs" color="zinc" class="mt-1">{{ $product->category->name ?? __('N/A') }}
                                </flux:badge>
                            </div>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-zinc-500">{{ __('Precio') }}: <span
                                    class="text-zinc-900 dark:text-zinc-200 font-semibold">${{ number_format($product->price, 0, ',', '.') }}</span></span>
                            <span class="text-zinc-500">{{ __('Stock') }}: <span
                                    class="{{ $product->stock < 5 ? 'text-red-500 font-bold' : 'text-zinc-900 dark:text-zinc-200 font-semibold' }}">{{ $product->stock }}</span></span>
                        </div>
                        <div class="flex justify-end gap-2 pt-2">
                            <flux:button variant="ghost" icon="pencil" wire:navigate
                                :href="route('products.edit', $product)">
                                {{ __('Editar') }}
                            </flux:button>
                            <form action="{{ route('products.delete', $product) }}" method="POST" class="inline"
                                onsubmit="return confirm('YesenIA:¿Seguro que deseas eliminar este producto?');">
                                @csrf
                                @method('DELETE')
                                <flux:button type="submit" variant="ghost" icon="trash"
                                    class="text-red-500 hover:text-red-600">
                                    {{ __('Eliminar') }}
                                </flux:button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="p-4 text-center text-sm text-zinc-500">{{ __('Sin productos.') }}</p>
                @endforelse
            </div>

            <div class="p-4 border-t border-zinc-200 dark:border-zinc-700">
                {{ $products->links() }}
            </div>

        </flux:card>
    </div>
</x-layouts::app>