<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '../Components/ApplicationLogo.vue';
import { ref } from 'vue';
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

    // Construct the appointment URL with doctorId, chosenDay, and the determined providerId
    const appointmentUrl = `/book-appointment?doctorId=${doctorId}&chosenDay=${chosenDay}&${providerName}=${providerId}`;

    // Navigate to the appointment booking page using window.location.href
    window.location.href = appointmentUrl;
};

</script>

<template>
    <Head title="Welcome" />
    <div class="flex pb-5">
        <!-- Logo -->
        <div class="shrink-0 flex items-center">
            <Link :href="route('home')">
                <ApplicationLogo
                    class="block h-9 w-36 fill-current text-gray-800"
                />
            </Link>
        </div>

    </div>

    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white"
    >
        <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
            <Link
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >Dashboard</Link
            >

            <template v-else>
                <Link
                    :href="route('login')"
                    class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Log in</Link
                >

                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Register</Link
                >
            </template>
        </div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-wrap gap-4">
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

    </div>

</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
