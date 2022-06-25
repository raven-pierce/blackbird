<script setup>
defineProps({
    enrollment: Object,
});
</script>

<template>
    <div class="snap-center shrink-0 flex h-64 w-80 flex-col justify-center rounded bg-gray-900 p-6">
        <div class="flex items-center justify-between text-xs uppercase">
            <span class="font-medium text-indigo-100">{{ enrollment.position }}</span>

            <span class="font-semibold text-gray-500">{{ enrollment.course_variant.course.course_level.name }}</span>
        </div>

        <span class="mt-6 text-xl font-bold text-white">{{ enrollment.course_variant.course.subject.name }}</span>

        <div class="mt-2 flex items-center justify-between">
            <span class="text-lg text-gray-100">{{ enrollment.course_variant.course.tutor.name }}</span>

            <span class="text-xs font-semibold uppercase text-indigo-500">{{ enrollment.course_variant.delivery_method }}</span>
        </div>

        <div class="mt-4 flex flex-col text-sm font-light text-gray-500 space-y-2">
            <span v-for="lecture in enrollment.course_variant.variant_lectures">{{ lecture.day }} {{ lecture.start_time }}</span>
        </div>

        <span class="mt-6 text-xs font-semibold uppercase text-indigo-500">{{ enrollment.course_variant.variant_lectures[0].duration }} Minutes</span>
    </div>
</template>
