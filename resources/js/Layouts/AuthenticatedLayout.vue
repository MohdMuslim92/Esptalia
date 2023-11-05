<script setup>
import {ref, onMounted, watch} from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import '@fortawesome/fontawesome-free/css/all.css';
import axios from 'axios';

const notifications = ref([]);
const showNotifications = ref(false);
let user_role = ref('');
let pollingInterval;

const fetchNotifications = () => {
    axios.get('/notifications')
        .then(response => {
            notifications.value = response.data.allNotifications;
            user_role.value = response.data.user_role;
            console.log(user_role);
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
        });
};

// Poll for new notifications every 5 seconds (adjust the interval as needed)
const startPolling = () => {
    pollingInterval = setInterval(fetchNotifications, 5000);
};

const stopPolling = () => {
    clearInterval(pollingInterval);
};
onMounted(() => {

    fetchNotifications(); // Initial fetch
    // Start polling if user_role is 'healthcare_provider'
    if (user_role.value === 'healthcare_provider') {
        startPolling();
    }
});
// Watch for changes in user_role and start/stop polling accordingly
watch(user_role, (newValue, oldValue) => {
    if (newValue === 'healthcare_provider') {
        startPolling();
    } else {
        stopPolling();
    }
});

const showingNavigationDropdown = ref(false);



</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('landing')">
                                    <ApplicationLogo
                                        class="block h-9 w-36 fill-current text-gray-800"
                                    />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <div class="ml-3 relative flex">
                                <!-- Notifications Icon with Notification Count -->
                                <div v-if="user_role === 'healthcare_provider'" class="relative ml-3 pt-2" @click="showNotifications = !showNotifications">
                                    <span class="cursor-pointer relative">
                                        <i class="fas fa-first-aid text-blue-500" style="font-size: 20px;"></i>
                                        <!-- Notification Count -->
                                        <span class="absolute top-3 right-0 bg-red-500 text-white rounded-full px-2 py-1
                                         text-xs" style="font-size: 10px;">{{ notifications.length }}</span>
                                    </span>

                                    <!-- Notifications List Dropdown -->
                                    <div v-if="showNotifications" class="absolute right-0 mt-2 w-72 bg-white border
                                    border-gray-300 rounded-lg shadow-lg">
                                        <div v-if="notifications.length > 0" class="p-2">
                                            <div v-for="(notification, index) in notifications.slice(0, 5)"
                                                 :key="index" class="mb-2 border-b border-gray-200 py-2">
                                                <a :href="route('booked-appointments')">
                                                    A new appointment by {{ notification.patient_name }}
                                                </a>
                                            </div>
                                            <div v-if="notifications.length > 5" class="text-blue-500 cursor-pointer
                                            hover:underline py-2">
                                                <a :href="route('booked-appointments')">See more</a>
                                            </div>
                                        </div>
                                        <div v-else class="p-2 text-gray-500">
                                            No new notifications.
                                        </div>
                                    </div>
                                </div>

                                <!-- Settings Dropdown -->
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent
                                                text-sm leading-4 font-medium rounded-md text-gray-500 bg-white
                                                hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <div class="-mr-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
