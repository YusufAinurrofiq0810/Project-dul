<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3'; // No need for useForm in a show view

// Define types for related models
interface Perusahaan {
    id: number;
    nama_perusahaan: string;
}

interface Merek {
    id: number;
    nama_merek: string;
}

// Define type for Angkutan model (assuming it matches your database structure)
interface Angkutan {
    id: number;
    perusahaan_id: number;
    merek_id: number;
    TNKB: string;
    No_uji: string;
    No_KP: string;
    No_NIB: string;
    No_SK: string;
    NO_Mesin: string | null;
    Tanggal_SK: string; // Assuming YYYY-MM-DD format
    Kode_Trayek: string;
    No_Seri: string;
    Daya_Angkut: number;
    KG: number;
    Tahun_Pembuatan: string; // Stored as 'YYYY-01-01' in DB, but we'll extract the year
    Alamat: string;
    created_at: string;
    updated_at: string;
}

// Define props for the component
interface Props {
    angkutan: Angkutan; // The single Angkutan object to display
    perusahaans_list: Perusahaan[]; // List of all companies to find the name
    mereks_list: Merek[]; // List of all brands to find the name
}

const props = defineProps<Props>();

// Log the props to the console for debugging
console.log('Props received by Angkutan/Show:', props);

// Dynamically set breadcrumbs for the show view
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Angkutan',
        href: '/angkutan',
    },
    {
        title: `Detail Angkutan: ${props.angkutan.TNKB}`,
        href: `/angkutan/${props.angkutan.id}`,
    }
];

// Helper function to find the company name by ID
const getPerusahaanName = (id: number): string => {
    const perusahaan = props.perusahaans_list.find(p => p.id === id);
    return perusahaan ? perusahaan.nama_perusahaan : 'N/A';
};

// Helper function to find the brand name by ID
const getMerekName = (id: number): string => {
    const merek = props.mereks_list.find(m => m.id === id);
    return merek ? merek.nama_merek : 'N/A';
};

// Extract just the year from Tahun_Pembuatan for display
const displayTahunPembuatan = props.angkutan.Tahun_Pembuatan ?
    new Date(props.angkutan.Tahun_Pembuatan).getFullYear().toString() : 'N/A';

</script>

<template>

    <Head :title="`Detail Angkutan: ${props.angkutan.TNKB}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex justify-between items-center">
                <Link href="/angkutan"
                    class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-green-500 focus:ring-4 focus:outline-none focus:ring-green-500 dark:bg-green-400">
                Kembali</Link>
                <Link :href="`/angkutan/${props.angkutan.id}/edit`"
                    class="px-3 py-2 text-xs font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-500 dark:bg-yellow-400">
                Edit</Link>
            </div>

            <div class="overflow-x-auto p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
                <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Detail Angkutan</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6 text-gray-700 dark:text-gray-300">
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">Nama Perusahaan:</span>
                        <span>{{ getPerusahaanName(props.angkutan.perusahaan_id) }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">Merek:</span>
                        <span>{{ getMerekName(props.angkutan.merek_id) }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">TNKB:</span>
                        <span>{{ props.angkutan.TNKB }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">No. Uji:</span>
                        <span>{{ props.angkutan.No_uji }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">No. KP:</span>
                        <span>{{ props.angkutan.No_KP }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">No. NIB:</span>
                        <span>{{ props.angkutan.No_NIB }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">No. SK:</span>
                        <span>{{ props.angkutan.No_SK }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">No. Mesin:</span>
                        <span>{{ props.angkutan.NO_Mesin || 'N/A' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">Tanggal SK:</span>
                        <span>{{ props.angkutan.Tanggal_SK }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">Kode Trayek:</span>
                        <span>{{ props.angkutan.Kode_Trayek }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">No. Seri:</span>
                        <span>{{ props.angkutan.No_Seri }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">Daya Angkut:</span>
                        <span>{{ props.angkutan.Daya_Angkut }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">KG:</span>
                        <span>{{ props.angkutan.KG }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">Tahun Pembuatan:</span>
                        <span>{{ displayTahunPembuatan }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">Alamat:</span>
                        <span>{{ props.angkutan.Alamat }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">Dibuat Pada:</span>
                        <span>{{ new Date(props.angkutan.created_at).toLocaleString() }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">Diperbarui Pada:</span>
                        <span>{{ new Date(props.angkutan.updated_at).toLocaleString() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
