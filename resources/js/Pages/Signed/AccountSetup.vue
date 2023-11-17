<template>
    <Head title="Account Setup" />

    <div>
        <h2 class="text-xl font-semibold">Confirm Your Account</h2>

        <div class="mb-3 mt-3">
            <label class="text-sm text-gray-400">Email Address</label>
            <InputText class="w-full border-1 rounded-lg border-gray-200 px-4 py-3 disabled:opacity-50" placeholder="your@email.com" disabled />
        </div>

        <div class="mb-3">
            <label class="text-sm text-gray-400">Desired Password</label>
            <Password v-model="form.password" class="w-full" inputClass="w-full border-1 rounded-lg border-gray-200 px-4 py-3" />
        </div>

        <div class="mb-3">
            <label class="text-sm text-gray-400">Confirm Desired Password</label>
            <InputText type="password" class="w-full border-1 rounded-lg border-gray-200 px-4 py-3" />
        </div>

        <Button label="Submit" severity="info" class="p-button p-button-success" />
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
import {Head, useForm} from "@inertiajs/vue3";
import Password from "primevue/password";
import InputText from "primevue/inputtext";
import Button from "primevue/button";

const form = useForm({
    password: null,
})
</script>

<style scoped>

</style>
