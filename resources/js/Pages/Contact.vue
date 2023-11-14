<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '../Components/ApplicationLogo.vue';
import {  onMounted, ref } from 'vue';
import axios from 'axios';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

const animationText = 'Revolutionizing Health, Empowering Lives';
let currentIndex = 0;
let textElement = '';
const animatedText = ref('');
let typingInterval;
const notifications = ref([]);
let user_role = ref('');
const isDropdownOpen = ref(false);
const auth = ref('');
const userRole = ref(null);



// Function to open and close the dropdown menu
function toggleDropdown() {
    isDropdownOpen.value = !isDropdownOpen.value;
}

// Event listener to to resize the menu responsivly
window.addEventListener('resize', () => {
    isDropdownOpen.value = window.innerWidth <= 640;
});

// Function to display text with typewriter effect
function displayText() {
    if (currentIndex < animationText.length) {
        textElement += animationText.charAt(currentIndex);
        document.querySelector('.animated-text').textContent = textElement;
        currentIndex++;
    } else {
        clearInterval(typingInterval);
    }
}
// Get the user type in order to display notifications in case of Healthcare Provider
const getUserRole = () => {
    axios.get('/notifications')
        .then(response => {
            user_role.value = response.data.user_role;
            console.log(userRole.value);
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
        });
};

onMounted(() => {
    typingInterval = setInterval(displayText, 100); // Adjust typing speed (interval) as needed
    getUserRole();
});

</script>

<template>
    <Head title="Contact Us" />
    <div class="background-wrapper">
        <div class="pb-5 absolute left-5">
            <!-- Logo -->
            <div class="shrink-0 flex items-center">
                <Link :href="route('landing')">
                    <ApplicationLogo
                        class="block h-9 w-36 fill-current text-gray-800"
                    />
                </Link>
            </div>

        </div>

        <div class="flex absolute top-0 right-0 transform   sm:mr-10 sm:mt-0">
            <div v-if="canLogin" class="sm:top-0 sm:right-0 p-6 text-right">
                <div v-if="$page.props.auth.user">
                    <Link
                        v-if="user_role === 'healthcare_provider'"
                        :href="route('healthcare.dashboard')"
                        class="link font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400
                dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Provider Dashboard</Link
                    >
                    <Link
                        v-else
                        :href="route('dashboard')"
                        class="link font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400
                dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Dashboard</Link
                    >
                </div>

                <!-- Navigation (Desktop) -->
                <template v-else>
                    <div class="hidden sm:flex sm:items-center ">
                        <Link
                            :href="route('home')"
                            class="link font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400
                    dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                        >Home</Link
                        >

                        <Link
                            :href="route('About')"
                            class="link ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400
                    dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                        >About</Link
                        >

                        <Link
                            :href="route('Contact')"
                            class="link ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400
                    dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                        >Contact Us</Link
                        >
                        <Link
                            :href="route('login')"
                            class="link ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400
                    dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                        >Log in</Link
                        >

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="link ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                        >Register</Link
                        >
                    </div>
                    <!-- Dropdown menu for smaller screens -->
                    <div class="sm:hidden absolute top-0 right-0 mt-5 mr-5">
                        <div class=" pt-2 dropdown" :class="{ open: isDropdownOpen }">
                            <button @click="toggleDropdown" class="flex dropdown-toggle">
                                <p class="menu-icon pr-2">&#9776; </p>
                                Menu</button>
                            <div v-if="isDropdownOpen" class="dropdown-menu">
                                <Link
                                    :href="route('home')"
                                    class="link font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400
                                    dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                                >Home</Link
                                >

                                <Link
                                    :href="route('About')"
                                    class="link font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400
                                    dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                                >About</Link
                                >

                                <Link
                                    :href="route('Contact')"
                                    class="link font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400
                                    dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                                >Contact Us</Link
                                >
                                <Link
                                    :href="route('login')"
                                    class="link font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400
                                    dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                                >Log in</Link
                                >

                                <Link
                                    v-if="canRegister"
                                    :href="route('register')"
                                    class="link font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400
                                    dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                                >Register</Link
                                >
                            </div>
                        </div>
                    </div>
                </template>
            </div>

        </div>
        <div class="animated-text absolute inset-0 flex justify-center items-center">
            <p class="text-white text-3xl font-bold">{{ animatedText }}</p>
        </div>


    </div>
    <div class="py-12">
        <!-- Contact Section -->
        <div class="mt-24 container mx-auto my-16 flex items-center justify-center">
            <div class="bg-gray-900 p-8 rounded-md shadow-md w-full lg:w-2/5">
                <h2 class="text-4xl font-bold mb-8 text-center text-black">Get in Touch</h2>

                <!-- Contact Form -->
                <form class="contact-form">
                    <div class="mb-4">
                        <label for="name" class="text-black mb-2">Your Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="w-full lg:w-60 bg-gray-800 rounded-md p-2 text-white"
                        />
                    </div>

                    <div class="mb-4">
                        <label for="email" class="text-black block mb-2">Your Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="w-full lg:w-60 bg-gray-800 rounded-md p-2 text-white"
                            placeholder="name@example.com"
                        />
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="text-black block mb-2">Your Phone Number</label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            class="w-full lg:w-60 bg-gray-800 rounded-md p-2 text-white"
                        />
                    </div>

                    <div class="mb-6">
                        <label for="message" class="text-black block mb-2">Your Message</label>
                        <textarea
                            id="message"
                            name="message"
                            rows="4"
                            class="w-full lg:w-60 bg-gray-800 rounded-md p-2 text-white"
                            placeholder="Write your message here..."
                        ></textarea>
                    </div>

                    <button
                        type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300"
                    >
                        Send Message
                    </button>
                </form>
            </div>
        </div>

    </div>
</template>

<style scoped>

.link:hover {
    text-decoration: underline darkblue; /* Red underline when hovering */
    transform: scale(1.1); /* Scale the text by 10% when hovering */
    color: #ff5722; /* Change text color to a different shade of red */
}

.background-wrapper {
    /* Set the background image and its properties */
    top: 0;

    background-image: url('/resources/img/cover.jpg');
    width: 100%;
    background-size: cover; /* Adjust the background size as needed */
    background-position: center center; /* Center the background image */
    background-repeat: no-repeat; /* Prevent the background from repeating */
    height: 75vh; /* Set the height of the wrapper to fill the viewport */
    display: flex;
}

.dropdown {
    position: relative;
}

.dropdown-toggle {
    background: none;
    border: none;
    cursor: pointer;
    color: white;
    font-weight: bold;
}

.dropdown-menu {
    display: block;
    padding: 10px 0; /* Add padding to the dropdown menu */
    margin-top: 5px; /* Add margin to separate the dropdown menu from the toggle button */
}

.dropdown-menu .link {
    text-align: left;
    display: block;
    white-space: nowrap; /* Prevent line breaks within the link */
    padding-top: 4px; /* Add padding to the links inside the dropdown */
    color: #333; /* Set the text color */
    text-decoration: none;
    transition: background-color 0.3s ease; /* Add a smooth transition effect */
}

.dropdown-menu .link:hover {
    background-color: #f7f7f7; /* Change the background color on hover */
}

.animated-text {
    margin-top: 5em;
    height: 4em;
    font-size: 3em;
    color: white;
    font-weight: bolder;
}

/* Center the contact form */
.contact-form-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; /* Adjust the height as needed */
}

/* Style for the contact form */
.contact-form {
    width: 60%; /* Adjust the width as needed */
    max-width: 400px; /* Set a maximum width if desired */
    padding: 20px;
    border: 2px solid #ddd; /* Add a border */
    border-radius: 10px; /* Add rounded corners */
    margin: 0 auto; /* Center the form horizontally */
}

/* Increase the height of form fields */
.contact-form input[type="text"],
.contact-form input[type="email"],
.contact-form input[type="tel"] {
    height: 2.5rem; /* Adjust the height as needed */
}
</style>
