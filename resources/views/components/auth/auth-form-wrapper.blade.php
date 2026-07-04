<div class="flex flex-col gap-6">
    <x-auth-header :title="$title" :description="$description" />

    <x-auth-session-status class="text-center" :status="session('status')" />

    {{ $slot }}
</div>
