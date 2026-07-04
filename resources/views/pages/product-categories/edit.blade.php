<x-layouts::app :title="__('Editar categoría de productos')">
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
            <flux:heading size="xl" level="1">{{ __('Editar categoría') }}</flux:heading>
            <flux:subheading>{{ __('Ajusta el nombre de la categoría de productos.') }}</flux:subheading>
        </div>

        <flux:card class="p-6 shadow-sm rounded-xl">
            <form action="{{ route('products-category.update', $productCategory) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <flux:input
                    name="name"
                    label="{{ __('Nombre de la categoría') }}"
                    value="{{ old('name', $productCategory->name) }}"
                    required
                />

                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <flux:button :href="route('products-category.index')" variant="ghost" wire:navigate>
                        {{ __('Cancelar') }}
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        {{ __('Guardar categoría') }}
                    </flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>