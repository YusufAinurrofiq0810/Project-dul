<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue'; // Import ref and watch
import { router } from '@inertiajs/vue3'; // Import router for manual visits

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

// Definisikan tipe untuk objek Angkutan, termasuk relasi yang dimuat
interface Angkutan {
    id: number;
    perusahaan: Perusahaan;
    merek: Merek;
    TNKB: string;
    No_uji: string;
    No_KP: string;
    No_NIB: string;
    No_SK: string;
    Tanggal_SK: string;
    Alamat: string;
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
}

const props = defineProps<Props>();

const deleteForm = useForm({});

const importForm = useForm({
    file: null as File | null,
});

// Reactive variable for the company name filter
const filterPerusahaan = ref(props.filterPerusahaan || '');

// Watch for changes in filterPerusahaan and update the URL
watch(filterPerusahaan, (value) => {
    router.get('/angkutan', { perusahaan: value }, {
        preserveState: true, // Keep the current scroll position
        replace: true,       // Replace the current history entry
    });
});

function deleteAngkutan(id: number) {
    console.warn('Konfirmasi Penghapusan: Apakah Anda yakin ingin menghapus angkutan ini?');
    if (confirm('Apakah Anda yakin ingin menghapus angkutan ini?')) { // Added a proper confirmation
        deleteForm.delete(`/angkutan/${id}`, {
            onSuccess: () => {
                console.log('Angkutan berhasil dihapus');
                // Tambahkan logika notifikasi sukses (misal: flash message)
            },
            onError: (errors) => {
                console.error('Terjadi kesalahan saat menghapus angkutan:', errors);
                // Tampilkan error ke pengguna
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
                // Tambahkan logika notifikasi sukses (misal: flash message)
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
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-wrap items-center gap-4">
                <a href="/angkutan/create"
                    class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-800">
                    Buat Angkutan</a>

                <a href="/angkutan/export"
                    class="px-3 py-2 text-xs font-medium text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-800">
                    Ekspor Angkutan</a>

                <form @submit.prevent="submitImport" class="flex items-center gap-2">
                    <input type="file" @change="handleFileUpload" accept=".xls,.xlsx"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" />
                    <Button type="submit" :disabled="importForm.processing || !importForm.file"
                        class="px-3 py-2 text-xs font-medium text-white bg-purple-700 rounded-lg hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-800">
                        {{ importForm.processing ? 'Mengimpor...' : 'Import Angkutan' }}
                    </Button>
                    <div v-if="importForm.errors.file" class="text-red-500 text-xs mt-1">
                        {{ importForm.errors.file }}
                    </div>
                </form>

                <div class="flex items-center gap-2 ml-auto">
                    <label for="perusahaanFilter" class="sr-only">Cari Perusahaan</label>
                    <input type="text" id="perusahaanFilter" v-model="filterPerusahaan"
                        placeholder="Filter Nama Perusahaan..."
                        class="block w-48 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                </div>
            </div>

            <div class="overflow-x-auto rounded-lg shadow">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead
                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 border-b-2">
                        <tr>
                            <th scope="col" class="px-6 py-3 border-r-2">Nomer</th>
                            <th scope="col" class="px-6 py-3 border-r-2">Nama Perusahaan</th>
                            <th scope="col" class="px-6 py-3 border-r-2">TNKB</th>
                            <th scope="col" class="px-6 py-3 border-r-2">No. Uji</th>
                            <th scope="col" class="px-6 py-3 border-r-2">No. KP</th>
                            <th scope="col" class="px-6 py-3 border-r-2">No. NIB</th>
                            <th scope="col" class="px-6 py-3 border-r-2">No. SK</th>
                            <th scope="col" class="px-6 py-3 border-r-2">Tanggal SK</th>
                            <th scope="col" class="px-6 py-3 border-r-2">Merek</th>
                            <th scope="col" class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(angkutan, key) in props.angkutans.data" :key="angkutan.id"
                            class="bg-white even:bg-gray-50 dark:bg-gray-800 dark:even:bg-gray-700 border-b">
                            <td class="border-r px-6 py-2 font-medium text-gray-900 dark:text-white">
                                {{ (props.angkutans.from || 1) + key }}
                            </td>
                            <td class="border-r px-6 py-2 text-gray-600 dark:text-gray-300">
                                {{ angkutan.perusahaan?.nama_perusahaan || 'N/A' }}
                            </td>
                            <td class="border-r px-6 py-2 text-gray-600 dark:text-gray-300">{{ angkutan.TNKB }}</td>
                            <td class="border-r px-6 py-2 text-gray-600 dark:text-gray-300">{{ angkutan.No_uji }}</td>
                            <td class="border-r px-6 py-2 text-gray-600 dark:text-gray-300">{{ angkutan.No_KP }}</td>
                            <td class="border-r px-6 py-2 text-gray-600 dark:text-gray-300">{{ angkutan.No_NIB }}</td>
                            <td class="border-r px-6 py-2 text-gray-600 dark:text-gray-300">{{ angkutan.No_SK }}</td>
                            <td class="border-r px-6 py-2 text-gray-600 dark:text-gray-300">{{ angkutan.Tanggal_SK }}
                            </td>
                            <td class="border-r px-6 py-2 text-gray-600 dark:text-gray-300">
                                {{ angkutan.merek?.nama_merek || 'N/A' }}
                            </td>
                            <td class="px-6 py-2 whitespace-nowrap">
                                <Link :href="`/angkutan/${angkutan.id}/edit`"
                                    class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 mr-2">
                                Edit</Link>
                                <Link :href="`/angkutan/${angkutan.id}`"
                                    class="px-3 py-2 text-xs font-medium text-white bg-green-700 rounded-lg hover:bg-green-800 mr-2">
                                Detail
                                </Link>
                                <Button @click="deleteAngkutan(angkutan.id)"
                                    class="px-3 py-2 text-xs font-medium text-white bg-red-700 rounded-lg hover:bg-red-800">Hapus
                                </Button>
                            </td>
                        </tr>
                        <tr v-if="props.angkutans.data.length === 0">
                            <td colspan="10" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada data angkutan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-center mt-4">
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <template v-for="(link, key) in props.angkutans.links" :key="key">
                        <Link :href="link.url || '#'"
                            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium" :class="{
                                'bg-blue-600 text-white border-blue-600 dark:bg-blue-500 dark:border-blue-500': link.active,
                                'bg-white border-gray-300 text-gray-700 hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600': !link.active,
                                'cursor-not-allowed opacity-50': !link.url, // Disable link if no URL
                            }">
                        <span v-html="link.label"></span>
                        </Link>
                    </template>
                </nav>
            </div>
        </div>
    </AppLayout>
</template>
