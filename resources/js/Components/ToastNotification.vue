<script setup>
import { CheckIcon, ExclamationIcon, FireIcon, InformationCircleIcon, XIcon, MinusSmIcon } from '@heroicons/vue/solid';

defineProps({
    type: String,
    message: String,
    manuallyClose: Boolean
});
</script>

<template>
    <transition enter-active-class="transition duration-100 ease-out" enter-from-class="transform scale-95 opacity-0"
        enter-to-class="transform scale-100 opacity-100" leave-active-class="transition duration-75 ease-in"
        leave-from-class="transform scale-100 opacity-100" leave-to-class="transform scale-95 opacity-0">
        <div
            class="absolute right-4 bottom-8 flex space-x-24 items-center justify-between rounded bg-gray-900 p-4 text-xs text-white w-fit">
            <div class="flex items-center space-x-4">
                <div v-if="type = 'success'">
                    <CheckIcon class="h-5 w-5 text-emerald-500" />
                </div>

                <div v-if="type = 'warning'">
                    <ExclamationIcon class="h-5 w-5 text-amber-500" />
                </div>

                <div v-if="type = 'danger'">
                    <FireIcon class="h-5 w-5 text-red-500" />
                </div>

                <div v-if="type = 'info'">
                    <InformationCircleIcon class="h-5 w-5 text-indigo-500" />
                </div>

                <div v-if="type = 'error'">
                    <XIcon class="h-5 w-5 text-red-500" />
                </div>

                <span>{{ message }}</span>
            </div>

            <MinusSmIcon class="h-5 w-5 text-gray-500" />
        </div>
    </transition>
</template>
