<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '../Components/ApplicationLogo.vue';
</script>

<template>
    <Head title="Thank You" />
    <div class="flex items-center justify-between py-5">
        <!-- Logo -->
        <div class="flex-shrink-0">
            <Link :href="route('home')">
                <ApplicationLogo class="h-9 w-36 fill-current text-gray-800" />
            </Link>
        </div>
    </div>

    <div class="text-center pt-8">
        <h1 class="text-4xl font-bold text-green-500 mb-4">Booking Successful!</h1>
        <p class="text-lg text-gray-600">{{ $page.props.successMessage }}</p>

        <!-- Fancy Design -->
        <div class="mt-8 p-6 bg-gray-100 rounded-lg shadow-md">
            <p class="text-sm text-gray-500">Thank you for choosing our services!</p>
            <p class="text-sm text-gray-500">We look forward to serving you.</p>
        </div>

        <!-- Fancy Button -->
        <Link :href="route('home')">
            <button class="mt-6 px-6 py-3 bg-green-500 text-white rounded-full hover:bg-green-600 transition duration-300 ease-in-out">
                Go to Home
            </button>
        </Link>
    </div>
</template>
