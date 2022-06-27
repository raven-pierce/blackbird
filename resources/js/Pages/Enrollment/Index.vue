<script setup>
import AuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Link, Head } from '@inertiajs/inertia-vue3';
import PrimaryLink from '@/Components/Navigation/PrimaryLink.vue';
import EnrollmentCard from '@/Components/Enrollment/Card.vue';

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

                <PrimaryLink class="mt-12" :href="route('enrollments.create')"
                    :active="route().current('enrollments.create')">New Enrollment</PrimaryLink>
            </div>

            <div class="ml-24 border-l border-gray-100"></div>

            <!--TODO: visual stuff -->
            <div
                class="pl-24 -mr-32 pr-32 flex py-16 items-center space-x-16 overflow-x-auto overflow-y-hidden snap-x scrollbar-thin scrollbar-track-indigo-100 scrollbar-thumb-indigo-500">
                <Link v-for="enrollment in enrollments.data" :key="enrollment.id"
                    :href="route('enrollments.show', enrollment)">
                <EnrollmentCard :enrollment="enrollment" />
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
