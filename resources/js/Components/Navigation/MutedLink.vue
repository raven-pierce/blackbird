<script setup>
import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps({
    href: String,
    active: Boolean,
});

const textClasses = {
    'w-fit group flex items-center text-xs text-gray-300 transition-all duration-300 ease-in-out hover:text-gray-200 focus:text-gray-200 focus:outline-none': true,
    'text-gray-500 font-medium': props.active,
};
</script>

<template>
    <Link :href="href" :class="textClasses">
        <slot />
    </Link>
</template>
