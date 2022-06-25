<script setup>
import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps({
    href: String,
    active: Boolean,
});

const textClasses = {
    'w-fit group flex flex-col items-center text-xs text-indigo-300 transition-all duration-300 ease-in-out hover:text-indigo-200 focus:text-indigo-200 focus:outline-none': true,
    'text-indigo-500 font-medium': props.active,
};

const underlineClasses = {
    'mt-1 h-0.5 w-3/5 rounded-full transition-all duration-300 ease-in-out group-hover:bg-indigo-300 group-hover:opacity-75 group-focus:bg-indigo-300 group-focus:opacity-75': true,
    'bg-indigo-300': props.active,
};
</script>

<template>
    <Link :href="href" :class="textClasses">
        <slot />
        <span :class="underlineClasses"></span>
    </Link>
</template>
