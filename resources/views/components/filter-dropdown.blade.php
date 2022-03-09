<div x-data="{ show: @entangle($attributes->wire('model')) }" class="relative" @click.away="show = false">
    {{ $trigger }}

    <div x-show="show" style="display: none" class="flex absolute flex-col mt-4 w-full font-sans text-base font-medium rounded-lg shadow-sm border border-slate-100 text-slate-700 overflow-hidden z-50">
        {{ $content }}
    </div>
</div>
