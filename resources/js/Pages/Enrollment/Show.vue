<script setup>
import AuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';

defineProps({
    enrollment: Object,
});
</script>

<template>
    <Head title="Enrollments" />

    <AuthenticatedLayout>
        <div class="grid grid-cols-[1fr,min-content,1fr] gap-x-24">
            <div class="flex flex-col justify-center">
                <span class="text-5xl font-black leading-tight">{{ enrollment.data.course_variant.course.subject.name }}<br /><span class="text-indigo-500">{{ enrollment.data.course_variant.course.tutor.name }}</span></span>

                <p class="mt-8 text-gray-700">
                    You’ll be able to find your course
                    <span class="font-semibold text-indigo-500">enrollments</span>
                    on this page, as well as make any
                    <span class="font-semibold text-indigo-500">transfers</span>
                    or
                    <span class="font-semibold text-indigo-500">withdrawals</span>.
                </p>
            </div>

            <div class="border-l border-gray-100"></div>

            <!--TODO: dynamic data -->
            <div class="flex h-64 w-80 flex-col justify-center rounded bg-gray-900 p-6">
                <span class="font-medium text-indigo-100">One</span>

                <span class="mt-6 text-xl font-bold text-white">Year 10 Biology</span>

                <div class="mt-2 flex items-center justify-between">
                    <span class="text-lg text-gray-100">Dr. John Doe</span>

                    <span class="text-xs font-semibold uppercase text-indigo-500">In Person</span>
                </div>

                <span class="mt-4 text-sm font-light text-gray-500">Sunday 10:30 PM</span>
                <span class="mt-2 text-sm font-light text-gray-500">Wednesday 8:30 PM</span>

                <span class="mt-6 text-xs font-semibold uppercase text-indigo-500">90 Minutes</span>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
