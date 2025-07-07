<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Button from '@/components/ui/button/Button.vue';
import Swal from 'sweetalert2';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Perusahaan create',
        href: '/perusahaan/create',
    },
];


const form = useForm({
    nama_perusahaan: "",
});


function submit() {
    form.post('/perusahaan', {
        onSuccess: () => {
            form.reset();
            console.log('Perusahaan berhasil dibuat!');
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Perusahaan berhasil dibuat.',
                timer: 2000,
                showConfirmButton: false,
            });
        },
        onError: (errors) => {
            console.error('Terjadi kesalahan saat membuat perusahaan:', errors);
            Swal.fire({
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat menyimpan perusahaan.',
                icon: 'error',
                confirmButtonText: 'OK',
            });
        },
    });
}
</script>

<template>

    <Head title="Perusahaan create" />

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
                        <Label for="nama_perusahaan">Nama Perusahaan</Label>
                        <Input id="nama_perusahaan" type="text"
                            class="mt-1 block w-full md:w-1/2 lg:w-1/3 p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required autocomplete="organization" placeholder="Masukkan nama perusahaan"
                            v-model="form.nama_perusahaan" />
                    </div>
                    <div class="mt-4">
                        <Button type="submit" :disabled="form.processing"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600">
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Perusahaan' }}
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
