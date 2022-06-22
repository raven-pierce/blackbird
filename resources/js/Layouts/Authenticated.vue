<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import {
    LogoutIcon,
    MailIcon,
    CogIcon,
    VideoCameraIcon,
    ChevronDownIcon,
} from "@heroicons/vue/solid";

import NavLink from "@/Components/NavLink";
</script>

<template>
    <div class="min-h-screen flex flex-col bg-white px-24 py-12 font-sans text-gray-900 antialiased gap-y-16">
        <nav class="flex items-center justify-between">
            <div class="flex items-center space-x-24">
                <Link :href="route('dashboard')" class="flex items-center space-x-4 rounded focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-opacity-75">
                    <img :src="asset('img/logo.svg')" alt="Blackbird Logo" />
                    <span class="text-xl font-semibold">Blackbird</span>
                </Link>

                <div class="flex items-center space-x-8">
                    <NavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</NavLink>

                    <NavLink :href="route('enrollments.index')" :active="route().current('enrollments.index')">Enrollments</NavLink>
                </div>
            </div>

            <Menu as="div" class="relative inline-block">
                <MenuButton class="inline-flex w-full justify-center rounded px-4 py-2 text-sm font-normal text-gray-500 hover:text-gray-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-opacity-75 active:text-gray-600">
                    {{ $page.props.auth.user.name }}
                    <ChevronDownIcon class="ml-2 -mr-1 h-5 w-5 text-gray-500 hover:text-gray-400 active:text-gray-600" aria-hidden="true"/>
                </MenuButton>

                <transition
                    enter-active-class="transition duration-100 ease-out"
                    enter-from-class="transform scale-95 opacity-0"
                    enter-to-class="transform scale-100 opacity-100"
                    leave-active-class="transition duration-75 ease-in"
                    leave-from-class="transform scale-100 opacity-100"
                    leave-to-class="transform scale-95 opacity-0"
                >
                    <MenuItems class="absolute right-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded bg-white shadow-md ring-1 ring-indigo-500 ring-opacity-5 focus:outline-none">
                        <div class="p-1">
                            <MenuItem v-slot="{ active }">
                                <Link :class="[ active ? 'bg-indigo-500 text-white' : 'text-gray-900', 'group flex w-full items-center rounded px-2 py-2 text-sm' ]">
                                    <CogIcon :active="{ active }" class="mr-2 h-5 w-5 text-indigo-400" aria-hidden="true"/>
                                    Settings
                                </Link>
                            </MenuItem>
                        </div>

                        <div class="p-1">
                            <MenuItem v-slot="{ active }">
                                <Link :class="[ active ? 'bg-indigo-500 text-white' : 'text-gray-900', 'group flex w-full items-center rounded px-2 py-2 text-sm' ]">
                                    <MailIcon :active="{ active }" class="mr-2 h-5 w-5 text-indigo-400" aria-hidden="true"/>
                                    O365 Email
                                </Link>
                            </MenuItem>

                            <MenuItem v-slot="{ active }">
                                <Link :class="[ active ? 'bg-indigo-500 text-white' : 'text-gray-900', 'group flex w-full items-center rounded px-2 py-2 text-sm' ]">
                                    <VideoCameraIcon :active="{ active }" class="mr-2 h-5 w-5 text-indigo-400" aria-hidden="true"/>
                                    Microsoft Teams
                                </Link>
                            </MenuItem>
                        </div>

                        <div class="p-1">
                            <MenuItem v-slot="{ active }">
                                <Link as="button" :href="route('logout')" method="post" :class="[ active ? 'bg-indigo-500 text-white' : 'text-gray-900', 'group flex w-full items-center rounded px-2 py-2 text-sm' ]">
                                    <LogoutIcon :active="{ active }" class="mr-2 h-5 w-5 text-indigo-400" aria-hidden="true"/>
                                    Log Out
                                </Link>
                            </MenuItem>
                        </div>
                    </MenuItems>
                </transition>
            </Menu>
        </nav>

        <main class="my-auto px-8">
            <div class="grid grid-cols-2 gap-x-48 divide-x divide-gray-100">
                <div class="flex flex-col">
                    <span class="text-5xl font-black leading-tight">Good Afternoon, <br /><span class="text-indigo-500">{{ $page.props.auth.user.name }}</span></span>

                    <p class="mt-8 text-gray-700">It’s 29°C on a <span class="text-indigo-500">Sunday</span>, and you have <span class="text-indigo-500">two classes</span> ahead of you.</p>
                </div>

                <div class="flex w-80 h-64 flex-col rounded bg-gray-900 p-6 justify-center">
                    <span class="font-medium text-indigo-100">One</span>

                    <span class="mt-6 text-xl font-bold text-white">Year 10 Biology</span>

                    <div class="mt-2 flex justify-between items-center">
                        <span class="text-lg text-gray-100">8:30 PM</span>

                        <span class="text-xs font-semibold uppercase text-indigo-500">In Person</span>
                    </div>

                    <span class="mt-4 text-sm font-light text-gray-500">Dr. John Doe</span>

                    <span class="mt-6 text-xs font-semibold uppercase text-indigo-500">90 Minutes</span>
                </div>
            </div>
        </main>
    </div>
</template>
