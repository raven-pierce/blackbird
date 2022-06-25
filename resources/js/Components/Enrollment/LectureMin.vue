<script setup>
defineProps({
    lecture: Object,
});
</script>

<template>
    <div class="snap-center shrink-0 flex h-40 w-80 flex-col justify-center rounded bg-gray-900 p-6">
        <span class="text-xs font-medium text-indigo-100 uppercase">One</span>

        <div class="flex items-center justify-between mt-6">
            <span class="text-xl font-bold text-white">{{ lecture.day }}</span>

            <span class="text-xs font-semibold uppercase text-indigo-500">In Person</span>
        </div>

        <div class="mt-2 flex items-center justify-between">
            <span class="text-lg text-gray-100">{{ lecture.start_time }}</span>

            <span class="text-xs font-semibold uppercase text-indigo-500">{{ lecture.duration }} Minutes</span>
        </div>
    </div>
</template>
