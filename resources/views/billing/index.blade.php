<x-app-layout>
    <x-slot name="header">
        <x-links.primary href="{{ route('receipts') }}">
            View Receipts
            <x-assets.icons.chevron-right class="h-5 w-5" />
        </x-links.primary>

        <span class="mt-4">Billing</span>
    </x-slot>

    <x-slot name="description">
        <p class="mt-8 text-gray-700">
            You’ll be able to view your <span class="font-semibold text-indigo-500">past receipts</span> on this page, as
            well as pay any <span class="font-semibold text-indigo-500">balances owing</span> for classes.
        </p>
    </x-slot>

    <div class="mt-16 grid grid-cols-[repeat(auto-fill,minmax(20rem,1fr))] gap-16">
        @foreach ($enrollments as $enrollment)
            <x-billing.unpaid-lectures-card :enrollment="$enrollment" :spellOutFormatter="$spellOutFormatter" :currencyFormatter="$currencyFormatter" :loop="$loop" />
        @endforeach
    </div>
</x-app-layout>
