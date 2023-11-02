<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    phone_number: '',
    gender: '',
    speciality: '',
    working_days: 'NA',
    working_hours: 'NA',
    availability: 'N',
    doctor_pic: '',
});

const submit = () => {
    form.post(route('addDoctor'));
};
const onFileChange = (event) => {
    const file = event.target.files[0];
    form.doctor_pic = file;
};


</script>

<template>
    <Head title="Add Doctor" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Doctor</h2>
        </template>
        <div class="flex justify-center items-center h-screen">
            <form class="pt-8 border-black" @submit.prevent="submit">
                <InputLabel for="first_name" value="First Name" />

                    <TextInput
                        id="first_name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.first_name"
                        required
                        autofocus
                        autocomplete="name"
                    />


                    <div>
                        <InputLabel for="last_name" value="Last Name" />

                        <TextInput
                            id="last_name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.last_name"
                            required
                            autofocus
                            autocomplete="name"
                        />

                    <InputError class="mt-2" :message="form.errors.first_name" />
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
                    <InputLabel for="speciality" value="Speciality" />

                    <select
                        name="speciality"
                        id="speciality"
                        class="my-select"
                        v-model="form.speciality"
                        required>
                        <option value="general">General</option>
                        <option value="eye">Eye</option>
                        <option value="ent">ENT</option>
                    </select>

                    <InputError class="mt-2" :message="form.errors.speciality" />
                </div>

                <div class="mt-4">
                    <InputLabel for="doctor_pic" value="Doctor Picture" />

                    <input
                        id="doctor_pic"
                        name="doctor_pic"
                        type="file"
                        class="mt-1 block w-full"
                        @change="onFileChange"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.doctor_pic" />
                </div>


                <div class="flex items-center justify-end mt-4">

                    <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Add Doctor
                    </PrimaryButton>
                </div>
            </form>
        </div>


    </AuthenticatedLayout>
</template>
