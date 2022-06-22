<script setup>
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    href: String,
    active: Boolean,
});

const textClasses = {
    "group flex flex-col items-center text-sm text-gray-500 transition-all duration-150 ease-in-out hover:text-gray-400 focus:text-gray-400 focus:outline-none": true,
    "text-gray-700 font-medium": props.active,
};

const underlineClasses = {
    "mt-1 h-0.5 w-3/5 rounded-full transition-all duration-150 ease-in-out group-hover:bg-indigo-500 group-hover:opacity-50 group-focus:bg-indigo-500 group-focus:opacity-50": true,
    "bg-indigo-500": props.active,
};
</script>

<template>
    <Link :href="href" :class="textClasses">
        <slot />
        <span :class="underlineClasses"></span>
    </Link>
</template>
