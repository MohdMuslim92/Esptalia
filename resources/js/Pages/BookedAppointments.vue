<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import {Head} from "@inertiajs/vue3";

const appointments = ref([]);
const visibleAppointments = ref([]);
const currentIndex = ref(0);

const allAppointments = ref([]);
const allVisibleAppointments = ref([]);
const currentAppointmentIndex = ref(0);

// Fetch new un confirmed appointments which displayed in the notification section
const fetchAppointments = () => {
    axios.get('/notifications')
        .then(response => {
            if (response.data && response.data.allNotifications && Array.isArray(response.data.allNotifications)) {
                appointments.value = response.data.allNotifications;
                visibleAppointments.value = appointments.value.slice(0, 3);
            } else {
                console.error('Invalid or empty data format received from API:', response.data);
            }
        })
        .catch(error => {
            console.error('Error fetching appointments:', error);
        });
};
onMounted(fetchAppointments);

// Scroll the appointments to show previous
const scrollLeft = () => {
    if (currentIndex.value > 0) {
        currentIndex.value -= 1;
        updateVisibleAppointments();
    }
};

// Scroll the appointments to show next
const scrollRight = () => {
    if (currentIndex.value < appointments.value.length - 3) {
        currentIndex.value += 1;
        updateVisibleAppointments();
    }
};

// show appointments 3 at a time and hide the rest
const updateVisibleAppointments = () => {
    visibleAppointments.value = appointments.value.slice(currentIndex.value, currentIndex.value + 3);
};

// Fetch all appointments
const fetchAllAppointments = () => {
    axios.get('/api/appointments/display')
        .then(appointmentsResponse => {
            allAppointments.value = appointmentsResponse.data;
            allVisibleAppointments.value = allAppointments.value.slice(0, 3);
        })
        .catch(error => {
            console.error('Error fetching whole appointments:', error);
        });
};

onMounted(fetchAllAppointments);

// show appointments 3 at a time and hide the rest
const updateAllVisibleAppointments = () => {
    allVisibleAppointments.value = allAppointments.value.slice(currentAppointmentIndex.value, currentAppointmentIndex.value + 3);
};

// Scroll the appointments to show previous
const appointmentscrollLeft = () => {
    if (currentAppointmentIndex.value > 0) {
        currentAppointmentIndex.value -= 1;
        updateAllVisibleAppointments();
    }
};

// Scroll the appointments to show next
const appointmentscrollRight = () => {
    if (currentAppointmentIndex.value < allAppointments.value.length - 3) {
        currentAppointmentIndex.value += 1;
        updateAllVisibleAppointments();
    }
};

// Confirm the booked appointments
const confirmAppointment = (appointmentId) => {
    axios.patch(`/api/appointments/confirm/${appointmentId}`, { status: 'confirmed' })
        .then(response => {
            // Reload appointments after confirming
            fetchAppointments();
        })
        .catch(error => {
            // Handle error, show an error message to the user
            console.error('Error confirming appointment:', error);
        });
};

// Cancel booked appointments
const cancelAppointment = (appointmentId) => {
    axios.patch(`/api/appointments/cancel/${appointmentId}`, { status: 'canceled' })
        .then(response => {
            console.log('Appointment canceled successfully:', response.data);
            // Reload appointments after canceling
            fetchAppointments();
        })
        .catch(error => {
            // Handle error,show an error message to the user
            console.error('Error canceling appointment:', error);
        });
};

</script>

<template>
    <Head title="Appointments" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Booked Appointments Details</h2>
        </template>
        <div class="h-screen flex items-center justify-center">
            <!-- Display not confirmed appointments -->
            <div class="flex-col items-center space-y-8 pl-20 justify-evenly">
                <h1 class="text-6xl font-bold text-center mb-4">Waiting For Confirmation</h1>
                <div v-if="appointments.length > 0" class="flex space-x-4 mb-4">
                    <div v-for="(appointment, index) in visibleAppointments" :key="index" class="flex-shrink-0 w-72 card">
                        <div class="max-w-full border rounded-lg p-4 ">
                            <p class="line-clamp"><strong>Patient name:</strong> {{ appointment.patient_name }}</p>
                            <p class="line-clamp"><strong>Patient Phone:</strong> {{ appointment.phone_number }}</p>
                            <p class="line-clamp"><strong>Patient email:</strong> {{ appointment.email }}</p>
                            <p class="line-clamp"><strong>Doctor name:</strong> {{ appointment.doctor_first_name }} {{ appointment.doctor_last_name }}</p>
                            <p class="line-clamp"><strong>Appointment time:</strong> {{ appointment.appointment_time }}</p>
                            <div class="flex justify-between mt-4">
                                <button @click="confirmAppointment(appointment.id)"
                                        class="button bg-blue-800 text-white px-4 py-2
                                        rounded hover:bg-blue-900">Confirm</button>
                                <button @click="cancelAppointment(appointment.id)"
                                        class="cancel button bg-red-500 text-white px-4
                                        py-2 rounded hover:bg-red-600">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="justify-center flex space-x-8 mb-8">
                    <h1 class="text-3xl font-bold text-center mb-8">You Have No Appointments To Confirm</h1>
                </div>

                <div class="flex justify-center mt-4" v-if="appointments.length > visibleAppointments.length">
                    <button @click="scrollLeft" class="button mr-2">&#8249;</button>
                    <button @click="scrollRight" class="button">&#8250;</button>
                </div>
            </div>
        </div>

        <div class="h-screen flex items-center justify-center">
            <!-- Display all appointments history including not confirmed -->
            <div class="flex-col items-center space-y-8 pl-20 justify-evenly">
                <h1 class="text-6xl font-bold text-center mb-4">Appointments History</h1>
                <div v-if="allAppointments.length > 0" class="flex space-x-4 mb-4">
                    <div v-for="(allAppointment, appointmentindex) in allVisibleAppointments"
                         :key="appointmentindex" class="flex-shrink-0 w-72 card">
                        <div class="border rounded-lg p-4">
                            <p class="line-clamp"><strong>Patient name:</strong> {{ allAppointment.patient_name }}</p>
                            <p class="line-clamp"><strong>Patient Phone:</strong> {{ allAppointment.phone_number }}</p>
                            <p class="line-clamp"><strong>Patient email:</strong> {{ allAppointment.email }}</p>
                            <p class="line-clamp"><strong>Doctor name:</strong> {{ allAppointment.doctor_first_name }} {{ allAppointment.doctor_last_name }}</p>
                            <p class="line-clamp"><strong>Appointment time:</strong> {{ allAppointment.appointment_time }}</p>
                            <p class="line-clamp"><strong>Appointment status:</strong> {{ allAppointment.status }}</p>
                        </div>
                    </div>
                </div>
                <div v-else class="justify-center flex space-x-8 mb-8">
                    <h1 class="text-3xl font-bold text-center mb-8">You don't have Appointments yet</h1>
                </div>

                <div class="flex justify-center mt-4" v-if="allAppointments.length > allVisibleAppointments.length">
                    <button @click="appointmentscrollLeft" class="button mr-2">&#8249;</button>
                    <button @click="appointmentscrollRight" class="button">&#8250;</button>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<style scoped>
/* Style the display of the appointment */
.card {
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 16px;
    transition: box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
}

.line-clamp {
    max-width: 100%;
    overflow-wrap: break-word;
    white-space: pre-line;
}

.button {
    background-color: #3490dc;
    color: #ffffff;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
    align-self: flex-end;
}

.cancel {
    background-color: red;
}
.button:hover {
    background-color: #2779bd;
}
</style>
