{{-- resources/views/components/custom-header.blade.php --}}

<style>
    /* Base (móvil): imagen y texto pequeños */
    .custom-header {
        display: flex;
        align-items: center;
        background: #000;
        color: #fff;
        padding: 0.5rem 1rem;
        height: auto;
    }

    .custom-header__logo {
        height: 32px;
        flex-shrink: 0;
        margin-right: 0.75rem;
    }

    .custom-header__title {
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        flex: 1;
    }

    /* A partir de 768px (md) */
    @media (min-width: 768px) {
        .custom-header {
            padding: 0.75rem 1.5rem;
            height: 80px;
        }

        .custom-header__logo {
            height: 64px;
        }

        .custom-header__title {
            font-size: 20px;
        }
    }
</style>

<header class="custom-header">
    <a href="https://frspot.com/" class="custom-header__logo-wrapper">
        <img src="{{ asset('login-imgs/logo.jpeg') }}" alt="Logo" class="custom-header__logo" />
    </a>

    <h1 class="custom-header__title">
        Bienvenido
        @php $user = filament()->auth()->user(); @endphp
        @if ($user)
            &nbsp;<span>{{ $user->name }}</span>
        @endif
    </h1>
</header>
