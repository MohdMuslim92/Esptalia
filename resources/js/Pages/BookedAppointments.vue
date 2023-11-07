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

const scrollLeft = () => {
    if (currentIndex.value > 0) {
        currentIndex.value -= 1;
        updateVisibleAppointments();
    }
};

const scrollRight = () => {
    if (currentIndex.value < appointments.value.length - 3) {
        currentIndex.value += 1;
        updateVisibleAppointments();
    }
};

const updateVisibleAppointments = () => {
    visibleAppointments.value = appointments.value.slice(currentIndex.value, currentIndex.value + 3);
};

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

const updateAllVisibleAppointments = () => {
    allVisibleAppointments.value = allAppointments.value.slice(currentAppointmentIndex.value, currentAppointmentIndex.value + 3);
};

const appointmentscrollLeft = () => {
    if (currentAppointmentIndex.value > 0) {
        currentAppointmentIndex.value -= 1;
        updateAllVisibleAppointments();
    }
};

const appointmentscrollRight = () => {
    if (currentAppointmentIndex.value < allAppointments.value.length - 3) {
        currentAppointmentIndex.value += 1;
        updateAllVisibleAppointments();
    }
};

const confirmAppointment = (appointmentId) => {
    axios.patch(`/api/appointments/confirm/${appointmentId}`, { status: 'confirmed' })
        .then(response => {
            // Handle success
            console.log('Appointment confirmed successfully:', response.data);
            // Reload appointments after confirming
            fetchAppointments();
        })
        .catch(error => {
            // Handle error, show an error message to the user
            console.error('Error confirming appointment:', error);
        });
};

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
            <div class="flex-col items-center space-y-8 pl-20 justify-evenly">
                <div v-if="appointments.length > 0" class="flex space-x-8 mb-8">
                    <div v-for="(appointment, index) in visibleAppointments" :key="index" class="flex-shrink-0 w-72 card">
                        <div class="border rounded-lg p-4">
                            <p><strong>Patient name:</strong> {{ appointment.patient_name }}</p>
                            <p><strong>Patient Phone:</strong> {{ appointment.phone_number }}</p>
                            <p><strong>Patient email:</strong> {{ appointment.email }}</p>
                            <p><strong>Doctor name:</strong> {{ appointment.doctor_first_name }} {{ appointment.doctor_last_name }}</p>
                            <p><strong>Appointment time:</strong> {{ appointment.appointment_time }}</p>
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
                <div class="flex justify-center mt-4" v-if="appointments.length > visibleAppointments.length">
                        <button @click="scrollLeft" class="button mr-2">&#8249;</button>
                        <button @click="scrollRight" class="button">&#8250;</button>
                </div>
            </div>
        </div>

        <div class="h-screen flex items-center justify-center">
            <div class="flex-col items-center space-y-8 pl-20 justify-evenly">
                <div v-if="allAppointments.length > 0" class="flex space-x-8 mb-8">
                    <div v-for="(allAppointment, appointmentindex) in allVisibleAppointments"
                         :key="appointmentindex" class="flex-shrink-0 w-72 card">
                        <div class="border rounded-lg p-4">
                            <p><strong>Patient name:</strong> {{ allAppointment.patient_name }}</p>
                            <p><strong>Patient Phone:</strong> {{ allAppointment.phone_number }}</p>
                            <p><strong>Patient email:</strong> {{ allAppointment.email }}</p>
                            <p><strong>Doctor name:</strong> {{ allAppointment.doctor_first_name }} {{ allAppointment.doctor_last_name }}</p>
                            <p><strong>Appointment time:</strong> {{ allAppointment.appointment_time }}</p>
                            <p><strong>Appointment status:</strong> {{ allAppointment.status }}</p>
                        </div>
                    </div>
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
