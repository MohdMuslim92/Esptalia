<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, usePage} from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
const user = usePage().props.auth.user;
let provider = ref({});
let hospital = ref({});
let medicalCenter = ref({});
let clinic = ref({});
let specialization = ref('');
let name = user.name;
let phone_number = user.phone_number;
let email = user.email;
let type = ref('');
let state = ref('');
let city = ref('');
let address = user.address;
let description = ref('');
let working_days = ref('');
let working_hours = ref('');
let availability = ref('');
let license_number = ref('');
let license_pic = ref('');

onMounted(async () => {
    try {
        const userId = user.id;
        const response = await axios.get(`/api/providers/${userId}`);
        provider.value = response.data;

    } catch (error) {
        console.error('Error fetching provider data:', error);
    }
});

watch(() => provider.value, (newValue) => {
    if (newValue) {
        specialization = newValue.specialization;
        type.value = newValue.type;
        license_pic.value = newValue.license_pic;
        console.log(license_pic);

    }
});
watch(() => provider.value.type, async (newValue) => {
    if (newValue) {

        try {
            if (newValue === 'hospital') {
                // Make API call to HospitalController
                const hospitalResponse = await axios.get(`/api/hospital/${provider.value.user_id}`);
                hospital = hospitalResponse.data;
                state.value = hospitalResponse.data.state;
                city.value = hospitalResponse.data.city;
                description.value = hospitalResponse.data.description;
                working_days.value = hospitalResponse.data.working_days;
                working_hours.value = hospitalResponse.data.working_hours;
                availability.value = hospitalResponse.data.availability;
                license_number.value = hospitalResponse.data.license_number;
            } else if (newValue === 'medical_center') {

                // Make API call to MedicalCenterController
                const medicalCenterResponse = await axios.get(`/api/medical-center/${provider.value.user_id}`);
                medicalCenter.value = medicalCenterResponse.data;
                state.value = medicalCenterResponse.data.state;
                city.value = medicalCenterResponse.data.city;
                description.value = medicalCenterResponse.data.description;
                working_days.value = medicalCenterResponse.data.working_days;
                working_hours.value = medicalCenterResponse.data.working_hours;
                availability.value = medicalCenterResponse.data.availability;
                license_number.value = medicalCenterResponse.data.license_number;

                console.log(medicalCenterResponse.data);
            } else if (newValue === 'clinic') {
                // Make API call to ClinicController
                const clinicResponse = await axios.get(`/api/clinic/${provider.value.user_id}`);
                clinic = clinicResponse.data;
                state.value = clinicResponse.data.state;
                city.value = clinicResponse.data.city;
                description.value = clinicResponse.data.description;
                working_days.value = clinicResponse.data.working_days;
                working_hours.value = clinicResponse.data.working_hours;
                availability.value = clinicResponse.data.availability;
                license_number.value = clinicResponse.data.license_number;
            }
        } catch (error) {
            console.error('Error fetching provider data:', error);
        }
    }
});

axios.interceptors.response.use(
    response => response,
    error => {
        console.error('API request failed:', error);
        return Promise.reject(error);
    }
);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="flex py-12 justify-start items-start space-x-40 pl-20">
            <!-- Left Side -->
            <div class="content-container flex py-12 justify-between items-start space-x-16">
                <div class="space-x-4">
                    <div class="font-semibold text-lg  space-y-4">
                        <div class="flex items-center space-x-4 font-semibold text-lg">
                            <img src="{{ license_pic }}" alt="Provider Photo" class="w-16 h-16 rounded-full" />
                            <p>{{ name }}</p>
                        </div>
                        <div class="pt-8 text-gray-600"> &#x1F4DE; {{ phone_number }}</div>
                        <div class="text-gray-600"> &#x2709; {{ email }}</div>
                        <div class="text-gray-600"> &#x1F3E0; {{ state }} - {{ city }} - {{ address }}</div>
                    </div>
                </div>
            </div>
            <!-- Right Side -->
            <div class="pt-14 flex flex-col space-y-4  space-y-4">
                <div class="flex space-x-4">
                    <div><strong>Type:</strong> {{ type }}</div>
                    <div><strong>Specialization:</strong> {{ specialization }}</div>
                </div>
                <div class="flex space-x-4">
                    <div><strong>License Number:</strong> {{ license_number }}</div>
                    <div><strong>Working Days:</strong> {{ working_days }}</div>
                </div>
                <div><strong>Working Hours:</strong> {{ working_hours }}</div>
                <div class="fixed bottom-8 left-1/2 transform -translate-x-1/2">
                    <div class="flex space-x-4">
                        <a :href="route('appointments.index')">
                            <button class="rounded-full bg-blue-500 text-white px-4 py-2">Appointments</button>
                        </a>
                        <a :href="route('doctors')">
                            <button class="rounded-full bg-green-500 text-white px-4 py-2">Doctors</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
