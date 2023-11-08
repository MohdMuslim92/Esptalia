<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
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
let provider = ref({});
let hospital = ref({});
let medicalCenter = ref({});
let clinic = ref({});
let specialization = ref('');
let type = ref('');
let state = ref('');
let city = ref('');
let description = ref('');
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
let workingHoursFrom = ref('');
let workingHoursTo = ref('');
let availability = ref('');
let license_number = ref('');
const loading = ref(true);
const selectedWorkingDays = computed(() => {
    return working_days.value
        .filter((day) => day.isChecked)
        .map((day) => day.name);
});

const hours = Array.from({ length: 24 }, (_, index) => {
    const hour = String(index).padStart(2, '0');
    return `${hour}:00`;
});

const form = useForm({
    name: user.name,
    phone_number: user.phone_number,
    email: user.email,
    specialization: specialization,
    gender: user.gender,
    date_of_birth: user.date_of_birth,
    state: state,
    city: city,
    address: user.address,
    description: description,
    working_days: working_days.value.map(day => ({ name: day.name, isChecked: day.isChecked })),
    working_hours_from: workingHoursFrom,
    working_hours_to: workingHoursTo,
    working_hours: working_hours,
    availability: availability,
    license_number: license_number,
});

onMounted(async () => {
    try {
        const userId = user.id;
        const response = await axios.get(`/api/providers/${userId}`);
        loading.value = false;
        provider.value = response.data;
        loading.value = false;

    } catch (error) {
        console.error('Error fetching provider data:', error);
        loading.value = false;
    }
});

const workingHours = computed(() => {
    return `${workingHoursFrom.value} to ${workingHoursTo.value}`;
});


// Watch for changes in working_hours_from and working_hours_to
watch([workingHoursFrom, workingHoursTo], ([from, to]) => {
    // Update the form's working_hours property
    form.working_hours = `${from} to ${to}`;
});

watch(() => provider.value, (newValue) => {
    if (newValue) {
        form.specialization = newValue.specialization;
        type.value = newValue.type;

    }
});
watch(() => provider.value.type, async (newValue) => {
    if (newValue) {

        try {
            if (newValue === 'hospital') {
                // Make API call to HospitalController
                const hospitalResponse = await axios.get(`/api/hospital/${provider.value.user_id}`);
                form.hospital = hospitalResponse.data;
                console.log(hospitalResponse);
                form.state = hospitalResponse.data.state;
                form.city = hospitalResponse.data.city;
                form.description = hospitalResponse.data.description;
                // Parse the working_days JSON string into an array of objects
                const workingDaysArray = JSON.parse(hospitalResponse.data.working_days);

                // Update working days using Vue.set to ensure reactivity
                workingDaysArray.forEach(day => {
                    const foundDay = form.working_days.find(d => d.name === day.name);
                    if (foundDay) {
                        foundDay.isChecked = day.isChecked;
                    }
                });
                const [workingHoursFrom, workingHoursTo] = hospitalResponse.data.working_hours.split(' to ');
                form.working_hours_from = workingHoursFrom;
                form.working_hours_to = workingHoursTo;
                form.availability = hospitalResponse.data.availability;
                form.license_number = hospitalResponse.data.license_number;
            } else if (newValue === 'medical_center') {

                // Make API call to MedicalCenterController
                const medicalCenterResponse = await axios.get(`/api/medical-center/${provider.value.user_id}`);
                medicalCenter.value = medicalCenterResponse.data;
                form.state = medicalCenterResponse.data.state;
                form.city = medicalCenterResponse.data.city;
                form.description = medicalCenterResponse.data.description;
                // Parse the working_days JSON string into an array of objects
                const workingDaysArray = JSON.parse(medicalCenterResponse.data.working_days);

                // Update working days using Vue.set to ensure reactivity
                workingDaysArray.forEach(day => {
                    const foundDay = form.working_days.find(d => d.name === day.name);
                    if (foundDay) {
                        foundDay.isChecked = day.isChecked;
                    }
                });
                const [workingHoursFrom, workingHoursTo] = medicalCenterResponse.data.working_hours.split(' to ');
                form.working_hours_from = workingHoursFrom;
                form.working_hours_to = workingHoursTo;
                form.availability = medicalCenterResponse.data.availability;
                form.license_number = medicalCenterResponse.data.license_number;

                console.log(medicalCenterResponse.data);
            } else if (newValue === 'clinic') {
                // Make API call to ClinicController
                const clinicResponse = await axios.get(`/api/clinic/${provider.value.user_id}`);
                form.clinic = clinicResponse.data;
                form.state = clinicResponse.data.state;
                form.city = clinicResponse.data.city;
                form.description = clinicResponse.data.description;
                // Parse the working_days JSON string into an array of objects
                const workingDaysArray = JSON.parse(clinicResponse.data.working_days);

                // Update working days using Vue.set to ensure reactivity
                workingDaysArray.forEach(day => {
                    const foundDay = form.working_days.find(d => d.name === day.name);
                    if (foundDay) {
                        foundDay.isChecked = day.isChecked;
                    }
                });
                const [workingHoursFrom, workingHoursTo] = clinicResponse.data.working_hours.split(' to ');
                form.working_hours_from = workingHoursFrom;
                form.working_hours_to = workingHoursTo;
                form.availability = clinicResponse.data.availability;
                form.license_number = clinicResponse.data.license_number;
                console.log(clinicResponse.data);
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
const mergedWorkingHours = computed(() => {
    return `${form.working_hours_from} to ${form.working_hours_to}`;
});

const submitForm = async () => {
    try {
        // Set form.working_hours using the computed property
        form.working_hours = mergedWorkingHours.value;

        delete form.working_hours_from;
        delete form.working_hours_to;

        await form.patch(route('provider-profile.update'));

    } catch (error) {
        console.error('Error updating profile:', error);
    }
};

</script>
<template v-if="provider.value">
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information.
            </p>
        </header>
        <div v-if="loading">Loading...</div>

        <div v-else>

        <div v-if="user.role === 'healthcare_provider'">
            <form @submit.prevent="submitForm" class="mt-6 space-y-6">
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

                <div>
                    <InputLabel for="phone_number" value="Phone Number" />

                    <TextInput
                        id="phone_number"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.phone_number"
                        required
                        autofocus
                        autocomplete="phone"
                    />

                    <InputError class="mt-2" :message="form.errors.phone_number" />
                </div>

                <div>
                    <InputLabel for="email" value="Email" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autocomplete="username"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div v-if="mustVerifyEmail && user.email_verified_at === null">
                    <p class="text-sm mt-2 text-gray-800">
                        Your email address is unverified.
                        <Link
                            :href="route('verification.send')"
                            method="post"
                            as="button"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            Click here to re-send the verification email.
                        </Link>
                    </p>

                    <div
                        v-show="status === 'verification-link-sent'"
                        class="mt-2 font-medium text-sm text-green-600"
                    >
                        A new verification link has been sent to your email address.
                    </div>
                </div>

                <div class="mt-4">
                    <InputLabel for="specialization" value="Specialization" />
                    <select
                        name="specialization"
                        id="specialization"
                        class="my-select"
                        v-model="form.specialization"
                        required>
                        <option :value="form.specialization" selected>{{ form.specialization }}</option>
                        <option v-if="form.specialization !== 'General'" value="General">General</option>
                        <option v-if="form.specialization !== 'Eye'" value="Eye">Eye</option>
                        <option v-if="form.specialization !== 'ENT'" value="ENT">ENT</option>
                    </select>

                    <InputError class="mt-2" :message="form.errors.specialization" />
                </div>

                <div class="mt-4">
                    <InputLabel for="state" value="State" />
                    <select
                        name="state"
                        id="state"
                        class="my-select"
                        v-model="form.state"
                        required>
                        <option :value="form.state" >{{ form.state }}</option>
                        <option v-if="form.state !== 'Gezira'" value="Gezira" >Gezira</option>
                    </select>

                    <InputError class="mt-2" :message="form.errors.state" />
                </div>

                <div class="mt-4">
                    <InputLabel for="city" value="City" />
                    <select
                        name="city"
                        id="city"
                        class="my-select"
                        v-model="form.city"
                        required>
                        <option :value="form.city" selected>{{ form.city }}</option>
                        <option v-if="form.city !== 'Madani'" value="Madani">Madani</option>
                        <option v-if="form.city !== 'Rufaa'" value="Rufaa">Rufaa</option>
                        <option v-if="form.city !== 'Kamleen'" value="Kamleen">Kamleen</option>
                    </select>

                    <InputError class="mt-2" :message="form.errors.city" />
                </div>

                <div class="mt-4">
                    <InputLabel for="address" value="Address" />

                    <TextInput
                        id="address"
                        name="address"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.address"
                        required
                        autocomplete="address"
                    />

                    <InputError class="mt-2" :message="form.errors.address" />
                </div>

                <div class="mt-4">
                    <InputLabel for="description" value="Description" />

                    <textarea
                        id="description"
                        name="descripion"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.description"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.description" />
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

                <div class="mt-4">
                    <InputLabel for="availability" value="Availability" />

                    <TextInput
                        id="availability"
                        name="availability"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.availability"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.availability" />
                </div>

                <div class="mt-4">
                    <InputLabel for="license_number" value="License Number" />

                    <TextInput
                        id="license_number"
                        name="license_number"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.license_number"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.license_number" />
                </div>

                <div class="flex items-center gap-4">
                    <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

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


        <div v-else>
            <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6">
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

                <div>
                    <InputLabel for="phone_number" value="Phone Number" />

                    <TextInput
                        id="phone_number"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.phone_number"
                        required
                        autofocus
                        autocomplete="phone"
                    />

                    <InputError class="mt-2" :message="form.errors.phone_number" />
                </div>

                <div>
                    <InputLabel for="email" value="Email" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autocomplete="username"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div v-if="mustVerifyEmail && user.email_verified_at === null">
                    <p class="text-sm mt-2 text-gray-800">
                        Your email address is unverified.
                        <Link
                            :href="route('verification.send')"
                            method="post"
                            as="button"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            Click here to re-send the verification email.
                        </Link>
                    </p>

                    <div
                        v-show="status === 'verification-link-sent'"
                        class="mt-2 font-medium text-sm text-green-600"
                    >
                        A new verification link has been sent to your email address.
                    </div>
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
                        required
                    />
                    <InputLabel for="female" value="Female" />

                    <input
                        id="female"
                        name="gender"
                        type="radio"
                        class="my-radio-button"
                        value="F"
                        v-model="form.gender"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.gender" />
                </div>

                <div class="mt-4">
                    <InputLabel for="date_of_birth" value="Date of Birth" />

                    <input
                        id="date_of_birth"
                        name="date_of_birth"
                        type="date"
                        class="my-date-input"
                        v-model="form.date_of_birth"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.date_of_birth" />
                </div>

                <div class="mt-4">
                    <InputLabel for="address" value="Address" />

                    <TextInput
                        id="address"
                        name="address"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.address"
                        required
                        autocomplete="address"
                    />

                    <InputError class="mt-2" :message="form.errors.address" />
                </div>

                <div class="flex items-center gap-4">
                    <PrimaryButton type="submit" :disabled="form.processing">Save</PrimaryButton>

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
        </div>

    </section>
</template>
