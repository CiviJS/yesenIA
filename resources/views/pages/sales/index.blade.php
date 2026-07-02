<x-layouts::app :title="__('Ventas')">
        <flux:button variant="primary" icon="plus" wire:navigate :href="route('sales.create')">
                {{ __('Nueva venta') }}
            </flux:button>
</x-app::layout>