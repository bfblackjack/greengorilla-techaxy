<script setup>
import { Link } from '@inertiajs/vue3';
import logo from "../../../public/img/logo.png";
</script>

<template>
    <Link :href="'/'">
        <img :src="logo" class="w-42 h-32" />
    </Link>
</template>
