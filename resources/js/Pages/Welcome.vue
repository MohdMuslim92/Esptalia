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

const selectedSpecialization = ref('');
const selectedState = ref('');
const selectedCity = ref('');
const doctorName = ref('');
const doctorsList = ref([]); // Variable to store the list of doctors
const searchPerformed = ref(false); // Flag to indicate if the search has been performed
const dayAfterTomorrowDate = ref('');
const animationText = 'Revolutionizing Health, Empowering Lives';
let currentIndex = 0;
let textElement = '';
const animatedText = ref('');
let typingInterval;

const searchDoctors = async () => {
    try {
        // Perform API call to fetch doctors based on user's selections
        const response = await axios.get('/api/doctors/search', {
            params: {
                specialization: selectedSpecialization.value,
                state: selectedState.value,
                city: selectedCity.value,
                name: doctorName.value
            }
        });
        // Update the doctorsList variable with the API response
        doctorsList.value = response.data;

        // Set searchPerformed flag based on whether doctors are found or not
        searchPerformed.value = doctorsList.value.length > 0;

        console.log('List of doctors:', doctorsList.value);
    } catch (error) {
        console.error('Error fetching doctors:', error);
        // Set searchPerformed to false in case of error
        searchPerformed.value = false;
    }
};

// Compute the value of dayAfterTomorrowDate based on the current date
const computeDayAfterTomorrowDate = () => {
    const today = new Date();
    const dayAfterTomorrow = new Date(today);
    dayAfterTomorrow.setDate(today.getDate() + 2);

    // Format the date as "DD/MM/YYYY"
    const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    const dayOfWeek = daysOfWeek[dayAfterTomorrow.getDay()];
    const dd = String(dayAfterTomorrow.getDate()).padStart(2, '0');
    const mm = String(dayAfterTomorrow.getMonth() + 1).padStart(2, '0'); // January is 0!
    const yyyy = dayAfterTomorrow.getFullYear();

    return `${dayOfWeek} ${dd}/${mm}`;
};
// Update the dayAfterTomorrowDate value using the computed property
dayAfterTomorrowDate.value = computeDayAfterTomorrowDate();

function formatDate(date) {
    const dd = String(date.getDate()).padStart(2, '0');
    const mm = String(date.getMonth() + 1).padStart(2, '0');
    const yyyy = date.getFullYear();
    return `${yyyy}-${mm}-${dd}`;
}

const today = new Date();
const tomorrow = new Date(today);
tomorrow.setDate(today.getDate() + 1);
const afterTomorrow = new Date(today);
afterTomorrow.setDate(today.getDate() + 2);

const todayDate = formatDate(today);
const tomorrowDate = formatDate(tomorrow);
const afterTomorrowDate = formatDate(afterTomorrow);

const bookAppointment = (doctorId, chosenDay, hospitalId, medicalCenterId, clinicId) => {
    // Determine which providerId is available
    let providerId;
    let providerName;
    if (hospitalId) {
        providerName = 'hospital_id';
        providerId = hospitalId;
    } else if (medicalCenterId) {
        providerName = 'medical_center_id';
        providerId = medicalCenterId;
    } else if (clinicId) {
        providerName = 'clinic_id';
        providerId = clinicId;
    } else {
        // Handle the case where none of the providerIds are available
        console.error('No valid providerId found.');
        return;
    }

    // Encode data using Base64 to send it with url
    const encodedDoctorId = btoa(doctorId);
    const encodedChosenDay = btoa(chosenDay);
    const encodedProviderId = btoa(providerId);

    // Construct the appointment URL with doctorId, chosenDay, and the determined providerId
    const appointmentUrl = `/book-appointment?doctorId=${encodedDoctorId}&chosenDay=${encodedChosenDay}&${providerName}=${encodedProviderId}`;

    // Navigate to the appointment booking page using window.location.href
    window.location.href = appointmentUrl;
};
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
onMounted(() => {
    typingInterval = setInterval(displayText, 100); // Adjust typing speed (interval) as needed
});

</script>

<template>
    <Head title="Welcome" />
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
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="link link font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400
                dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >Dashboard</Link
                >


                <template v-else>
                    <Link
                        :href="route('About')"
                        class="link font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400
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
                </template>
            </div>

        </div>
        <div class="animated-text absolute inset-0 flex justify-center items-center">
            <p class="text-white text-3xl font-bold">{{ animatedText }}</p>
        </div>

    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex
                        flex-wrap gap-4 justify-center space-x-4">
                <div class="mb-4 flex-grow">
                    <label for="specialization" class="block text-sm font-medium text-gray-600">Specialization:</label>
                    <select v-model="selectedSpecialization" id="specialization" class="mt-1 p-2 border border-gray-300 rounded-md w-full">
                        <option value="General">General</option>
                        <option value="Eye">Eye</option>
                        <option value="ENT">ENT</option>
                    </select>
                </div>

                <div class="mb-4 flex-grow">
                    <label for="state" class="block text-sm font-medium text-gray-600">State:</label>
                    <select v-model="selectedState" id="state" class="mt-1 p-2 border border-gray-300 rounded-md w-full">
                        <option value="Gezira">Gezira</option>
                    </select>
                </div>

                <div class="mb-4 flex-grow">
                    <label for="city" class="block text-sm font-medium text-gray-600">City:</label>
                    <select v-model="selectedCity" id="city" class="mt-1 p-2 border border-gray-300 rounded-md w-full">
                        <option value="Madani">Madani</option>
                        <option value="Rufaa">Rufaa</option>
                        <option value="Kamleen">Kamleen</option>
                    </select>
                </div>

                <div class="mb-4 flex-grow">
                    <label for="doctorName" class="block text-sm font-medium text-gray-600"> . </label>
                    <input v-model="doctorName" id="doctorName" type="text" placeholder="or enter doctor's name" class="mt-1 p-2 border border-gray-300 rounded-md w-full" />
                </div>

                <button @click="searchDoctors" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded-md transition duration-300 ease-in-out">
                    Search Doctors
                </button>
            </div>
        </div>
        <div v-if="doctorsList.length > 0" class="flex pt-6 ml-4">
            <!-- Content for doctorsList (left side) -->
            <div class="flex-grow">
                <h2 class="text-2xl font-semibold mb-4">Search Results:</h2>
                <ul>
                    <li class="pt-4 flex" v-for="doctor in doctorsList" :key="doctor.id">
                        <h3 class="text-xl font-semibold">{{ doctor.first_name }} {{ doctor.last_name }}</h3>
                        <p>Specialization: {{ doctor.speciality }}</p>
                        <p>City: {{ doctor.city }}, State: {{ doctor.state }}</p>
                        <!-- Right sidebar for appointments -->
                        <div class="w-1/4 p-4 border-l">
                            <div class="mb-6">
                                <div class="flex flex-col space-y-4">
                                    <div class="flex justify-between">
                                        <div class="column">
                                            <p class="border-b-2 bg-blue-800 text-center text-white">Today</p>
                                            <p class="pt-4 border-b border-black pb-3">From 18:00 to 23:00</p>
                                            <button @click="bookAppointment(doctor.id, todayDate, doctor.hospital_id, doctor.medical_center_id, doctor.clinic_id)"
                                                    class="mt-4 bg-blue-500
                                                        text-white py-1 px-2 rounded-md
                                                        hover:bg-blue-700 mx-auto flex
                                                        justify-center items-center">
                                                Book
                                            </button>
                                        </div>
                                        <div class="column">
                                            <p class="border-b-2 bg-blue-800 text-center text-white">Tomorrow</p>
                                            <p class="pt-4 border-b border-black pb-3">From 18:00 to 23:00</p>
                                            <button @click="bookAppointment(doctor.id, tomorrowDate, doctor.hospital_id, doctor.medical_center_id, doctor.clinic_id)"
                                                    class="mt-4 bg-blue-500
                                                        text-white py-1 px-2 rounded-md
                                                        hover:bg-blue-700 mx-auto flex
                                                        justify-center items-center">
                                                Book
                                            </button>
                                        </div>
                                        <div class="column">
                                            <p class="border-b-2 bg-blue-800 text-center text-white">{{ dayAfterTomorrowDate }}</p>
                                            <p class="pt-4 border-b border-black pb-3">From 18:00 to 23:00</p>
                                            <button @click="bookAppointment(doctor.id, afterTomorrowDate, doctor.hospital_id, doctor.medical_center_id, doctor.clinic_id)"
                                                    class="mt-4 bg-blue-500
                                                        text-white py-1 px-2 rounded-md
                                                        hover:bg-blue-700 mx-auto flex
                                                        justify-center items-center">
                                                Book
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </li>
                </ul>
            </div>
        </div>
        <div v-else-if="searchPerformed">
            <p>No doctors found matching your search criteria.</p>
        </div>
    </div>
</template>

<style>

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
    width: 100%;
}

.animated-text {
    margin-top: 5em;
    height: 4em;
    font-size: 3em;
    color: white;
    font-weight: bolder;
}
</style>
