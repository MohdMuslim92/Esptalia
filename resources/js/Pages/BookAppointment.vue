<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {Head, Link, useForm, usePage} from '@inertiajs/vue3';
import axios from "axios";
import { ref, onMounted, watch , computed} from 'vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
const user = usePage().props.auth.user;
let doctor = ref({});
let locations = ref({});
let first_name = ref('');
let last_name = ref('');
let phone_number = ref('');
let speciality = ref('');
let gender = ref('');
let doctor_pic = ref('');
let working_days = ref([
    { name: 'Monday', isChecked: false },
    { name: 'Tuesday', isChecked: false },
    { name: 'Wednesday', isChecked: false },
    { name: 'Thursday', isChecked: false },
    { name: 'Friday', isChecked: false },
    { name: 'Saturday', isChecked: false },
    { name: 'Sunday', isChecked: false },
]);
let working_hours = ref('');
let availability = ref('');
const currentURL = window.location.href;
const email = currentURL.split('/').pop(); // Extract email from the end of the URL

const hours = Array.from({ length: 24 }, (_, index) => {
    const hour = String(index).padStart(2, '0');
    return `${hour}:00`;
});

const form = useForm({
    first_name: first_name,
    last_name: last_name,
    phone_number: phone_number,
    email: email,
    gender: gender,
    speciality: speciality,
    working_days: working_days.value.map(day => ({ name: day.name, isChecked: day.isChecked })),
    working_hours: working_hours,
    working_hours_from: '',
    working_hours_to: '',
    availability: availability,
    doctor_pic: doctor_pic,
});

onMounted(async () => {
    try {
        const doctorResponse = await axios.get(`/Get-doctor/${email}`);

        const response = await axios.get(`/Edit-doctor/${email}`);
        doctor.value = doctorResponse.data;
    } catch (error) {
        console.error('Error fetching Doctor data:', error);
    }
});

watch(() => doctor.value, (newValue) => {
    if (newValue) {
        form.first_name = newValue.first_name;
        form.last_name = newValue.last_name;
        form.phone_number = newValue.phone_number;
        form.email = newValue.email;
        form.gender = newValue.gender;
        form.speciality = newValue.speciality;
        form.doctor_pic = newValue.doctor_pic;

    }
});
watch(() => doctor.value, async (newValue) => {
    if (newValue) {
        try {
            const locationResponse = await axios.get(`/Get-locations/${doctor.value.id}`);
            locations.value = locationResponse.data;
            console.log(locations.value);

            // Parse the working_days JSON string into an array of objects
            const workingDaysArray = JSON.parse(locations.value.working_days);

            // Update working days using Vue.set to ensure reactivity
            workingDaysArray.forEach(day => {
                const foundDay = form.working_days.find(d => d.name === day.name);
                if (foundDay) {
                    foundDay.isChecked = day.isChecked;
                }
            });

            const [workingHoursFrom, workingHoursTo] = locations.value.working_hours.split(' to ');
            form.working_hours_from = workingHoursFrom;
            form.working_hours_to = workingHoursTo;

            form.availability = locations.value.availability;
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

const submitForm = async () => {
    try {
        // Merge working_hours_from and working_hours_to into a single string
        form.working_hours = `${form.working_hours_from} to ${form.working_hours_to}`;

        await form.patch(`/update-doctor/${doctor.value.id}`);

    } catch (error) {
        console.error('Error updating doctor:', error);
    }
};
</script>
<template v-if="doctor.value.id">
    <Head title="Doctors" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Doctor Details</h2>
        </template>
        <section class="section pt-14">
            <header>
                <h2 class="text-lg font-medium text-gray-900">Doctor Information</h2>
            </header>

            <div class="flex flex-col space-y-8 pl-20 justify-start">
                <form @submit.prevent="form.patch(route('doctor.update'))" class="mt-6 space-y-6">
                    <div class="form-field">
                        <InputLabel for="first_name" value="First Name" />
                        <TextInput
                            id="first_name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.first_name"
                            required
                            autofocus
                            autocomplete="name" />
                        <InputError class="mt-2" :message="form.errors.first_name" />
                    </div>

                    <div class="form-field">
                        <InputLabel for="last_name" value="Last Name" />
                        <TextInput
                            id="last_name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.last_name"
                            required
                            autofocus
                            autocomplete="name" />
                        <InputError class="mt-2" :message="form.errors.last_name" />
                    </div>

                    <div class="form-field">
                        <InputLabel for="phone_number" value="Phone Number" />
                        <TextInput
                            id="phone_number"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.phone_number"
                            required
                            autofocus
                            autocomplete="phone" />
                        <InputError class="mt-2" :message="form.errors.phone_number" />
                    </div>

                    <div class="form-field">
                        <InputLabel for="email" value="Email" />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autocomplete="username" />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="speciality" value="Speciality" />
                        <select
                            name="speciality"
                            id="city"
                            class="my-select"
                            v-model="form.speciality"
                            required>
                            <option :value="form.speciality" selected>{{ form.speciality }}</option>
                            <option v-if="form.speciality !== 'General'" value="General">General</option>
                            <option v-if="form.speciality !== 'Eye'" value="Eye">Eye</option>
                            <option v-if="form.speciality !== 'ENT'" value="ENT">ENT</option>
                        </select>

                        <InputError class="mt-2" :message="form.errors.speciality" />
                    </div>

                    <div class="form-field mt-4">
                        <InputLabel for="working_days" value="Working Days" />
                        <div class="flex items-center space-x-4">
                            <div v-for="(day, index) in form.working_days" :key="index">
                                <input
                                    type="checkbox"
                                    :id="day.name"
                                    :value="day.name"
                                    v-model="day.isChecked"
                                />
                                <label :for="day.name">{{ day.name }}</label>
                            </div>
                        </div>
                        <InputError class="mt-2" :message="form.errors.working_days" />
                    </div>

                    <div class="form-field mt-4">
                        <InputLabel for="working_hours" value="Working Hours" />
                        <div class="flex items-center space-x-4">
                            <select v-model="form.working_hours_from" class="my-select">
                                <option v-for="hour in hours" :key="hour" :value="hour">{{ hour }}</option>
                            </select>
                            <span>to</span>
                            <select v-model="form.working_hours_to" class="my-select">
                                <option v-for="hour in hours" :key="hour" :value="hour">{{ hour }}</option>
                            </select>
                        </div>
                        <InputError class="mt-2" :message="form.errors.working_hours" />
                    </div>

                    <div class="form-field mt-4">
                        <InputLabel for="availability" value="Availability" />
                        <TextInput
                            id="availability"
                            name="availability"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.availability"
                            required />
                        <InputError class="mt-2" :message="form.errors.availability" />
                    </div>

                    <div class="mt-4 flex justify-around">
                        <InputLabel for="male" value="Male" />
                        <input
                            id="male"
                            name="gender"
                            type="radio"
                            class="my-radio-button"
                            value="M"
                            v-model="form.gender"
                            required />
                        <InputLabel for="female" value="Female" />
                        <input
                            id="female"
                            name="gender"
                            type="radio"
                            class="my-radio-button"
                            value="F"
                            v-model="form.gender"
                            required />
                        <InputError class="mt-2" :message="form.errors.gender" />
                    </div>

                    <input type="hidden" name="id" v-model="doctor.id">

                    <div class="flex items-center gap-4 mt-6">
                        <PrimaryButton @click="submitForm" :disabled="form.processing">Save</PrimaryButton>
                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                        </Transition>
                    </div>
                </form>
            </div>
        </section>
    </AuthenticatedLayout>
</template>

<style scoped>
.form-field {
    width: 70%;
}

.section {
    width: 80%;
    margin: 0 auto;
}
</style>
