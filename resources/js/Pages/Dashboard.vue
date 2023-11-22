<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Message from "primevue/message";
import {router, useForm, usePage} from "@inertiajs/vue3";
import {computed, ref} from "vue";
import axios from "axios";
import {useToast} from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import InputText from "primevue/inputtext";
import InputError from "@/Components/InputError.vue";
const toast = useToast();
const confirm = useConfirm();

const page = usePage();
const user_role = computed(() => page.props.user_role);

const props = defineProps({
    report: Array,
    tokens: Object,
})

const webhookMessage = ref(null);
const createWebhook = () => {
    axios.get(route('dashboard.create-webhook')).then(res => {
        if(res.data.status === 'success') {
            router.reload();

            webhookMessage.value = res.data.token;
            toast.add({
                severity: 'success',
                summary: 'Operation Successful.',
                life: 4000,
            })
        }
    }).catch(err => {
        toast.add({
            severity: 'error',
            summary: 'ERROR: Something went wrong. Try again later.',
            life: 4000,
        })
    })
}

const buyerForm = useForm({
    webhook: null,
})

const updateWebhookUrl = () => {
    buyerForm.post(route('dashboard.update-buyer-endpoint'));
}

const requestNewToken = () => {
    confirm.require({
        message: 'Your existing token will no longer work and must be replaced in your outbound requests to our system with the new token, which will be provided should you proceed.',
        header: 'Confirmation Required.',
        icon: 'pi pi-exclamation-triangle',
        acceptLabel: 'Proceed',
        acceptClass: 'bg-green-600 p-4 text-white',
        rejectLabel: 'Go Back',
        rejectClass: 'bg-red-600 p-4 text-white mx-4',
        accept: () => {
            createWebhook();
        },
    });
}
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <h1 class="text-2xl font-medium text-gray-900">
                            Report
                        </h1>

                        <DataTable :value="report" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" stripedRows>
                            <Column field="caller_id" header="Caller ID" />
                            <Column field="external_id" header="Remote ID" />
                            <Column field="status" header="Status" />
                            <Column field="date" header="Date" />

                            <template #empty>
                                No results found.
                            </template>
                        </DataTable>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8" v-if="user_role === 'buyer' || user_role === 'publisher'">
                    <div :class="user_role === 'publisher' ? 'col-span-2 bg-white overflow-hidden shadow-xl sm:rounded-lg mt-12' : 'bg-white overflow-hidden shadow-xl sm:rounded-lg mt-12'">
                        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                            <h1 class="text-2xl font-medium text-gray-900">
                                Inbound Webhooks
                            </h1>

                            <div class="flex justify-start bg-green-600 text-white p-8 rounded-lg gap-4 align-center items-center mt-2 mb-2" v-if="webhookMessage !== null">
                                <i class="fa fa-check"></i>
                                <span>SUCCESS! Please COPY the following authentication token and follow the instructions below. This will only be displayed ONCE: {{ webhookMessage }}</span>
                            </div>

                            <div v-if="tokens.length < 1">
                                <Message severity="info" :closable="false">Get started by creating your unique webhook.
                                    <span v-if="user_role === 'buyer'">You'll be provided with a unique, protected webhook where you'll be able to post contact updates</span>
                                    <span v-else>You'll be provided with a unique, protected webhook that will allow you to post new contacts, which will be attributed to your account. After you create your webhook, your dashboard will update with metrics performed on each of your contacts received.</span>
                                </Message>

                                <button v-tooltip="'Get started with this button.'" class="bg-green-600 p-4 text-white font-bold rounded-lg hover:bg-green-300 hover:text-black" @click="createWebhook">Create Webhook & Get Instructions <i class="fa fa-check"></i></button>
                            </div>
                            <div v-else>
                                <div class="border-2 border-dashed p-4 bg-gray-100 mt-4 mb-4">
                                    <strong>WEBHOOK INSTRUCTIONS:</strong>
                                    <ul class="list-decimal px-4">
                                        <li>Using your token, add it to your "Authorization" header in your outgoing HTTP POST request. Preface the header with 'Bearer'.</li>
                                        <ul>
                                            <li>EXAMPLE AUTHORIZATION HEADER: <i>Bearer {{ webhookMessage ? webhookMessage : 'Bearer 123_YOUR_TOKEN_HERE_0987'}}</i></li>
                                        </ul>
                                        <li v-if="user_role === 'publisher'">Post non-billed contacts to Green Gorilla. If a conversion occurs, you'll be notified on your dashboard (here.)</li>
                                        <li v-else-if="user_role === 'buyer'">Post contact updates to Green Gorilla. <u>Please ensure you POST updates</u> no matter the disposition - BILLED or UNBILLED, please notify us.</li>
                                        <ul v-else-if="user_role === 'buyer'">
                                            <li>Post `caller_id` and `status`. `status` must be either "billed" or "nonbillable"</li>
                                        </ul>
                                    </ul>

                                    <br><br>
                                    <strong>SAMPLE REQUEST (CURL):</strong>
                                    <br>

                                    <code v-if="user_role === 'publisher'">
                                        curl -X POST 'https://greengorillagrp.com/api/publisher-data' \<br>
                                        -H 'Content-Type: application/json' \<br>
                                        -H 'Authorization: Bearer {{ webhookMessage ? webhookMessage : 'Bearer 123_YOUR_TOKEN_HERE_0987'}}' \<br>
                                        -d '{"caller_id":"999-999-9999","identifier": "your_optional_identifier"}'
                                    </code>

                                    <code v-else>
                                        curl -X POST 'https://greengorillagrp.com/api/caller-update' \<br>
                                        -H 'Content-Type: application/json' \<br>
                                        -H 'Authorization: Bearer {{ webhookMessage ? webhookMessage : 'Bearer 123_YOUR_TOKEN_HERE_0987'}}' \<br>
                                        -d '{"caller_id":"999-999-9999","status":"billed"}'
                                    </code>
                                </div>
                                <DataTable :value="tokens">
                                    <Column field="name" header="Token Type" />
                                    <Column field="last_used_at" header="Last Used Date" />
                                    <Column>
                                        <template #body="slotProps">
                                            <button class="bg-red-700 text-white px-3 py-2 rounded" v-tooltip="'Delete & request new token.'" @click="requestNewToken"><i class="fa fa-times" /></button>
                                        </template>
                                    </Column>
                                </DataTable>
                            </div>

                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-12" v-if="user_role === 'buyer'">
                        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                            <h1 class="text-2xl font-medium text-gray-900 mb-4">
                                Outbound Webhooks
                            </h1>

                            <p>In order for us to send you contacts, please provide us <u>your POST webhook url.</u></p>

                            <p class="mt-4">We will send you the following form field: `caller_id`</p>
                            <div class="border-2 border-dashed p-4 bg-gray-100 mt-4 mb-4">
                                <strong>Sample JSON Request Body from US to YOU:</strong><br>
                                <code>
                                    {"caller_id": "999-999-9999"}
                                </code>
                            </div>

                            <InputText v-model="buyerForm.webhook" class="w-full border-2 rounded-lg border-gray-800 px-4 py-3" placeholder="Your Webhook URL" />
                            <InputError class="mt-2" :message="buyerForm.errors.webhook" />
                            <button class="px-8 py-2 bg-black text-white rounded-lg mt-4" @click="updateWebhookUrl" :class="{ 'opacity-25': buyerForm.processing }" :disabled="buyerForm.processing">Update Webhook URL</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
