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
    <Head title="About" />
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
        <div class="about-section bg-gradient-to-b from-purple-700 via-pink-500 to-indigo-900 py-16 text-white">
            <div class="container mx-auto text-center">
                <h2 class="text-4xl font-extrabold mb-8 text-yellow-300">About Esptalia</h2>
                <p class="text-gray-300 mb-12 leading-loose">
                    Esptalia is a groundbreaking initiative born out of a profound concern for the challenges prevalent in Sudan's healthcare system. We recognized the need for transformative solutions to address issues such as long queues, uncertain doctor schedules, and cumbersome administrative processes in hospitals and health centers.

                    Our mission is to revolutionize Sudan's healthcare landscape by introducing digital innovations that empower lives. We understand the frustrations of patients and healthcare providers and are dedicated to creating a seamless, efficient, and accessible healthcare experience for all.

                    With a vision that extends beyond just a digital platform, Esptalia embodies a transformation in how healthcare is accessed and delivered in Sudan. We are committed to making a real difference by providing a holistic solution to the existing challenges.

                    <br><br>

                    At Esptalia, we believe in community engagement and grassroots involvement. Our approach includes:
                </p>

                <!-- Engagement Strategies -->
                <div class="flex flex-wrap justify-center mb-12">
                    <div class="strategy mx-6 mb-8 transform hover:scale-110 duration-300 ease-in-out">
                        <img src="../../img/engage.png" alt="Engage Local Influencers" class="w-40 h-40 mb-4 mx-auto rounded-full">
                        <h3 class="text-lg font-extrabold mb-2 text-yellow-300">Engage Local Influencers</h3>
                        <p class="text-gray-300">We collaborate with influential local personalities and community leaders on social media platforms. By leveraging their reach and credibility, we spread awareness about the platform's benefits, addressing common misconceptions and fears.</p>
                    </div>
                    <div class="strategy mx-6 mb-8 transform hover:scale-110 duration-300 ease-in-out">
                        <img src="../../img/partnership.png" alt="Partnerships with Local Initiatives" class="w-40 h-40 mb-4 mx-auto rounded-full">
                        <h3 class="text-lg font-extrabold mb-2 text-yellow-300">Partnerships with Local Initiatives</h3>
                        <p class="text-gray-300">We forge partnerships with local charity organizations and initiatives. These entities often enjoy trust within communities and play a crucial role in educating citizens about the platform's ease of use and advantages.</p>
                    </div>
                </div>

                <!-- Portfolio Project Information -->
                <p class="text-gray-300 mb-6">
                    Esptalia is also a portfolio project for Holberton School, a prestigious institution dedicated to nurturing talent in the field of technology and software development. This project showcases our skills, dedication, and passion for making a difference in the world through innovative solutions.
                </p>
                <p class="text-gray-300">
                    To learn more about Holberton School and their programs, please visit
                    <a href="https://www.holbertonschool.com/"
                       class="text-pink-400 hover:underline" target="_blank">Holberton School</a>.
                </p>

                <!-- Team Members -->
                <div class="flex flex-wrap justify-center mb-12 pt-8">
                    <div class="team-member mx-6 mb-8 text-center">
                        <img src="../../img/linkedin.png" alt="LinkedIn Profile" class="w-16 h-16 mx-auto mb-4">
                        <a href="https://www.linkedin.com/in/mohdmuslim92/" target="_blank" class="text-blue-500 block mt-2 text-lg font-semibold hover:underline">LinkedIn</a>
                    </div>
                    <div class="team-member mx-6 mb-8 text-center">
                        <img src="../../img/github.png" alt="GitHub Profile" class="w-16 h-16 mx-auto mb-4">
                        <a href="https://github.com/MohdMuslim92/" target="_blank" class="text-gray-800 block mt-2 text-lg font-semibold hover:underline">GitHub</a>
                    </div>
                    <div class="team-member mx-6 mb-8 text-center">
                        <img src="../../img/twitter.png" alt="Twitter Profile" class="w-16 h-16 mx-auto mb-4">
                        <a href="https://twitter.com/MohdMuslim92" target="_blank" class="text-blue-400 block mt-2 text-lg font-semibold hover:underline">Twitter</a>
                    </div>
                </div>

                <!-- Project Repository Link -->
                <div class="mb-12 text-center">
                    <p class="text-white mb-4 text-lg">Explore our project on GitHub:</p>
                    <a href="https://github.com/MohdMuslim92/Esptalia" target="_blank" class="text-blue-500 font-bold text-lg hover:underline">Esptalia GitHub Repository</a>
                </div>
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

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}
</style>
