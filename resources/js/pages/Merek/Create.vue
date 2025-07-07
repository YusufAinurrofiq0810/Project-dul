<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Button from '@/components/ui/button/Button.vue';
import InputError from '@/components/InputError.vue'; // Pastikan ini ada dan berfungsi
import Swal from 'sweetalert2'; // <-- Import SweetAlert2 di sini

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Merek create',
        href: '/merek/create',
    },
];

// Inisialisasi form dengan field 'nama_merek'
const form = useForm({
    nama_merek: "",
});

// Fungsi untuk menangani submit form
function submit() {
    form.post('/merek', {
        onSuccess: () => {
            // Reset form setelah berhasil submit
            form.reset();
            // Tampilkan pesan sukses menggunakan SweetAlert2
            Swal.fire({
                title: 'Berhasil!',
                text: 'Merek berhasil ditambahkan.',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        },
        onError: (errors) => {
            // Tangani error validasi atau error lainnya dari server
            console.error('Terjadi kesalahan saat membuat merek:', errors);

            // Tampilkan pesan error menggunakan SweetAlert2
            // Anda bisa menampilkan pesan error umum atau detail dari `errors`
            let errorMessage = 'Terjadi kesalahan saat menyimpan merek.';
            if (errors && Object.keys(errors).length > 0) {
                // Jika ada error validasi spesifik, bisa diambil yang pertama
                errorMessage = Object.values(errors)[0] as string;
            }

            Swal.fire({
                title: 'Gagal!',
                text: errorMessage,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        },
    });
}
</script>

<template>

    <Head title="Merek create" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div>
                <Link href="/merek"
                    class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-green-500 focus:ring-4 focus:outline-none focus:ring-green-500 dark:bg-green-400">
                Kembali</Link>
            </div>

            <div class="overflow-x-auto p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="grid gap-2">
                        <Label for="nama_merek">Nama Merek</Label>
                        <Input id="nama_merek" type="text"
                            class="mt-1 block w-full md:w-1/2 lg:w-1/3 p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required autocomplete="off" placeholder="Masukkan nama merek" v-model="form.nama_merek" />
                        <InputError :message="form.errors.nama_merek" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <Button type="submit" :disabled="form.processing"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600">
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Merek' }}
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
