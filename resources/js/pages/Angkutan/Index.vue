<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue'; // Import ref and watch
import { router } from '@inertiajs/vue3'; // Import router for manual visits
import Swal from 'sweetalert2'; // Import SweetAlert2 for notifications

// Definisikan tipe untuk objek Perusahaan (relasi)
interface Perusahaan {
    id: number;
    nama_perusahaan: string;
}

// Definisikan tipe untuk objek Merek (relasi)
interface Merek {
    id: number;
    nama_merek: string;
}

interface JenisAngkutan {
    id: number;
    Nama_Jenis_Angkutan: string;
}
// Definisikan tipe untuk objek Angkutan, termasuk relasi JenisAngkutan
// Definisikan tipe untuk objek Angkutan, termasuk relasi yang dimuat
interface Angkutan {
    id: number;
    perusahaan: Perusahaan;
    merek: Merek;
    jenis_angkutan: JenisAngkutan;
    TNKB: string;
    Keterangan_Perizinan: boolean;
    No_NIB: string;
}

// Define the type for pagination links (from Laravel/Inertia)
interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

// Define the type for the paginated data structure
interface PaginatedAngkutans {
    data: Angkutan[]; // The actual Angkutan data for the current page
    links: PaginationLink[]; // Pagination links (prev, 1, 2, next, etc.)
    current_page: number;
    last_page: number;
    from: number | null;
    to: number | null;
    total: number;
}

interface Props {
    angkutans: PaginatedAngkutans;
    // Add a prop for the current filter value, so it persists on page load
    filterPerusahaan: string | null;
    filterJenisAngkutan: string | null;
    perusahaanOptions: Perusahaan[];
    jenisAngkutanOptions: JenisAngkutan[];
}

const props = defineProps<Props>();

const deleteForm = useForm({});

const importForm = useForm({
    file: null as File | null,
});

// Reactive variable for the company name filter
const filterPerusahaan = ref(props.filterPerusahaan || '');
const filterJenisAngkutan = ref(props.filterJenisAngkutan || '');

// Watch for changes in filterPerusahaan and update the URL
watch([filterPerusahaan, filterJenisAngkutan], ([perusahaan, jenisAngkutan]) => {
    router.get('/angkutan', {
        perusahaan: perusahaan || null,
        jenis_angkutan: jenisAngkutan || null
    }, {
        preserveState: true, // Keep the current scroll position
        replace: true,       // Replace the current history entry
    });
});

function clearFilters() {
    filterPerusahaan.value = '';
    filterJenisAngkutan.value = '';
}

function deleteAngkutan(id: number) {
    console.warn('Konfirmasi Penghapusan: Apakah Anda yakin ingin menghapus angkutan ini?');
    if (confirm('Apakah Anda yakin ingin menghapus angkutan ini?')) { // Added a proper confirmation
        deleteForm.delete(`/angkutan/${id}`, {
            onSuccess: () => {
                console.log('Angkutan berhasil dihapus');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Angkutan berhasil dihapus.',
                    timer: 2000,
                    showConfirmButton: false,
                });
            },
            onError: (errors) => {
                console.error('Terjadi kesalahan saat menghapus angkutan:', errors);
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat menghapus angkutan.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                });
            },
        });
    }
}

function handleFileUpload(event: Event) {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        importForm.file = target.files[0];
    }
}

function submitImport() {
    if (importForm.file) {
        importForm.post('/angkutan/import', {
            onSuccess: () => {
                console.log('Data angkutan berhasil diimpor!');
                importForm.reset(); // Reset form setelah sukses
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data angkutan berhasil diimpor.',
                    timer: 2000,
                    showConfirmButton: false,
                });
            },
            onError: (errors) => {
                console.error('Terjadi kesalahan saat mengimpor angkutan:', errors);
                // Tampilkan error ke pengguna
            },
        });
    } else {
        console.warn('Silakan pilih file untuk diimpor.');
        // Tampilkan pesan ke pengguna bahwa file belum dipilih
    }
}

function downloadTemplate() {
    fetch(route('angkutan.downloadImportTemplate'))
        .then(async response => {
            if (!response.ok) throw new Error('Download failed');

            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'template.xlsx';
            a.click();
            window.URL.revokeObjectURL(url);
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Gagal mengunduh template.',
                timer: 2000,
                showConfirmButton: false,
            });
        });
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Angkutan',
        href: '/angkutan',
    },
];
</script>

<template>

    <Head title="Angkutan" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
            <!-- Action Buttons Section -->
            <div class="flex flex-wrap items-center gap-4">
                <a href="/angkutan/create"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-colors">
                    Buat Angkutan
                </a>

                <a href="/angkutan/export"
                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 transition-colors">
                    Ekspor Angkutan
                </a>

                <a  @click="downloadTemplate"
                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 transition-colors">
                    Download Template Import Angkutan
                </a>

                <form @submit.prevent="submitImport" class="flex items-center gap-2">
                    <input type="file" @change="handleFileUpload" accept=".xls,.xlsx"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                    <Button type="submit" :disabled="importForm.processing || !importForm.file"
                        class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:ring-4 focus:outline-none focus:ring-purple-300 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        {{ importForm.processing ? 'Mengimpor...' : 'Import Angkutan' }}
                    </Button>
                </form>

                <div v-if="importForm.errors.file" class="text-red-500 text-sm">
                    {{ importForm.errors.file }}
                </div>
            </div>

            <!-- Filter Section -->
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">
                        Filter Data:
                    </h3>

                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 flex-1">
                        <!-- Company Filter -->
                        <div class="flex flex-col gap-1 min-w-0 flex-1">
                            <label for="perusahaanFilter" class="text-xs font-medium text-gray-600 dark:text-gray-400">
                                Nama Perusahaan
                            </label>
                            <select id="perusahaanFilter" v-model="filterPerusahaan"
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-colors">
                                <option value="">Semua Perusahaan</option>
                                <option v-for="perusahaan in props.perusahaanOptions"
                                    :key="perusahaan.id" :value="perusahaan.nama_perusahaan">
                                    {{ perusahaan.nama_perusahaan }}
                                </option>
                            </select>
                        </div>

                        <!-- Vehicle Type Filter -->
                        <div class="flex flex-col gap-1 min-w-0 flex-1">
                            <label for="jenisAngkutanFilter"
                                class="text-xs font-medium text-gray-600 dark:text-gray-400">
                                Jenis Angkutan
                            </label>
                            <select id="jenisAngkutanFilter" v-model="filterJenisAngkutan"
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-colors">
                                <option value="">Semua Jenis Angkutan</option>
                                <option v-for="jenis in props.jenisAngkutanOptions"
                                    :key="jenis.id" :value="jenis.Nama_Jenis_Angkutan">
                                    {{ jenis.Nama_Jenis_Angkutan }}
                                </option>
                            </select>
                        </div>

                        <!-- Clear Filters Button -->
                        <div class="flex items-end">
                            <button @click="clearFilters"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-gray-200 focus:outline-none dark:bg-gray-600 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-700 dark:focus:ring-gray-700 transition-colors whitespace-nowrap">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Clear Filters
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Active Filters Display -->
                <div v-if="filterPerusahaan || filterJenisAngkutan"
                    class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-medium text-gray-600 dark:text-gray-400">Filter aktif:</span>
                        <span v-if="filterPerusahaan"
                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">
                            Perusahaan: {{ filterPerusahaan }}
                        </span>
                        <span v-if="filterJenisAngkutan"
                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300">
                            Jenis: {{ filterJenisAngkutan }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="overflow-x-auto rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-4 border-r border-gray-200 dark:border-gray-600">No</th>
                            <th scope="col" class="px-6 py-4 border-r border-gray-200 dark:border-gray-600">Jenis
                                Kendaraan</th>
                            <th scope="col" class="px-6 py-4 border-r border-gray-200 dark:border-gray-600">Nama
                                Perusahaan</th>
                            <th scope="col" class="px-6 py-4 border-r border-gray-200 dark:border-gray-600">TNKB</th>
                            <th scope="col" class="px-6 py-4 border-r border-gray-200 dark:border-gray-600">No. NIB</th>
                            <th scope="col" class="px-6 py-4 border-r border-gray-200 dark:border-gray-600">Keterangan
                                Izin</th>
                            <th scope="col" class="px-6 py-4 border-r border-gray-200 dark:border-gray-600">Merek</th>
                            <th scope="col" class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(angkutan, key) in props.angkutans.data" :key="angkutan.id"
                            class="bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <td
                                class="border-r border-gray-200 dark:border-gray-600 px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ (props.angkutans.from || 1) + key }}
                            </td>
                            <td
                                class="border-r border-gray-200 dark:border-gray-600 px-6 py-4 text-gray-600 dark:text-gray-300">
                                {{ angkutan.jenis_angkutan?.Nama_Jenis_Angkutan || 'PROPERRTI KOSONG' }}
                            </td>
                            <td
                                class="border-r border-gray-200 dark:border-gray-600 px-6 py-4 text-gray-600 dark:text-gray-300">
                                {{ angkutan.perusahaan?.nama_perusahaan || 'N/A' }}
                            </td>
                            <td
                                class="border-r border-gray-200 dark:border-gray-600 px-6 py-4 text-gray-600 dark:text-gray-300">
                                {{ angkutan.TNKB }}
                            </td>
                            <td
                                class="border-r border-gray-200 dark:border-gray-600 px-6 py-4 text-gray-600 dark:text-gray-300">
                                {{ angkutan.No_NIB }}
                            </td>
                            <td class="border-r border-gray-200 dark:border-gray-600 px-6 py-4">
                                <span :class="{
                                    'text-green-700 bg-green-100 dark:text-green-400 dark:bg-green-900': angkutan.Keterangan_Perizinan,
                                    'text-red-700 bg-red-100 dark:text-red-400 dark:bg-red-900': !angkutan.Keterangan_Perizinan
                                }" class="px-2 py-1 text-xs font-medium rounded-full">
                                    {{ angkutan.Keterangan_Perizinan ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td
                                class="border-r border-gray-200 dark:border-gray-600 px-6 py-4 text-gray-600 dark:text-gray-300">
                                {{ angkutan.merek?.nama_merek || 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <Link :href="`/angkutan/${angkutan.id}/edit`"
                                        class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors">
                                    Edit
                                    </Link>
                                    <Link :href="`/angkutan/${angkutan.id}`"
                                        class="px-3 py-1.5 text-xs font-medium text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors">
                                    Detail
                                    </Link>
                                    <Button @click="deleteAngkutan(angkutan.id)"
                                        class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors">
                                        Hapus
                                    </Button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="props.angkutans.data.length === 0">
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <p class="text-lg font-medium mb-2">Tidak ada data angkutan</p>
                                    <p class="text-sm">Coba ubah filter pencarian Anda atau tambah data baru</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Section -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    Menampilkan {{ props.angkutans.from || 0 }} - {{ props.angkutans.to || 0 }} dari {{
                        props.angkutans.total }} data
                </div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <template v-for="(link, key) in props.angkutans.links" :key="key">
                        <Link :href="link.url || '#'"
                            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors"
                            :class="{
                                'bg-blue-600 text-white border-blue-600 dark:bg-blue-500 dark:border-blue-500': link.active,
                                'bg-white border-gray-300 text-gray-700 hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600': !link.active,
                                'cursor-not-allowed opacity-50': !link.url,
                                'rounded-l-md': key === 0,
                                'rounded-r-md': key === props.angkutans.links.length - 1
                            }">
                        <span v-html="link.label"></span>
                        </Link>
                    </template>
                </nav>
            </div>
        </div>
    </AppLayout>
</template>
