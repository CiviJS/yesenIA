<x-layouts::app :title="__('Categorías de Productos')">
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
                <flux:heading size="xl" level="1">{{ __('Categorías de Productos') }}</flux:heading>
                <flux:subheading>{{ __('Agrega nuevas categorías y administra las existentes con facilidad.') }}</flux:subheading>
            </div>

            <flux:button variant="ghost" wire:navigate :href="route('products.index')">
                {{ __('Ir a productos') }}
            </flux:button>
        </div>

        <flux:card class="p-6 shadow-sm rounded-xl">
            <form action="{{ route('products-category.store') }}" method="POST" class="space-y-6">
                @csrf

                <flux:input
                    name="name"
                    label="{{ __('Nombre de la categoría') }}"
                    value="{{ old('name') }}"
                    required
                />

                <div class="flex justify-end gap-3 pt-4">
                    <flux:button :href="route('products-category.index')" variant="ghost" wire:navigate>
                        {{ __('Cancelar') }}
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        {{ __('Guardar categoría') }}
                    </flux:button>
                </div>
            </form>
        </flux:card>

        <flux:card class="overflow-hidden shadow-sm rounded-xl">
            <flux:table>
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
                            <flux:table.cell align="right" class="space-x-2 whitespace-nowrap">
                                <flux:button variant="ghost" icon="pencil" wire:navigate
                                    :href="route('products-category.edit', $category)">
                                    {{ __('Editar') }}
                                </flux:button>

                                <form action="{{ route('products-category.delete', $category) }}" method="POST" class="inline"
                                    onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?');">
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
                            <flux:table.cell colspan="2" class="text-center py-10 text-zinc-400 italic">
                                {{ __('No hay categorías registradas.') }}
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

            <div class="p-4 border-t border-zinc-200 dark:border-zinc-700">
                {{ $categories->links() }}
            </div>
        </flux:card>
    </div>
</x-layouts::app>