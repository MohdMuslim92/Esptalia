<script setup>
import { ref, onMounted, toRefs, watch } from 'vue';
import {Head, Link, useForm, usePage} from '@inertiajs/vue3';
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import ApplicationLogo from '../Components/ApplicationLogo.vue';

const { doctor, appointment_time, provider_type_id, provider_id } = toRefs(usePage().props);

const doctorName = ref(`${doctor.value.first_name} ${doctor.value.last_name}`);
const doctorId = ref(`${doctor.value.id}`);
const doctorSpecialization = ref(doctor.value.speciality);

const email = ref('');

const form = useForm({
    name: '',
    email: '',
    phone_number: '',
    doctor_id: doctorId,
    provider_id: provider_id.value.id.toString(),
    appointment_time: appointment_time,
    status: 'booked',
});

watch(provider_id, (newProviderId) => {
    // Update the form's provider_id whenever provider_id changes
    form.provider_id = newProviderId;
});

onMounted(() => {
    console.log('Doctor Data:', doctor.value);
    console.log('Chosen Day:', appointment_time.value);
    console.log('Provider ID:', provider_type_id.value);
});
const submitAppointment = async () => {
    try {
        form.post(route('MakeAppointment'));
    } catch (error) {
        console.error('Error submitting appointment:', error);
    }
};

</script>

<template>
    <Head title="Book Appointment" />
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
    <div class="mt-4 flex justify-between">
        <!-- Left Side: Display Doctor's Details and Chosen Day -->
        <div class="w-1/2 p-4">
            <h1 class="text-3xl font-semibold mb-4">Doctor Details</h1>
            <div class="mb-4">
                <p class="text-lg font-semibold">Name:</p>
                <p class="text-gray-700">{{ doctorName }}</p>
            </div>
            <div class="mb-4">
                <p class="text-lg font-semibold">Specialization:</p>
                <p class="text-gray-700">{{ doctorSpecialization }}</p>
            </div>
            <div class="mb-4">
                <p class="text-lg font-semibold">Appintment Date:</p>
                <p class="text-gray-700">{{ appointment_time }}</p>
            </div>

        </div>

        <!-- Right Side: Patient Information Form -->
        <div class="w-1/2 p-4">
            <h2 class="text-2xl font-semibold mb-4">Patient Information</h2>
            <form @submit.prevent="submitAppointment">
                <div>
                    <InputLabel for="name" value="Name" />

                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                    />

                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="pt-4">
                    <InputLabel for="phone_number" value="Phone Number" />

                    <TextInput
                        id="phone_number"
                        mame="phone_number"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.phone_number"
                        required
                        autocomplete="phone"
                    />

                    <InputError class="mt-2" :message="form.errors.phone_number" />
                </div>

                <div class="mt-4">
                    <InputLabel for="email" value="Email" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autocomplete="email"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="pt-4">
                    <TextInput
                        id="doctor_id"
                        mame="doctor_id"
                        type="hidden"
                        class="mt-1 block w-full"
                        v-model="form.doctor_id"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.doctor_id" />
                </div>

                <div class="pt-4">
                    <TextInput
                        id="provider_id"
                        mame="provider_id"
                        type="hidden"
                        class="mt-1 block w-full"
                        v-model="form.provider_id"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.provider_id" />
                </div>

                <div class="pt-4">
                    <TextInput
                        id="appointment_time"
                        mame="appointment_time"
                        type="hidden"
                        class="mt-1 block w-full"
                        v-model="form.appointment_time"
                        required
                        autocomplete="phone"
                    />

                    <InputError class="mt-2" :message="form.errors.appointment_time" />
                </div>


                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700
                            text-white font-bold py-2 px-4 rounded-md
                            transition duration-300 ease-in-out">Book Appointment</button>
            </form>
        </div>
    </div>
</template>
