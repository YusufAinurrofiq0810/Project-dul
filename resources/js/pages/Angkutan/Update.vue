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

// Define type for Angkutan model (assuming it matches your database structure)
interface Angkutan {
    id: number;
    perusahaan_id: number;
    merek_id: number;
    TNKB: string;
    No_uji: string;
    No_KP: string;
    No_NIB: string;
    No_SK: string;
    NO_Mesin: string | null;
    Tanggal_SK: string; // Assuming YYYY-MM-DD format
    Kode_Trayek: string;
    No_Seri: string;
    Daya_Angkut: number;
    KG: number;
    Tahun_Pembuatan: string; // Stored as 'YYYY-01-01' in DB, but we'll extract the year
    Alamat: string;
    created_at: string;
    updated_at: string;
}

// Define props for the component, including lists for dropdowns and an optional Angkutan object for editing
interface Props {
    perusahaans_list: Perusahaan[]; // List of companies for the dropdown
    mereks_list: Merek[]; // List of brands for the dropdown
    angkutan?: Angkutan; // Optional Angkutan object for editing
}

const props = defineProps<Props>();

// Determine if we are in edit mode
const isEditMode = !!props.angkutan;

// Dynamically set breadcrumbs based on mode
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Angkutan',
        href: '/angkutan',
    },
    {
        title: isEditMode ? 'Edit Angkutan' : 'Create Angkutan',
        href: isEditMode ? `/angkutan/${props.angkutan?.id}/edit` : '/angkutan/create',
    }
];

// Inisialisasi form dengan field-field baru dan relasi
const form = useForm({
    // Populate form fields with existing angkutan data if in edit mode, otherwise empty
    perusahaan_id: props.angkutan?.perusahaan_id.toString() || "", // Convert to string for select
    merek_id: props.angkutan?.merek_id.toString() || "", // Convert to string for select
    TNKB: props.angkutan?.TNKB || "",
    No_uji: props.angkutan?.No_uji || "",
    No_KP: props.angkutan?.No_KP || "",
    No_NIB: props.angkutan?.No_NIB || "",
    No_SK: props.angkutan?.No_SK || "",
    No_Mesin: props.angkutan?.NO_Mesin || "",
    Tanggal_SK: props.angkutan?.Tanggal_SK || "",
    Kode_Trayek: props.angkutan?.Kode_Trayek || "",
    No_Seri: props.angkutan?.No_Seri || "",
    Daya_Angkut: props.angkutan?.Daya_Angkut || "",
    KG: props.angkutan?.KG || "",
    // Extract only the year from Tahun_Pembuatan if in edit mode
    Tahun_Pembuatan: props.angkutan?.Tahun_Pembuatan ? new Date(props.angkutan.Tahun_Pembuatan).getFullYear().toString() : "",
    Alamat: props.angkutan?.Alamat || "",
});


function submit() {
    if (isEditMode) {
        // If in edit mode, use PUT request to update
        form.put(`/angkutan/${props.angkutan?.id}`, {
            onSuccess: () => {
                console.log('Angkutan berhasil diperbarui!');
            },
            onError: (errors) => {
                console.error('Terjadi kesalahan saat memperbarui angkutan:', errors);
            },
        });
    } else {
        // If in create mode, use POST request to store
        form.post('/angkutan', {
            onSuccess: () => {
                form.reset();
                console.log('Angkutan berhasil dibuat!');
            },
            onError: (errors) => {
                console.error('Terjadi kesalahan saat membuat angkutan:', errors);
            },
        });
    }
}
</script>

<template>

    <Head :title="isEditMode ? 'Edit Angkutan' : 'Create Angkutan'" />
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
                            <div v-if="form.errors.perusahaan_id" class="text-red-500 text-sm">
                                {{ form.errors.perusahaan_id }}</div>
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
                            <div v-if="form.errors.merek_id" class="text-red-500 text-sm">{{ form.errors.merek_id }}
                            </div>
                        </div>

                        <div class="grid gap-2">
                            <Label for="tnkb">TNKB</Label>
                            <Input id="tnkb" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan TNKB" v-model="form.TNKB" />
                            <div v-if="form.errors.TNKB" class="text-red-500 text-sm">{{ form.errors.TNKB }}</div>
                        </div>

                        <div class="grid gap-2">
                            <Label for="no_uji">No. Uji</Label>
                            <Input id="no_uji" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan No. Uji" v-model="form.No_uji" />
                            <div v-if="form.errors.No_uji" class="text-red-500 text-sm">{{ form.errors.No_uji }}</div>
                        </div>

                        <div class="grid gap-2">
                            <Label for="no_kp">No. KP</Label>
                            <Input id="no_kp" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan No. KP" v-model="form.No_KP" />
                            <div v-if="form.errors.No_KP" class="text-red-500 text-sm">{{ form.errors.No_KP }}</div>
                        </div>

                        <div class="grid gap-2">
                            <Label for="no_nib">No. NIB</Label>
                            <Input id="no_nib" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan No. NIB" v-model="form.No_NIB" />
                            <div v-if="form.errors.No_NIB" class="text-red-500 text-sm">{{ form.errors.No_NIB }}</div>
                        </div>

                        <div class="grid gap-2">
                            <Label for="no_sk">No. SK</Label>
                            <Input id="no_sk" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan No. SK" v-model="form.No_SK" />
                            <div v-if="form.errors.No_SK" class="text-red-500 text-sm">{{ form.errors.No_SK }}</div>
                        </div>

                        <div class="grid gap-2">
                            <Label for="no_mesin">No_Mesin</Label>
                            <!-- Changed type to text to match backend validation -->
                            <Input id="no_mesin" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan No. Mesin" v-model="form.No_Mesin" />
                            <div v-if="form.errors.No_Mesin" class="text-red-500 text-sm">{{ form.errors.No_Mesin }}
                            </div>
                        </div>

                        <div class="grid gap-2">
                            <Label for="tanggal_sk">Tanggal SK</Label>
                            <Input id="tanggal_sk" type="date"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                v-model="form.Tanggal_SK" />
                            <div v-if="form.errors.Tanggal_SK" class="text-red-500 text-sm">
                                {{ form.errors.Tanggal_SK }}</div>
                        </div>

                        <div class="grid gap-2">
                            <Label for="kode_trayek">Kode Trayek</Label>
                            <Input id="kode_trayek" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan Kode Trayek" v-model="form.Kode_Trayek" />
                            <div v-if="form.errors.Kode_Trayek" class="text-red-500 text-sm">
                                {{ form.errors.Kode_Trayek }}</div>
                        </div>

                        <div class="grid gap-2">
                            <Label for="nomer_seri">Nomer Seri</Label>
                            <Input id="nomer_seri" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan Nomer Seri" v-model="form.No_Seri" />
                            <div v-if="form.errors.No_Seri" class="text-red-500 text-sm">{{ form.errors.No_Seri }}
                            </div>
                        </div>

                        <div class="grid gap-2">
                            <Label for="daya_angkut">Daya Angkut</Label>
                            <Input id="daya_angkut" type="number"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan Daya Angkut" v-model="form.Daya_Angkut" />
                            <div v-if="form.errors.Daya_Angkut" class="text-red-500 text-sm">
                                {{ form.errors.Daya_Angkut }}</div>
                        </div>

                        <div class="grid gap-2">
                            <Label for="kg">KG</Label>
                            <Input id="kg" type="number"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan KG" v-model="form.KG" />
                            <div v-if="form.errors.KG" class="text-red-500 text-sm">{{ form.errors.KG }}</div>
                        </div>

                        <div class="grid gap-2">
                            <Label for="tahun_pembuatan">Tahun Pembuatan</Label>
                            <Input id="tahun_pembuatan" type="number"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan Tahun Pembuatan" v-model="form.Tahun_Pembuatan" />
                            <div v-if="form.errors.Tahun_Pembuatan" class="text-red-500 text-sm">
                                {{ form.errors.Tahun_Pembuatan }}</div>
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <Label for="alamat">Alamat</Label>
                        <Input id="alamat" type="text"
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Masukkan Alamat" v-model="form.Alamat" />
                        <div v-if="form.errors.Alamat" class="text-red-500 text-sm">{{ form.errors.Alamat }}</div>
                    </div>

                    <div class="mt-4">
                        <Button type="submit" :disabled="form.processing"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600">
                            {{ form.processing ? 'Menyimpan...' : (isEditMode ? 'Update Angkutan' : 'Simpan Angkutan')
                            }}
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
