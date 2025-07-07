<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2'; // Import SweetAlert2 for notifications

// Define the type for a company
interface merek {
    id: number;
    nama_merek: string;
}

// Define the props this component expects
interface Props {
    mereks: merek[]; // An array of company objects
}

// Destructure the props
const props = defineProps<Props>();

const form = useForm({});
function deleteMerek(id: number) {
    if (confirm('Apakah Anda yakin ingin menghapus merek ini?')) {
        form.delete(`/merek/${id}`, {
            onSuccess: () => {
                // Opsional: Tampilkan pesan sukses atau redirect
                console.log('Merek berhasil dihapus');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Merek berhasil dihapus.',
                    timer: 2000,
                    showConfirmButton: false,
                });
            },
            onError: (errors) => {
                // Tangani error yang terjadi saat penghapusan
                console.error('Terjadi kesalahan saat menghapus merek:', errors);
            },
        });
    }
}


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Merek',
        href: '/merek',
    },
];
</script>

<template>

    <Head title="Merek" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div>
                <Link href="/merek/create"
                    class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-green-500 focus:ring-4 focus:outline-none focus:ring-green-500 dark:bg-green-400">
                Create Merek</Link>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray500 dark:text-gray-400">
                    <thead
                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 border-2">
                        <tr>
                            <th scope="col" class="px-6 py-3 border-2">No.</th>
                            <th scope="col" class="px-6 py-3 border-2">Nama Merek</th>
                            <th scope="col" class="px-6 py-3 border-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(merek, index) in props.mereks" :key="merek.id"
                            class="add-bg-white add:dark:bg-gray-800 even:bg-gray-50 dark:even:bg-gray-700">
                            <td class="border px-6 py-2 font-medium text-gray-600 dark:text-gray-300">{{ index + 1 }}
                            </td>
                            <td class="border px-6 py-2 text-gray-600 dark:text-gray-300">{{ merek.nama_merek
                                }}
                            </td>
                            <td class="border px-6 py-2">
                                <Link :href="`/merek/${merek.id}/edit`"
                                    class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg ">Edit</Link>
                                <button @click="deleteMerek(merek.id)"
                                    class="px-3 py-2 mx-2 text-xs font-medium text-white bg-red-700 rounded-lg">Delete
                                </button>
                            </td>
                        </tr>
                        <tr v-if="props.mereks.length === 0">
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada data merek.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
