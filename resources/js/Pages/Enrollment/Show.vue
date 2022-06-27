<script setup>
import AuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { ChevronLeftIcon } from '@heroicons/vue/solid';
import LectureMin from '@/Components/Enrollment/LectureMin.vue';
import SecondaryLink from '@/Components/Navigation/SecondaryLink.vue';
import TertiaryLink from '@/Components/Navigation/TertiaryLink.vue';
import MutedLink from '@/Components/Navigation/MutedLink.vue';

defineProps({
    enrollment: Object,
});
</script>

<template>

    <Head title="Enrollments" />

    <AuthenticatedLayout>
        <div class="grid grid-cols-[1fr,min-content,1fr]">
            <div class="flex flex-col justify-center">
                <span class="text-5xl font-black leading-tight">{{ enrollment.data.course_variant.course.subject.name
                    }}<br /><span class="text-indigo-500">{{ enrollment.data.course_variant.course.tutor.name
                        }}</span></span>

                <p class="mt-8 text-gray-700">
                    This course has
                    <span class="font-semibold text-indigo-500 lowercase">{{
                        enrollment.data.course_variant.lecture_count }} lectures</span>
                    per week, both of which are
                    <span class="font-semibold text-indigo-500 lowercase">{{
                        enrollment.data.course_variant.delivery_method }}</span>
                    and last
                    <span class="font-semibold text-indigo-500">{{
                        enrollment.data.course_variant.variant_lectures[0].duration }} minutes</span>.
                    You can transfer without prior approval.
                </p>

                <div class="flex items-center justify-between mt-12">
                    <MutedLink :href="route('enrollments.index')" :active="route().current('enrollments.index')">
                        <ChevronLeftIcon
                            class="mr-2 h-5 w-5 text-gray-300 group-hover:text-gray-200 group-focus:text-gray-200" />
                        Back
                    </MutedLink>

                    <div class="flex items-center space-x-8">
                        <TertiaryLink :href="route('enrollments.destroy', enrollment.data)" as="button" method="delete">
                            Withdraw</TertiaryLink>

                        <SecondaryLink :href="route('enrollments.edit', enrollment.data)">Transfer</SecondaryLink>
                    </div>
                </div>
            </div>

            <div class="ml-24 border-l border-gray-100"></div>

            <!--TODO: dynamic data + position -->
            <div
                class="snap-x pl-24 -mr-32 pr-32 flex py-16 items-center space-x-16 overflow-x-auto overflow-y-hidden scrollbar-thin scrollbar-track-indigo-100 scrollbar-thumb-indigo-500">
                <LectureMin :lecture="lecture" v-for="lecture in enrollment.data.course_variant.variant_lectures"
                    :key="lecture.id" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
