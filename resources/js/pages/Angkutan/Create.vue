<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Button from '@/components/ui/button/Button.vue';

// Define types for related models
interface Perusahaan {
    id: number;
    nama_perusahaan: string;
}

interface Merek {
    id: number;
    nama_merek: string;
}

// Define props for the component, including lists for dropdowns
interface Props {
    perusahaans_list: Perusahaan[]; // List of companies for the dropdown
    mereks_list: Merek[]; // List of brands for the dropdown
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Angkutan create', // Changed breadcrumb title
        href: '/angkutan/create', // Changed breadcrumb href
    },
];

// Inisialisasi form dengan field-field baru dan relasi
const form = useForm({
    perusahaan_id: "", // Foreign key for Perusahaan
    merek_id: "", // Foreign key for Merek
    TNKB: "",
    No_uji: "",
    No_KP: "",
    No_NIB: "",
    No_SK: "",
    NO_Mesin: "",
    Tanggal_SK: "",
    Kode_Trayek: "",
    No_Seri: "",
    Daya_Angkut: "",
    KG: "",
    Tahun_Pembuatan: "",
    Alamat: "",
});


function submit() {
    // Changed endpoint to /angkutan
    form.post('/angkutan', {
        onSuccess: () => {
            form.reset();
            console.log('Angkutan berhasil dibuat!'); // Changed success message
        },
        onError: (errors) => {
            console.error('Terjadi kesalahan saat membuat angkutan:', errors); // Changed error message
        },
    });
}
</script>

<template>

    <Head title="Angkutan create" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div>
                <Link href="/angkutan"
                    class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-green-500 focus:ring-4 focus:outline-none focus:ring-green-500 dark:bg-green-400">
                Kembali</Link>
            </div>

            <div class="overflow-x-auto p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="grid gap-2">
                            <Label for="perusahaan_id">Nama Perusahaan</Label>
                            <select id="perusahaan_id"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                v-model="form.perusahaan_id" required>
                                <option value="" disabled>Pilih Perusahaan</option>
                                <option v-for="perusahaan in props.perusahaans_list" :key="perusahaan.id"
                                    :value="perusahaan.id">
                                    {{ perusahaan.nama_perusahaan }}
                                </option>
                            </select>
                        </div>

                        <div class="grid gap-2">
                            <Label for="merek_id">Merek</Label>
                            <select id="merek_id"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                v-model="form.merek_id" required>
                                <option value="" disabled>Pilih Merek</option>
                                <option v-for="merek in props.mereks_list" :key="merek.id" :value="merek.id">
                                    {{ merek.nama_merek }}
                                </option>
                            </select>
                        </div>

                        <div class="grid gap-2">
                            <Label for="tnk">TNK</Label>
                            <Input id="tnk" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan TNKB" v-model="form.TNKB" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="no_uji">No. Uji</Label>
                            <Input id="no_uji" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan No. Uji" v-model="form.No_uji" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="no_kp">No. KP</Label>
                            <Input id="no_kp" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan No. KP" v-model="form.No_KP" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="no_nib">No. NIB</Label>
                            <Input id="no_nib" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan No. NIB" v-model="form.No_NIB" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="no_sk">No. SK</Label>
                            <Input id="no_sk" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan No. SK" v-model="form.No_SK" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="no_mesin">No. Mesin</Label>
                            <Input id="no_mesin" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan No. Mesin" v-model="form.NO_Mesin" />
                        </div>j
                        <div class="grid gap-2">
                            <Label for="tanggal_sk">Tanggal SK</Label>
                            <Input id="tanggal_sk" type="date"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                v-model="form.Tanggal_SK" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="kode_trayek">Kode Trayek</Label>
                            <Input id="kode_trayek" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan Kode Trayek" v-model="form.Kode_Trayek" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="nomer_seri">Nomer Seri</Label>
                            <Input id="nomer_seri" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan Nomer Seri" v-model="form.No_Seri" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="daya_angkut">Daya Angkut</Label>
                            <Input id="daya_angkut" type="number"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan Daya Angkut" v-model="form.Daya_Angkut" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="kg">KG</Label>
                            <Input id="kg" type="number"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan KG" v-model="form.KG" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="tahun_pembuatan">Tahun Pembuatan</Label>
                            <Input id="tahun_pembuatan" type="number"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan Tahun Pembuatan" v-model="form.Tahun_Pembuatan" />
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <Label for="alamat">Alamat</Label>
                        <Input id="alamat" type="text"
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Masukkan Alamat" v-model="form.Alamat" />
                    </div>

                    <div class="mt-4">
                        <Button type="submit" :disabled="form.processing"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600">
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Angkutan' }}
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
