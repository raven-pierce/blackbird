<script setup>
import { ref, onMounted } from 'vue';
import { CheckIcon, ExclamationIcon, FireIcon, InformationCircleIcon, XIcon, MinusSmIcon } from '@heroicons/vue/solid';

let props = defineProps({
    level: String,
    message: String,
    duration: Number
});

let toastShown = ref(true);

onMounted(() => {
    setTimeout(() => {
        toastShown.value = false;
    }, props.duration * 1000);
});
</script>

<template>
    <transition enter-active-class="transition duration-300 delay-300 ease-out"
        enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100"
        leave-active-class="transition duration-150 ease-in" leave-from-class="transform scale-100 opacity-100"
        leave-to-class="transform scale-95 opacity-0">
        <div v-show="toastShown"
            class="absolute right-4 bottom-8 flex space-x-24 items-center justify-between rounded bg-gray-900 p-4 text-xs text-white w-fit">
            <div class="flex items-center space-x-4">
                <CheckIcon v-if="props.level == 'success'" class="h-5 w-5 text-emerald-500" />

                <ExclamationIcon v-else-if="props.level == 'warning'" class="h-5 w-5 text-amber-500" />

                <FireIcon v-else-if="props.level == 'danger'" class="h-5 w-5 text-red-500" />

                <InformationCircleIcon v-else-if="props.level == 'info'" class="h-5 w-5 text-indigo-500" />

                <XIcon v-else="props.level == 'error'" class="h-5 w-5 text-red-500" />

                <span>{{ props.message }}</span>
            </div>

            <MinusSmIcon @click="toastShown = false"
                class="h-5 w-5 text-gray-500 hover:text-gray-400 hover:cursor-pointer" />
        </div>
    </transition>
</template>
