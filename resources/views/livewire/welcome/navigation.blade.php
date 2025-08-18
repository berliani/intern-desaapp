<div class="flex items-center justify-end sm:justify-end">
    @auth
        <a href="{{ route('dashboard') }}" wire:navigate class="font-semibold text-emerald-600 hover:text-emerald-700 mr-4">Dashboard</a>
    @else
        <a href="{{ route('login') }}" wire:navigate class="font-semibold text-emerald-600 hover:text-emerald-700 mr-4">Log in</a>

        @if (Route::has('register'))
            <a href="{{ route('register') }}" wire:navigate class="font-semibold text-emerald-600 hover:text-emerald-700">Register</a>
        @endif
    @endauth
</div>
