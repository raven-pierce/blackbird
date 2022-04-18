<div x-data="{ open: false }" @click="open = ! open" @click.away="open = false"
    class="relative flex w-full flex-col rounded bg-gray-100 p-4">
    <div class="flex items-center justify-between">
        {{ $trigger }}
    </div>

    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95" style="display: none;"
        class="absolute z-50 -m-4 mt-4 flex w-full flex-col space-y-2 rounded bg-gray-100 p-4 lg:mt-8 2xl:space-y-4">
        {{ $content }}
    </div>
</div>
