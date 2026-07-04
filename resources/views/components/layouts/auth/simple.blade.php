<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800 flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="rounded-3xl border border-zinc-200 bg-white/90 p-8 shadow-xl shadow-zinc-900/5 backdrop-blur-xl dark:border-zinc-700 dark:bg-zinc-950/80">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
