{{-- resources/views/filament/login-extra.blade.php --}}
@vite('resources/css/filament/client/login.css')

<header class="bg-black text-white text-lg max-h-14 px-6 flex items-center">
    <a href="https://frspot.com/" class="mr-4">
        <img src="{{ asset('login-imgs/logo.jpeg') }}" alt="Logo" style="height: 80px">
    </a>
    <h1 class="text-xl font-semibold">
        Bienvenido al Portal de Clientes

        @php
            $user = filament()->auth()->user();
        @endphp

        @if ($user)
            <span class="ml-2 text-lg text-gray-300 uppercase">{{ $user->name }}</span>
        @endif
    </h1>
</header>
