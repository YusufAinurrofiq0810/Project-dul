<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';

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
    id: number; // Asumsi ada ID untuk setiap angkutan
    perusahaan: Perusahaan; // Sekarang ini adalah objek Perusahaan lengkap
    TNKB: string; // Nomor TNK
    No_uji: string; // Nomor Uji
    No_KP: string; // Nomor KP
    No_NIB: string; // Nomor NIB
    No_SK: string; // Nomor SK
    Tanggal_SK: string; // Tanggal SK (bisa string atau Date)
    merek: Merek; // Sekarang ini adalah objek Merek lengkap
    alamat: string; // Alamat
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

// Definisikan tipe untuk props komponen ini
interface Props {
    angkutans: PaginatedAngkutans; // Sekarang mengharapkan struktur paginated dengan relasi
}

// Destrukturisasi props
const props = defineProps<Props>();

const form = useForm({});
function deleteAngkutan(id: number) {
    if (confirm('Apakah Anda yakin ingin menghapus angkutan ini?')) {
        form.delete(`/angkutan/${id}`, {
            onSuccess: () => {
                console.log('Angkutan berhasil dihapus');
            },
            onError: (errors) => {
                console.error('Terjadi kesalahan saat menghapus angkutan:', errors);
            },
        });
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
            <div>
                <Link href="/angkutan/create"
                    class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-green-500 focus:ring-4 focus:outline-none focus:ring-green-500 dark:bg-green-400">
                Buat Angkutan</Link>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead
                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 border-2">
                        <tr>
                            <th scope="col" class="px-6 py-3 border-2">ID</th>
                            <th scope="col" class="px-6 py-3 border-2">Nama Perusahaan</th>
                            <th scope="col" class="px-6 py-3 border-2">TNKB</th>
                            <th scope="col" class="px-6 py-3 border-2">No. Uji</th>
                            <th scope="col" class="px-6 py-3 border-2">No. KP</th>
                            <th scope="col" class="px-6 py-3 border-2">No. NIB</th>
                            <th scope="col" class="px-6 py-3 border-2">No. SK</th>
                            <th scope="col" class="px-6 py-3 border-2">Tanggal SK</th>
                            <th scope="col" class="px-6 py-3 border-2">Merek</th>
                            <th scope="col" class="px-6 py-3 border-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="angkutan in props.angkutans.data" :key="angkutan.id"
                            class="add-bg-white add:dark:bg-gray-800 even:bg-gray-50 dark:even:bg-gray-700">
                            <td class="border px-6 py-2 font-medium text-gray-900 dark:text-gray-white">{{ angkutan.id
                                }}</td>
                            <td class="border px-6 py-2 text-gray-600 dark:text-gray-300">
                                {{ angkutan.perusahaan.nama_perusahaan }}
                            </td>
                            <td class="border px-6 py-2 text-gray-600 dark:text-gray-300">{{ angkutan.TNKB }}</td>
                            <td class="border px-6 py-2 text-gray-600 dark:text-gray-300">{{ angkutan.No_uji }}</td>
                            <td class="border px-6 py-2 text-gray-600 dark:text-gray-300">{{ angkutan.No_KP }}</td>
                            <td class="border px-6 py-2 text-gray-600 dark:text-gray-300">{{ angkutan.No_NIB }}</td>
                            <td class="border px-6 py-2 text-gray-600 dark:text-gray-300">{{ angkutan.No_SK }}</td>
                            <td class="border px-6 py-2 text-gray-600 dark:text-gray-300">{{ angkutan.Tanggal_SK }}</td>
                            <td class="border px-6 py-2 text-gray-600 dark:text-gray-300">
                                {{ angkutan.merek.nama_merek }}
                            </td>
                            <td class="border px-6 py-2">
                                <Link :href="`/angkutan/${angkutan.id}/edit`"
                                    class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg ">Edit</Link>
                                <Link :href="`/angkutan/${angkutan.id}`"
                                    class="px-3 py-2 mx-2 text-xs font-medium text-white bg-green-700 rounded-lg">Detail
                                </Link>
                                <Button @click="deleteAngkutan(angkutan.id)"
                                    class="px-3 py-2 mx-2 text-xs font-medium text-white bg-red-700 rounded-lg">Hapus
                                </Button>
                            </td>
                        </tr>
                        <tr v-if="props.angkutans.data.length === 0">
                            <td colspan="16" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
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
                                'bg-blue-600 text-white border-blue-600': link.active,
                                'bg-white border-gray-300 text-gray-700 hover:bg-gray-50': !link.active,
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
