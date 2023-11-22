<template>
    <AppLayout title="Users">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Users
                </h2>

                <Button icon="fa fa-plus" severity="success" label="Invite User" @click="userDialog = true" />
            </div>
        </template>

        <Dialog v-model:visible="userDialog" modal header="Invite User" :style="{width: '50rem'}" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <InputLabel for="first" value="First Name" />
                    <InputText id="first" v-model="userForm.first" class="w-full border border-gray-300 shadow-sm rounded py-3 focus:border-green-500 focus:ring-green-500" required autofocus />
                    <InputError class="mt-2" :message="userForm.errors.first" />
                </div>

                <div class="mb-4">
                    <InputLabel for="last" value="Last Name" />
                    <InputText id="last" v-model="userForm.last" class="w-full border border-gray-300 shadow-sm rounded py-3 focus:border-green-500 focus:ring-green-500" required />
                    <InputError class="mt-2" :message="userForm.errors.last" />
                </div>

                <div class="mb-4">
                    <InputLabel for="email" value="Email Address" />
                    <InputText id="email" v-model="userForm.email" inputmode="email" class="w-full border border-gray-300 shadow-sm rounded py-3 focus:border-green-500 focus:ring-green-500" required />
                    <InputError class="mt-2" :message="userForm.errors.email" />
                </div>

                <div class="mb-4">
                    <InputLabel for="role" value="Role" />
                    <Dropdown v-model="userForm.role" inputId="role" class="w-full mt-1 border border-gray-300 shadow-sm" required :options="['admin', 'buyer', 'publisher']" />
                    <InputError class="mt-2" :message="userForm.errors.role" />
                </div>

                <PrimaryButton :class="{ 'opacity-25': userForm.processing }" :disabled="userForm.processing">
                    Send Invite
                </PrimaryButton>
            </form>
        </Dialog>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <DataTable :value="users" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem">
                    <Column field="id" header="ID" />
                    <Column field="first" header="First Name" />
                    <Column field="last" header="Last Name" />
                    <Column field="email" header="Email" />
                    <Column field="email_verified_at" header="Active (Y/N)">
                        <template #body="slotProps">
                            <i :class="slotProps.data.email_verified_at === null ? 'fa fa-times text-red-500' : 'fa fa-check'" />
                        </template>
                    </Column>
                    <Column field="roles.0.name" header="Role" />
                    <Column>
                        <template #body="slotProps">
                            <Button v-tooltip="'Resend Invite'" v-if="slotProps.data.email_verified_at === null" icon="fa fa-envelope" severity="secondary" @click="resendInvite(slotProps.data.id)" />
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import {ref} from "vue";
import {router, useForm} from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Dropdown from "primevue/dropdown";
import InputText from "primevue/inputtext";

const props = defineProps({
    users: Array,
})

const userDialog = ref(false);
const userForm = useForm({
    first: null,
    last: null,
    email: null,
    role: null,
})

const submit = () => {
    userForm.post(route('users.create-user'), {
        onSuccess: () => {
            userDialog.value = false;
            userForm.reset();
        }
    })
};

const resendInvite = (userId) => {
    router.post(route('user.resend-invite', userId));
}
</script>

<style scoped>

</style>
