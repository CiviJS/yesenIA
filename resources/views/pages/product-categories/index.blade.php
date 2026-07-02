<x-layouts::app :title="__('Categoria de Productos')">
        @if (session('success'))
        <flux:card class="bg-green-50 dark:bg-green-950/20 border-green-200 dark:border-green-800/50 p-4">
            <div class="flex items-center gap-3 text-green-700 dark:text-green-400">
                <flux:icon name="check-circle" class="w-5 h-5 flex-shrink-0" />
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        </flux:card>
    @endif
    @if ($errors->any())
        <flux:card class="bg-red-50 dark:bg-red-950/20 border-red-200 dark:border-red-800/50 p-4 mb-6">
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
       <flux:card class="p-6 shadow-sm rounded-xl">
            <form action="{{ route('products-category.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <flux:input 
                        name="name" 
                        label="Nombre de la categoria" 
                        value="{{ old('name') }}" 
                        required 
                    />
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <flux:button :href="route('products.index')" variant="ghost" wire:navigate>
                        {{ __('Cancelar') }}
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        {{ __('Guardar') }}
                    </flux:button>
                </div>

            </form>
        </flux:card>
    </div>
    <flux:card class="shadow-sm rounded-xl">
            <flux:table>
                {{-- Corregido para Flux v2: Todo lleva el prefijo table. --}}
                <flux:table.columns>
                    <flux:table.column>{{ __('Nombre') }}</flux:table.column>
                    <flux:table.column align="right">{{ __('Acciones') }}</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($categories as $category)
                        <flux:table.row>
                            <flux:table.cell class="font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $category->name }}
                            </flux:table.cell>
                            <flux:table.cell align="right">
                                <flux:button variant="ghost" icon="pencil" wire:navigate
                                    :href="route('products-category.edit', $category)">
                                    {{ __('Editar') }}
                                </flux:button>
                            </flux:table.cell>
                            <flux:table.cell align="right">
                                <form action="{{ route('products-category.delete', $category) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Yesenia: ¿Seguro que deseas eliminar esta categoria?');">
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
                            <flux:table.cell colspan="4" class="text-center py-10 text-zinc-400 italic">
                                {{ __('No hay categorias registradas.') }}
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

            {{-- Pagination Wrapper --}}
            <div class="pt-6">
                {{ $categories->links() }}
            </div>
        </flux:card>
</x-layouts::app>