{{-- resources/views/components/custom-footer.blade.php --}}

<style>
    /* Oculta el footer por defecto */
    .custom-footer {
        display: none;
    }

    /* A partir de md (≥768px) lo mostramos */
    @media (min-width: 768px) {
        .custom-footer {
            display: block !important;
        }
    }
</style>

<footer class="custom-footer bg-black text-white py-4">
    <div class="container mx-auto flex flex-wrap justify-center items-center gap-6 px-4">
        <a href="https://frspot.com/" class="flex-shrink-0">
            <img src="{{ asset('login-imgs/logo.jpeg') }}" alt="Logo" class="h-16 w-auto" />
        </a>
        <img src="{{ asset('login-imgs/Best.png') }}" alt="Best" class="flex-shrink-0 h-16 w-auto" />
        <img src="{{ asset('login-imgs/Broker.png') }}" alt="Broker" class="flex-shrink-0 h-16 w-auto" />
        <img src="{{ asset('login-imgs/Forex.png') }}" alt="Forex" class="flex-shrink-0 h-16 w-auto" />
        <img src="{{ asset('login-imgs/ultimo.png') }}" alt="Ultimo" class="flex-shrink-0 h-16 w-auto" />
        <img src="{{ asset('login-imgs/Vanuatu.png') }}" alt="Vanuatu" class="flex-shrink-0 h-16 w-auto" />
    </div>
</footer>
