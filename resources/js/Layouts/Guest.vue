<script setup>
import { Link } from '@inertiajs/inertia-vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <div class="h-screen overflow-hidden bg-white px-24 py-12 font-sans text-gray-900 antialiased">
        <nav class="flex items-center justify-between">
            <Link :href="route('home')" class="flex items-center space-x-4 rounded focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-opacity-75">
                <img :src="asset('img/logo.svg')" alt="Blackbird Logo" />
                <span class="text-xl font-semibold">Blackbird</span>
            </Link>

            <div v-if="canLogin" class="flex items-center space-x-12">
                <Link v-if="$page.props.auth.user" :href="route('dashboard')" :active="route().current('dashboard')" class="rounded border border-gray-900 px-8 py-2 text-base font-medium">Dashboard</Link>

                <div v-else class="flex items-center space-x-12">
                    <Link :href="route('login')" :active="route().current('login')" class="text-base font-medium">Sign In</Link>

                    <Link v-if="canRegister" :href="route('register')" :active="route().current('register')" class="rounded border border-gray-900 px-8 py-2 text-base font-medium">Sign Up</Link>
                </div>
            </div>
        </nav>

        <slot />
    </div>
</template>
