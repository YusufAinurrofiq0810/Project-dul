<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2'; // Import SweetAlert2 for notifications

// Define the type for a company
interface Perusahaan {
    id: number;
    nama_perusahaan: string;
}

// Define the props this component expects
interface Props {
    perusahaans: Perusahaan[]; // An array of company objects
}

const form = useForm({});

// Destructure the props
const props = defineProps<Props>();

function deletePerusahaan(id: number) {
    if (confirm('Are you sure you want to delete this perusahaan?')) {
        form.delete(`/perusahaan/${id}`, {
            onSuccess: () => {
                // Optionally, you can show a success message or redirect
                console.log('Perusahaan deleted successfully');
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Perusahaan berhasil dihapus.',
                    timer: 2000,
                    showConfirmButton: false,
                });
            },
            onError: (errors) => {
                // Handle any errors that occur during deletion
                console.error('Error deleting perusahaan:', errors);

                let errorMessage = 'Terjadi kesalahan yang tidak terduga saat menghapus perusahaan.';
                if (errors && errors.delete_perusahaan) {
                    errorMessage = errors.delete_perusahaan;
                } else if (errors && Object.keys(errors).length > 0) {
                    errorMessage = Object.values(errors)[0] as string;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: errorMessage,
                    timer: 3000, // Give a bit more time for the user to read
                    showConfirmButton: true, // Keep the confirm button for manual closure
                });
            },
        });
    }
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Perusahaan',
        href: '/perusahaan',
    },
];
</script>

<template>

    <Head title="Perusahaan" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div>
                <Link href="/perusahaan/create"
                    class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-green-500 focus:ring-4 focus:outline-none focus:ring-green-500 dark:bg-green-400">
                Create Perusahaan</Link>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray500 dark:text-gray-400">
                    <thead
                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 border-2">
                        <tr>
                            <th scope="col" class="px-6 py-3 border-2">No.</th>
                            <th scope="col" class="px-6 py-3 border-2">Nama Perusahaan</th>
                            <th scope="col" class="px-6 py-3 border-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(perusahaan, index) in props.perusahaans" :key="perusahaan.id"
                            class="add-bg-white add:dark:bg-gray-800 even:bg-gray-50 dark:even:bg-gray-700">
                            <td class="border px-6 py-2 font-medium text-gray-600 dark:text-gray-300">{{ index + 1 }}
                            </td>
                            <td class="border px-6 py-2 text-gray-600 dark:text-gray-300">{{ perusahaan.nama_perusahaan
                                }}
                            </td>
                            <td class="border px-6 py-2">
                                <Link :href="`/perusahaan/${perusahaan.id}/edit`"
                                    class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg ">Edit</Link>
                                <button @click="deletePerusahaan(perusahaan.id)"
                                    class="px-3 py-2 mx-2 text-xs font-medium text-white bg-red-700 rounded-lg">Delete
                                </button>
                            </td>
                        </tr>
                        <tr v-if="props.perusahaans.length === 0">
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada data perusahaan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
