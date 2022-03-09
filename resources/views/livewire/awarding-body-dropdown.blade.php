<x-filter-dropdown wire:model="showLinks">
    <x-slot name="trigger">
        <button wire:click="$set('showLinks', true)"
                class="px-12 py-3 w-full font-sans text-lg font-medium text-left rounded-lg border shadow-sm bg-slate-50 border-slate-100 text-slate-400 hover:shadow-lg">
            <span class="mr-4 fas fa-award"></span>{{ $currentAwardingBody ? $currentAwardingBody->name : 'Awarding Body' }}
        </button>
    </x-slot>

    <x-slot name="content">
        @foreach($awarding_bodies as $awarding_body)
            <x-filter-dropdown-link>
                <x-slot name="wire_click">select({{ $awarding_body }})</x-slot>
                <x-slot name="wire_model">{{ $currentAwardingBody }}</x-slot>

                <x-slot name="content">{{ $awarding_body->name }}</x-slot>
            </x-filter-dropdown-link>
        @endforeach()
    </x-slot>
</x-filter-dropdown>
