<script setup>
import GuestLayout from "@/Layouts/Guest.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Home" />

    <guest-layout :can-login="canLogin" :can-register="canRegister">
        <main class="mt-32 grid grid-cols-2 gap-x-48 px-8">
            <div class="flex flex-col">
                <span class="text-5xl font-black">learning,<br />modernized.</span>

                <p class="mt-4 text-gray-700">Dolor molestiae voluptatem quae. Ut eum sint fugit similique. Ut mollitia natus eius et debitis doloribus qui.</p>

                <Link :href="$page.props.auth.user ? route('dashboard') : route('register')" :active="route().current('register')" class="mt-8 w-fit rounded bg-gray-900 px-8 py-2 text-sm text-white">Get Started</Link>
            </div>

            <div class="relative flex flex-col">
                <img :src="asset('img/library.jpg')" alt="Stock photo of a library." class="absolute top-32 left-0 w-96 rounded"/>

                <div class="absolute top-12 left-48 flex  h-64 flex-col rounded bg-gray-900 p-6 justify-center">
                    <span class="font-medium text-indigo-100">New Course</span>

                    <span class="mt-6 text-xl font-bold text-white">Year 10 Biology</span>

                    <span class="mt-2 text-lg text-gray-100">Dr. John Doe</span>

                    <span class="mt-4 text-sm font-light text-gray-500">Cambridge Assessment International Examinations</span>

                    <span class="mt-6 text-xs font-semibold uppercase text-indigo-500">90 min/session</span>
                </div>
            </div>
        </main>
    </guest-layout>
</template>
