<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-200">
        <div>
            <slot name="logo" />
        </div>

        <AuthenticationCardLogo />
        <div class="w-full sm:max-w-lg mt-6 px-6 py-4 bg-white shadow-sm overflow-hidden sm:rounded-lg">
            <slot />
        </div>
    </div>
</template>

<script>
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";

export default {
    name: "GuestLayout",
    components: {AuthenticationCardLogo}
}
</script>

<style scoped>

</style>
