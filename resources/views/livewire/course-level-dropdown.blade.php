<x-filter-dropdown wire:model="showLinks">
    <x-slot name="trigger">
        <button wire:click="$set('showLinks', true)"
                class="px-12 py-3 w-full font-sans text-lg font-medium text-left rounded-lg border shadow-sm bg-slate-50 border-slate-100 text-slate-400 hover:shadow-lg">
            <span class="mr-4 fas fa-layer-group"></span>{{ $currentCourseLevel ? $currentCourseLevel->name : 'Course Level' }}
        </button>
    </x-slot>

    <x-slot name="content">
        @foreach($course_levels as $level)
            <x-filter-dropdown-link>
                <x-slot name="wire_click">select({{ $level }})</x-slot>
                <x-slot name="wire_model">{{ $currentCourseLevel }}</x-slot>

                <x-slot name="content">{{ $level->name }}</x-slot>
            </x-filter-dropdown-link>
        @endforeach()
    </x-slot>
</x-filter-dropdown>
