<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const user = usePage().props.auth.user;
let doctors = ref([]);
let doctorLocations = ref([]);

onMounted(async () => {
    try {
        const userId = user.id;

        // Make both API calls simultaneously using Promise.all
        const [doctorsResponse, doctorLocationsResponse] = await Promise.all([
            axios.get(`/api/doctors-list`),
            axios.get(`/api/doctor-locations/${userId}`)
        ]);

        doctors.value = doctorsResponse.data;
        console.log(doctors.value);
        doctorLocations.value = doctorLocationsResponse.data;

        console.log(doctors);
        console.log(doctorLocations);
    } catch (error) {
        console.error('Error fetching data:', error);
    }
});
</script>

<template>
    <Head title="Doctors" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Doctors</h2>
        </template>
        <div class="flex-container items-center justify-center h-screen">
            <div class="flex-col space-y-8 pl-20 justify-start w-full md:w-auto">
                <div v-for="(doctor, index) in doctors" :key="index" class="flex py-12 flex-col md:flex-row justify-evenly">
                    <!-- Left Side Content -->
                    <div class="space-x-4">
                        <div class="font-semibold text-lg space-y-4">
                            <div class="flex items-center space-x-4 font-semibold text-lg pt-12">
                                <img :src="doctor.doctor_pic" alt="Doctor Photo" class="w-16 h-16 rounded-full" />
                                <div class="row-end-2">
                                    <a :href="`/Edit-doctor/${doctor.email}`">
                                        <div style="color: #2563eb">Dr. {{ doctor.first_name }} {{ doctor.last_name }}</div>
                                    </a>
                                    <div>{{ doctor.speciality }}</div>
                                </div>
                            </div>
                            <div class="pt-8 text-gray-600"> &#x1F4DE; {{ doctor.phone_number }}</div>
                            <div class="text-gray-600" style="white-space: nowrap;"> &#x2709; {{ doctor.email }}</div>
                        </div>
                    </div>
                    <!-- Right Side Content -->
                    <div class="pt-12 flex flex-col space-y-4 pr-20">
                        <div class="flex items-center space-x-4">
                            <div style="width: 120px;"><strong>Gender:</strong></div>
                            <div style="flex: 1;">{{ doctor.gender }}</div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div style="width: 120px;"><strong>Availability:</strong></div>
                            <div style="flex: 1;">{{ doctorLocations[index].availability }}</div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div style="width: 120px;"><strong>Working Days:</strong></div>
                            <div style="flex: 1;">{{ doctorLocations[index].working_days }}</div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div style="width: 120px;"><strong>Working Hours:</strong></div>
                            <div style="flex: 1;">{{ doctorLocations[index].working_hours }}</div>
                        </div>
                    </div>
                </div>
                <!-- Add Doctor Button -->
                <div class="absolute pb-4 left-1/2 transform -translate-x-1/2">
                    <a :href="route('addDoctor')">
                        <button class="rounded-full bg-blue-500 text-white px-4 py-2">Add Doctor</button>
                    </a>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
