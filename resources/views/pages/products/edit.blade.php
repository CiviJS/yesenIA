<x-layouts::app :title="__('edicion de producto')">
        <div class="space-y-8 max-w-2xl mx-auto py-6">
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