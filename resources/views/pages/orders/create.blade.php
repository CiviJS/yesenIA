<x-layouts::app :title="__('Registrar deuda')">
    <div class="space-y-8 max-w-4xl mx-auto py-6">
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
                <flux:subheading>{{ __('Selecciona el cliente y los productos para crear una deuda.') }}</flux:subheading>
            </div>
            <flux:button :href="route('orders.index')" variant="primary" wire:navigate>
                {{ __('Ir a deudas') }}
            </flux:button>
        </div>

        <flux:card class="p-6 shadow-sm rounded-xl">
            <form action="{{ route('orders.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <flux:select name="client_id" label="{{ __('Cliente') }}" required>
                        <flux:select.option value="">{{ __('Selecciona un cliente') }}</flux:select.option>
                        @foreach ($clients as $client)
                            <flux:select.option value="{{ $client->id }}" :selected="old('client_id') == $client->id">
                                {{ $client->name }} — {{ $client->phone }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>

                    @error('client_id')
                        <flux:description class="text-red-500">{{ $message }}</flux:description>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <flux:select name="items[0][product_id]" label="{{ __('Producto') }}" required>
                            <flux:select.option value="">{{ __('Selecciona un producto') }}</flux:select.option>
                            @foreach ($products as $product)
                                <flux:select.option value="{{ $product->id }}" :selected="old('items.0.product_id') == $product->id">
                                    {{ $product->name }} - quedan: {{ number_format($product->stock, 0, ',', '.') }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>

                        @error('items.0.product_id')
                            <flux:description class="text-red-500">{{ $message }}</flux:description>
                        @enderror
                    </div>

                    <div>
                        <flux:input name="items[0][quantity]" label="{{ __('Cantidad') }}" type="number" min="1" value="{{ old('items.0.quantity', 1) }}" required />

                        @error('items.0.quantity')
                            <flux:description class="text-red-500">{{ $message }}</flux:description>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <flux:button :href="route('orders.index')" variant="ghost" wire:navigate>
                        {{ __('Cancelar') }}
                    </flux:button>
                    <flux:button type="submit" variant="primary">{{ __('Registrar deuda') }}</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>
