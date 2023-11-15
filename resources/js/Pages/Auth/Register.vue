<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const isHealthcareProvider = ref(false);

// Variables sent with user form submit
const form = useForm({
    name: '',
    phone_number: '',
    email: '',
    password: '',
    password_confirmation: '',
    gender: '',
    date_of_birth: '',
    address: '',
});

// Variables sent with healthcare provider form submit
const providerForm = useForm({
    name: '',
    phone_number: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'healthcare_provider',
    type: '',
    specialization: '',
    state: '',
    city: '',
    address: '',
    description: 'NA',
    working_days: '[]',
    working_hours: 'NA',
    availability: 'N',
    license_number: '',
    license_pic: '',
});
const toggleForm = () => {
    isHealthcareProvider.value = !isHealthcareProvider.value;
};

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const submitHealthcareProviderForm = () => {
    providerForm.post(route('healthcare.register'), {
        onFinish: () => providerForm.reset('password', 'password_confirmation'),
    });
};

const onFileChange = (event) => {
    const file = event.target.files[0];
    providerForm.license_pic = file;
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <!-- Toggle button for regular user and healthcare provider -->
        <button @click="toggleForm" class="mb-4">Switch to {{ isHealthcareProvider ? 'User' : 'Healthcare Provider' }} Registration</button>

        <!-- Regular User Registration Form -->
        <form v-if="!isHealthcareProvider" @submit.prevent="submit">
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
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
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

            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Already registered?
                </Link>

                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>

        <!-- Healthcare Provider Registration Form -->
        <form v-if="isHealthcareProvider" @submit.prevent="submitHealthcareProviderForm" enctype="multipart/form-data">
            <!-- ... healthcare provider fields ... -->
            <div class="pt-4">
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="providerForm.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="providerForm.errors.name" />
            </div>

            <div class="pt-4">
                <InputLabel for="phone_number" value="Phone Number" />

                <TextInput
                    id="phone_number"
                    mame="phone_number"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="providerForm.phone_number"
                    required
                    autocomplete="phone"
                />

                <InputError class="mt-2" :message="providerForm.errors.phone_number" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="providerForm.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="providerForm.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="providerForm.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="providerForm.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="providerForm.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="providerForm.errors.password_confirmation" />
            </div>

            <div class="mt-4">
                <InputLabel for="type" value="Type" />

                <select
                    name="type"
                    id="type"
                    class="my-select"
                    v-model="providerForm.type"
                    required>
                    <option value="hospital">Hospital</option>
                    <option value="medical_center">Medical Center</option>
                    <option value="clinic">Clinic</option>
                </select>

                <InputError class="mt-2" :message="providerForm.errors.type" />
            </div>

            <div class="mt-4">
                <InputLabel for="specialization" value="Specialization" />

                <select
                    name="specialization"
                    id="specialization"
                    class="my-select"
                    v-model="providerForm.specialization"
                    required>
                    <option value="general">General</option>
                    <option value="eye">Eye</option>
                    <option value="ent">ENT</option>
                </select>

                <InputError class="mt-2" :message="providerForm.errors.specialization" />
            </div>

            <div class="mt-4">
                <InputLabel for="state" value="State" />

                <select
                    name="state"
                    id="state"
                    class="my-select"
                    v-model="providerForm.state"
                    required>
                    <option value="Gezira">Gezira</option>
                </select>

                <InputError class="mt-2" :message="providerForm.errors.state" />
            </div>

            <div class="mt-4">
                <InputLabel for="city" value="City" />

                <select
                    name="city"
                    id="city"
                    class="my-select"
                    v-model="providerForm.city"
                    required>
                    <option value="Madani">Madani</option>
                    <option value="Rufaa">Rufaa</option>
                    <option value="Kamleen">Kamleen</option>
                </select>

                <InputError class="mt-2" :message="providerForm.errors.city" />
            </div>

            <div class="mt-4">
                <InputLabel for="address" value="Address" />

                <TextInput
                    id="address"
                    name="address"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="providerForm.address"
                    required
                    autocomplete="address"
                />

                <InputError class="mt-2" :message="providerForm.errors.address" />
            </div>

            <div class="mt-4">
                <InputLabel for="lincense_number" value="License Number" />

                <TextInput
                    id="lincense_number"
                    name="license_number"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="providerForm.license_number"
                    required
                />

                <InputError class="mt-2" :message="providerForm.errors.license_number" />
            </div>

            <div class="mt-4">
                <InputLabel for="lincense_pic" value="License Picture" />

                <input
                    id="lincense_pic"
                    name="license_pic"
                    type="file"
                    class="mt-1 block w-full"
                    @change="onFileChange"
                    required
                />

                <InputError class="mt-2" :message="providerForm.errors.license_pic" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Already registered?
                </Link>

                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
