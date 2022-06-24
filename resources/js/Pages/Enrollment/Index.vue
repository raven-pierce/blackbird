<script setup>
import AuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Link, Head } from '@inertiajs/inertia-vue3';

defineProps({
    enrollments: Object,
});
</script>

<template>
    <Head title="Enrollments" />

    <AuthenticatedLayout>
        <div class="grid grid-cols-[1fr,min-content,1fr]">
            <div class="flex flex-col justify-center">
                <span class="text-5xl font-black leading-tight">Enrollments</span>

                <p class="mt-8 text-gray-700">
                    You’ll be able to find your course
                    <span class="font-semibold text-indigo-500">enrollments</span>
                    on this page, as well as make any
                    <span class="font-semibold text-indigo-500">transfers</span>
                    or
                    <span class="font-semibold text-indigo-500">withdrawals</span>.
                </p>
            </div>

            <div class="ml-24 border-l border-gray-100"></div>

            <!--TODO: visual stuff -->
            <div class="pl-24 -mr-32 pr-32 flex py-16 items-center space-x-16 overflow-x-auto overflow-y-hidden scrollbar scrollbar-thin scrollbar-track-indigo-100 scrollbar-thumb-indigo-500">
                <Link v-for="enrollment in enrollments.data" :key="enrollment.id" :href="route('enrollments.show', enrollment)">
                    <div class="shrink-0 flex h-64 w-80 flex-col justify-center rounded bg-gray-900 p-6">
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
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
