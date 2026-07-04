@props([
    'canManageTwoFactor' => false,
    'twoFactorEnabled' => false,
    'requiresConfirmation' => false,
])

@if ($canManageTwoFactor)
    <section class="mt-12">
        <flux:heading>{{ __('Two-factor authentication') }}</flux:heading>
        <flux:subheading>{{ __('Manage your two-factor authentication settings') }}</flux:subheading>

        <div class="flex flex-col w-full mx-auto space-y-6 text-sm" wire:cloak>
            @if ($twoFactorEnabled)
                <div class="space-y-4">
                    <flux:text>
                        {{ __('You will be prompted for a secure, random pin during login, which you can retrieve from the TOTP-supported application on your phone.') }}
                    </flux:text>

                    <div class="flex justify-start">
                        <flux:button variant="danger" wire:click="disable">
                            {{ __('Disable 2FA') }}
                        </flux:button>
                    </div>

                    <livewire:pages::settings.two-factor.recovery-codes :$requiresConfirmation />
                </div>
            @else
                <div class="space-y-4">
                    <flux:text variant="subtle">
                        {{ __('When you enable two-factor authentication, you will be prompted for a secure pin during login. This pin can be retrieved from a TOTP-supported application on your phone.') }}
                    </flux:text>

                    <flux:modal.trigger name="two-factor-setup-modal">
                        <flux:button variant="primary" wire:click="$dispatch('start-two-factor-setup')">
                            {{ __('Enable 2FA') }}
                        </flux:button>
                    </flux:modal.trigger>

                    <livewire:pages::settings.two-factor-setup-modal :requires-confirmation="$requiresConfirmation" />
                </div>
            @endif
        </div>
    </section>
@endif
