<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input'; // Asumsi komponen Input dari shadcn/ui atau sejenisnya
import { Label } from '@/components/ui/label'; // Asumsi komponen Label dari shadcn/ui atau sejenisnya
import Button from '@/components/ui/button/Button.vue'; // Asumsi komponen Button dari shadcn/ui atau sejenisnya
import Swal from 'sweetalert2'; // Uncomment if you want to use SweetAlert2 for notifications
// import InputError from '@/components/InputError.vue'; // Asumsi ini adalah komponen untuk menampilkan error validasi

// Define the type for a company
interface Perusahaan {
    id: number;
    nama_perusahaan: string; // Changed back to nama_perusahaan
}

// Define the props this component expects, now including the 'perusahaan' object for editing
interface Props {
    perusahaan: Perusahaan; // The company object to be edited
}

// Destructure the props
const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Perusahaan',
        href: '/perusahaan',
    },
    {
        title: 'Edit Perusahaan', // Updated breadcrumb for edit context
        href: `/perusahaan/${props.perusahaan.id}/edit`,
    },
];

// Inisialisasi form dengan field 'nama_perusahaan' dan mengisi dengan data yang ada
const form = useForm({
    // Initialize with existing data from props.perusahaan
    nama_perusahaan: props.perusahaan.nama_perusahaan, // Changed to nama_perusahaan
});

// Fungsi untuk menangani submit form
function submit() {
    // Mengirim data form menggunakan metode PUT untuk update
    form.put(`/perusahaan/${props.perusahaan.id}`, { // Mengirim data form ke endpoint update dengan ID
        onSuccess: () => {
            // Opsional: Tampilkan pesan sukses atau redirect
            console.log('Perusahaan berhasil diperbarui!');
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Perusahaan berhasil diperbarui.',
                timer: 2000,
                showConfirmButton: false,
            });
            // You might want to redirect to the list page or show page after successful update
            // e.g., Inertia.visit('/perusahaan');
        },
        onError: (errors) => {
            // Tangani error validasi atau error lainnya dari server
            console.error('Terjadi kesalahan saat memperbarui perusahaan:', errors);
            // Inertia.js secara otomatis akan mengisi `form.errors`
            // yang bisa Anda tampilkan di samping input field
        },
    });
}
</script>

<template>

    <Head :title="`Edit Perusahaan: ${form.nama_perusahaan}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div>
                <Link href="/perusahaan"
                    class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-green-500 focus:ring-4 focus:outline-none focus:ring-green-500 dark:bg-green-400">
                Kembali</Link>
            </div>

            <div class="overflow-x-auto p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="grid gap-2">
                        <Label for="nama_perusahaan">Nama Perusahaan</Label> <Input id="nama_perusahaan" type="text"
                            class="mt-1 block w-full md:w-1/2 lg:w-1/3 p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required autocomplete="organization" placeholder="Masukkan nama perusahaan"
                            v-model="form.nama_perusahaan" />
                    </div>
                    <div class="mt-4">
                        <Button type="submit" :disabled="form.processing"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600">
                            {{ form.processing ? 'Menyimpan...' : 'Perbarui Perusahaan' }} </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
