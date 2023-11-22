<template>
    <Head title="Account Setup" />

    <div>
        <h2 class="text-xl font-semibold">Confirm Your Account</h2>
        <h3 class="text-lg font-normal">You've been invited to join the Green Gorilla network. Set your password below and login.</h3>

        <div class="mb-3 mt-3">
            <label class="text-sm text-gray-400">Email Address</label>
            <InputText class="w-full border-1 rounded-lg border-gray-200 px-4 py-3 disabled:opacity-50" :placeholder="email" disabled />
        </div>

        <div class="mb-3">
            <label class="text-sm text-gray-400">Desired Password</label>
            <Password v-model="form.password" class="w-full" inputClass="w-full border-1 rounded-lg border-gray-200 px-4 py-3" />
            <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="mb-3">
            <label class="text-sm text-gray-400">Confirm Desired Password</label>
            <InputText type="password" v-model="form.password_confirmation" class="w-full border-1 rounded-lg border-gray-200 px-4 py-3" />
            <InputError class="mt-2" :message="form.errors.password_confirmation" />
        </div>

        <button class="px-8 py-2 bg-black text-white rounded-lg" @click="submitForm" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Submit</button>
    </div>
</template>

<script>
import layout from "@/Layouts/GuestLayout.vue";

export default {
    name: "AccountSetup",
    layout: layout,
}
</script>

<script setup>
import {Head, router, useForm} from "@inertiajs/vue3";
import Password from "primevue/password";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    token: Number,
    email: String,
})

const form = useForm({
    password: null,
    password_confirmation: null,
})

const submitForm = () => {
    form.post(route('account-setup-post', props.token));
}
</script>

<style scoped>

</style>
