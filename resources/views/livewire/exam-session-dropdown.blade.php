<x-filter-dropdown wire:model="showLinks">
    <x-slot name="trigger">
        <button wire:click="$set('showLinks', true)"
                class="px-12 py-3 w-full font-sans text-lg font-medium text-left rounded-lg border shadow-sm bg-slate-50 border-slate-100 text-slate-400 hover:shadow-lg">
            <span class="mr-4 fas fa-calendar-days"></span>{{ $currentExamSession ? $currentExamSession->session->format('F Y') : 'Exam Session' }}
        </button>
    </x-slot>

    <x-slot name="content">
        @foreach($exam_sessions as $session)
            <x-filter-dropdown-link>
                <x-slot name="wire_click">select({{ $session }})</x-slot>
                <x-slot name="wire_model">{{ $currentExamSession }}</x-slot>

                <x-slot name="content">{{ $session->session->format('F Y') }}</x-slot>
            </x-filter-dropdown-link>
        @endforeach()
    </x-slot>
</x-filter-dropdown>
