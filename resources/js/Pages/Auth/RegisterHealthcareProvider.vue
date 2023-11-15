<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

// Variables to be sent with form when submit
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

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
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
                <InputLabel for="type" value="Type" />

                <select
                    name="type"
                    id="type"
                    class="my-select"
                    v-model="form.type"
                    required>
                    <option value="1">Hospital</option>
                    <option value="2">Medical Center</option>
                    <option value="3">Clinic</option>
                </select>

                <InputError class="mt-2" :message="form.errors.type" />
            </div>

            <div class="mt-4">
                <InputLabel for="specialization" value="Specialization" />

                <select
                    name="specialization"
                    id="specialization"
                    class="my-select"
                    v-model="form.specialization"
                    required>
                    <option value="general">General</option>
                    <option value="eye">Eye</option>
                    <option value="ent">ENT</option>
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
                    <option value="gezira">Gezira</option>
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
                    <option value="madani">Madani</option>
                    <option value="city2">Rufaa</option>
                    <option value="kamleen">Kamleen</option>
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
                <InputLabel for="lincense_number" value="License Number" />

                <TextInput
                    id="lincense_number"
                    name="license_number"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.license_number"
                    required
                    autocomplete=""
                />

                <InputError class="mt-2" :message="form.errors.license_number" />
            </div>

            <div class="mt-4">
                <InputLabel for="lincense_pic" value="License Picture" />

                <input
                    id="lincense_pic"
                    name="license_pic"
                    type="file"
                    formenctype="multipart/form-data"
                    class="mt-1 block w-full"
                    required
                    autocomplete=""
                />

                <InputError class="mt-2" :message="form.errors.license_number" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Already registered?
                </Link>

                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
