<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
// import PlaceholderPattern from '../components/PlaceholderPattern.vue'; // Pastikan komponen ini tersedia

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <PlaceholderPattern class="mb-4">
            <div class="flex justify-center items-center h-full p-4">
                <h2
                    class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl text-center font-extrabold text-gray-900 dark:text-white leading-tight">
                    Selamat Datang di Database Penumpang dan Angkutan Barang
                </h2>
            </div>
        </PlaceholderPattern>

        <PlaceholderPattern>
            <div class="flex justify-center items-center h-full p-4">
                <img src="/business.png" alt="Business" class="max-w-full h-auto rounded shadow" />
            </div>
        </PlaceholderPattern>

    </AppLayout>
</template>
