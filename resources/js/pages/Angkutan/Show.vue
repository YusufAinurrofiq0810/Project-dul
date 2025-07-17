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
interface JenisAngkutan {
    id: number;
    Nama_Jenis_Angkutan: string;
}

// Define type for Angkutan model (assuming it matches your database structure)
interface Angkutan {
    id: number;
    perusahaan_id: number;
    merek_id: number;
    jenis_angkutan: JenisAngkutan;
    Masa_berlaku_KP_Start_date: string; // Assuming YYYY-MM-DD format
    Masa_berlaku_KP_End_date: string; // Assuming YYYY-MM-DD format
    Masa_berlaku_SK_Start_date: string; // Assuming YYYY-MM-DD format
    Masa_berlaku_SK_End_date: string; // Assuming YYYY-MM-DD format
    Keterangan_Perizinan: boolean;
    tipe: string;
    Jenis_BBM: string;
    Masa_Berlaku_STNK: string; // Assuming YYYY-MM-DD format
    No_Rangka: string;
    No_Trayek: string;
    TNKB: string;
    No_uji: string;
    No_NIB: string;
    No_SK: string;
    No_Mesin: string | null;
    trayek: string;
    No_Seri: string;
    keterangan: string;
    Daya_Angkut_Orang: number;
    Daya_Angkut_KG: number;
    Tahun_Pembuatan: string; // Stored as 'YYYY-01-01' in DB, but we'll extract the year
    Alamat: string;
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
        title: `Detail Angkutan`,
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

const getJenisAngkutanName = (id: number): string => {
    const jenis = props.angkutan.jenis_angkutan;
    return jenis ? jenis.Nama_Jenis_Angkutan : 'N/A';
};

// Extract just the year from Tahun_Pembuatan for display
const displayTahunPembuatan = props.angkutan.Tahun_Pembuatan ?
    new Date(props.angkutan.Tahun_Pembuatan).getFullYear().toString() : 'N/A';

</script>

<template>

    <Head :title="`Detail Angkutan:`" />
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
                        <span class="font-semibold text-gray-500 dark:text-gray-400">Jenis Kendaraan:</span>
                        <span>{{ getJenisAngkutanName(props.angkutan.jenis_angkutan.id) }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">Masa Berlaku KP:</span>
                        <span>{{ props.angkutan.Masa_berlaku_KP_Start_date }} - {{
                            props.angkutan.Masa_berlaku_KP_End_date }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">Masa Berlaku SK:</span>
                        <span>{{ props.angkutan.Masa_berlaku_SK_Start_date }} - {{
                            props.angkutan.Masa_berlaku_SK_End_date }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">Keterangan Perizinan:</span>
                        <span>{{ props.angkutan.Keterangan_Perizinan ? 'Aktif' : 'Tidak Aktif' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">tipe:</span>
                        <span>{{ props.angkutan.tipe }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">Jenis BBM:</span>
                        <span>{{ props.angkutan.Jenis_BBM }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">Masa Berlaku STNK:</span>
                        <span>{{ props.angkutan.Masa_Berlaku_STNK }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">No. Rangka:</span>
                        <span>{{ props.angkutan.No_Rangka }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">Keterangan:</span>
                        <span>{{ props.angkutan.keterangan }}</span>
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
                        <span class="font-semibold text-gray-500 dark:text-gray-400">No. NIB:</span>
                        <span>{{ props.angkutan.No_NIB }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">No. SK:</span>
                        <span>{{ props.angkutan.No_SK }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">No. Mesin:</span>
                        <span>{{ props.angkutan.No_Mesin || 'N/A' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">Trayek:</span>
                        <span>{{ props.angkutan.trayek }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">No. Seri:</span>
                        <span>{{ props.angkutan.No_Seri }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">Daya Angkut:</span>
                        <span>{{ props.angkutan.Daya_Angkut_Orang }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">KG:</span>
                        <span>{{ props.angkutan.Daya_Angkut_KG }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">Tahun Pembuatan:</span>
                        <span>{{ displayTahunPembuatan }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">Alamat:</span>
                        <span>{{ props.angkutan.Alamat }}</span>
                    </div>
                </div>
            </div>
        </div>

    </AppLayout>
</template>
