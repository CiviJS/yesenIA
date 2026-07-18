<x-layouts::app :title="__('edicion de producto')">
        <div class="space-y-8 max-w-2xl mx-auto py-6">
     
            <div>
                <flux:heading size="xl" level="1">{{ __('Editar Producto') }}</flux:heading>
                <flux:subheading>{{ __('Modifica los datos y guarda los cambios.') }}</flux:subheading>
            </div>

            <flux:card class="p-6 shadow-sm rounded-xl">
                <form action="{{ route('products.update', $product) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <flux:field>
                        <flux:label>{{ __('Nombre') }}</flux:label>
                        <flux:input name="name" value="{{ old('name', $product->name) }}" />
                        @error('name') <flux:description class="text-red-500">{{ $message }}</flux:description>
                        @enderror
                    </flux:field>

                    <flux:field>
                        <flux:label>{{ __('Categoría') }}</flux:label>
                        <flux:select name="product_category_id"
                            value="{{ old('product_category_id', $product->product_category_id) }}">
                            <option value="">{{ __('Seleccionar categoría') }}</option>
                            @foreach ($productCategories as $category)
                                <option value="{{ $category->id }}" {{ old('product_category_id', $product->product_category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </flux:select>
                        @error('product_category_id') <flux:description class="text-red-500">{{ $message }}
                        </flux:description> @enderror
                    </flux:field>

                    <flux:field>
                        <flux:label>{{ __('precio') }}</flux:label>
                        <flux:input name="price" value="{{ old('price', $product->price) }}" />
                        @error('price') <flux:description class="text-red-500">{{ $message }}</flux:description>
                        @enderror
                    </flux:field>
                    <flux:field>
                        <flux:label>{{ __('stock') }}</flux:label>
                        <flux:input name="stock" value="{{ old('stock', $product->stock) }}" />
                        @error('stock') <flux:description class="text-red-500">{{ $message }}</flux:description>
                        @enderror
                    </flux:field>

                    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                        <flux:button href="{{ route('products.index') }}" variant="ghost" wire:navigate>
                            {{ __('Cancelar') }}
                        </flux:button>
                        <flux:button type="submit" variant="primary">{{ __('Guardar Cambios') }}</flux:button>
                    </div>
                </form>
            </flux:card>
        </div>


</x-layouts::app>