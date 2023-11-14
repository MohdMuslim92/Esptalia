<script setup>
import { ref } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const selectedSpecialization = ref('');
const selectedState = ref('');
const selectedCity = ref('');
const doctorName = ref('');
const doctorsList = ref([]); // Variable to store the list of doctors
const searchPerformed = ref(false); // Flag to indicate if the search has been performed
const dayAfterTomorrowDate = ref('');
const resultContainer = ref(null);


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
    if (searchPerformed.value) {
        const offset = 20; // Adjust this value as needed
        const topPosition = resultContainer.value.offsetTop - offset;

        // Use window.requestAnimationFrame to ensure smooth scrolling
        const scrollAnimation = () => {
            window.scroll(0, topPosition);
        };

        // Use requestAnimationFrame for smooth scrolling
        window.requestAnimationFrame(scrollAnimation);
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

</script>

<template>
    <Head title="Search Doctors" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Revolutionizing Health, Empowering Lives</h2>
        </template>

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
            <div ref="resultContainer"  class="container mx-auto mt-4 px-4">
                <div v-if="doctorsList.length > 0" class="flex flex-col pt-6">
                    <!-- Content for doctorsList (left side) -->
                    <div class="flex-grow mb-4">
                        <h2 class="text-2xl font-semibold mb-4">Search Results:</h2>
                        <ul>
                            <li class="pt-4 flex flex-col sm:flex-row" v-for="doctor in doctorsList" :key="doctor.id">
                                <div class="doctor-details mb-4 sm:pt-0 sm:pr-4 sm:w-3/4">
                                    <h3 class="text-xl font-semibold">Dr. {{ doctor.first_name }} {{ doctor.last_name }}</h3>
                                    <p>Specialization: {{ doctor.speciality }}</p>
                                    <p>{{ doctor.user_name }} {{ doctor.type }}</p>
                                    <p>{{ doctor.user_address }}</p>
                                    <p>{{ doctor.city }}, {{ doctor.state }}</p>
                                </div>
                                <!-- Right sidebar for appointments -->
                                <div class="appointments-details w-full sm:w-1/4 p-4 border-t sm:border-t-0 sm:border-l">
                                    <div class="mb-6">
                                        <div class="flex flex-col space-y-4">
                                            <div class="flex justify-between">
                                                <div class="column">
                                                    <p class="border-b-2 bg-blue-800 text-center text-white">Today</p>
                                                    <p class="pt-4 border-b border-black pb-3">From 18:00 to 23:00</p>
                                                    <button @click="bookAppointment(doctor.id, todayDate, doctor.hospital_id, doctor.medical_center_id, doctor.clinic_id)"
                                                            class="mt-4 bg-blue-500 text-white py-1 px-2 rounded-md hover:bg-blue-700 mx-auto flex justify-center items-center">
                                                        Book
                                                    </button>
                                                </div>
                                                <div class="column">
                                                    <p class="border-b-2 bg-blue-800 text-center text-white">Tomorrow</p>
                                                    <p class="pt-4 border-b border-black pb-3">From 18:00 to 23:00</p>
                                                    <button @click="bookAppointment(doctor.id, tomorrowDate, doctor.hospital_id, doctor.medical_center_id, doctor.clinic_id)"
                                                            class="mt-4 bg-blue-500 text-white py-1 px-2 rounded-md hover:bg-blue-700 mx-auto flex justify-center items-center">
                                                        Book
                                                    </button>
                                                </div>
                                                <div class="column">
                                                    <p class="border-b-2 bg-blue-800 text-center text-white">{{ dayAfterTomorrowDate }}</p>
                                                    <p class="pt-4 border-b border-black pb-3">From 18:00 to 23:00</p>
                                                    <button @click="bookAppointment(doctor.id, afterTomorrowDate, doctor.hospital_id, doctor.medical_center_id, doctor.clinic_id)"
                                                            class="mt-4 bg-blue-500 text-white py-1 px-2 rounded-md hover:bg-blue-700 mx-auto flex justify-center items-center">
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
                    <p class="text-center">No doctors found matching your search criteria.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style scoped>
/* Add custom styles for the button */
button {
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.3s ease-in-out;
}

button:hover {
    background-color: #1a365d;
}

.column {
    border-right: 1px solid #ccc; /* Thin border separating columns */
    padding-right: 10px; /* Add padding to separate text from the border */
    padding-left: 10px; /* Add padding to separate text from the border */
}

</style>
