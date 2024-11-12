<x-filament-panels::page>
    <div class="flex w-fit flex-col items-center gap-2 bg-purple-400">
        <x-filament::avatar src="https://ui-avatars.com/api/?name=Alan+Zapata" alt="Dan Harrin" size="lg" />
        <x-filament::badge icon="heroicon-m-sparkles" color="success">
            Alan Zapata
        </x-filament::badge>
    </div>
    <hr>
    <x-filament::section icon="heroicon-o-user">
        <x-slot name="heading">
            User details
        </x-slot>

        {{-- Content --}}
        <x-slot name="description">
            This is all the information we hold about the user.
        </x-slot>
    </x-filament::section>
</x-filament-panels::page>
