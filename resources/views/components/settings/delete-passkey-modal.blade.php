<flux:modal
    name="delete-passkey-modal"
    class="max-w-md md:min-w-md"
    @close="closeDeleteModal"
    wire:model="showDeleteModal"
>
    <div class="space-y-6">
        <div class="space-y-2">
            <flux:heading size="lg">{{ __('Remove passkey') }}</flux:heading>
            <flux:text>
                {{ __('Are you sure you want to remove the passkey ":name"? You will no longer be able to use it to sign in.', ['name' => $deletingPasskeyName]) }}
            </flux:text>
        </div>

        <div class="flex gap-3 justify-end">
            <flux:button variant="outline" wire:click="closeDeleteModal">
                {{ __('Cancel') }}
            </flux:button>
            <flux:button variant="danger" wire:click="deletePasskey">
                {{ __('Remove passkey') }}
            </flux:button>
        </div>
    </div>
</flux:modal>
