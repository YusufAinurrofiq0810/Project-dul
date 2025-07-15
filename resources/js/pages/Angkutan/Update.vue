<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Button from '@/components/ui/button/Button.vue';
import Swal from 'sweetalert2'; // Import SweetAlert2 for notifications

// Define types for related models
interface Perusahaan {
    id: number;
    nama_perusahaan: string;
}

interface Merek {
    id: number;
    nama_merek: string;
}

interface JenisAngkutan {
    id: number;
    Nama_Jenis_Angkutan: string;
}

// Define type for Angkutan model (assuming it matches your database structure)
interface Angkutan {
    id: number;
    perusahaan_id: number;
    merek_id: number;
    jenis_angkutan_id: number; // Assuming this is a foreign key to JenisAngkutan
    Masa_berlaku_KP_Start_date: string; // Assuming YYYY-MM-DD format
    Masa_berlaku_KP_End_date: string; // Assuming YYYY-MM-DD format
    Masa_berlaku_SK_Start_date: string; // Assuming YYYY-MM-DD format
    Masa_berlaku_SK_End_date: string; // Assuming YYYY-MM-DD format
    keterangan_perizinan: string | null; // Assuming this can be null
    NIK: string;
    Jenis_BBM: string;
    Masa_Berlaku_STNK: string; // Assuming YYYY-MM-DD format
    No_Rangka: string;
    No_Trayek: string;
    TNKB: string;
    No_uji: string;
    No_KP: string;
    No_NIB: string;
    No_SK: string;
    No_Mesin: string | null;
    // Tanggal_SK: string; // Assuming YYYY-MM-DD format
    Kode_Trayek: string;
    No_Seri: string;
    Daya_Angkut_Orang: number;
    Daya_Angkut_KG: number;
    Tahun_Pembuatan: string; // Stored as 'YYYY-01-01' in DB, but we'll extract the year
    Alamat: string;
    keterangan: string;
    trayek: string;
    tipe: string;
}

// Define props for the component, including lists for dropdowns and an optional Angkutan object for editing
interface Props {
    perusahaans_list: Perusahaan[]; // List of companies for the dropdown
    mereks_list: Merek[]; // List of brands for the dropdown
    jenis_angkutans_list: JenisAngkutan[]; // List of types of transportation for the dropdown
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
    jenis_angkutan_id: props.angkutan?.jenis_angkutan_id.toString() || "", // Convert to string for select
    Masa_Berlaku_KP_Start_Date: props.angkutan?.Masa_berlaku_KP_Start_date || "",
    Masa_Berlaku_KP_End_Date: props.angkutan?.Masa_berlaku_KP_End_date || "",
    Masa_Berlaku_SK_Start_Date: props.angkutan?.Masa_berlaku_SK_Start_date || "",
    Masa_Berlaku_SK_End_Date: props.angkutan?.Masa_berlaku_SK_End_date || "",
    keterangan_perizinan: props.angkutan?.keterangan_perizinan || 1,
    // NIK: props.angkutan?.NIK || "",
    Jenis_BBM: props.angkutan?.Jenis_BBM || "",
    Masa_Berlaku_STNK: props.angkutan?.Masa_Berlaku_STNK || "",
    No_Rangka: props.angkutan?.No_Rangka || "",
    // No_Trayek: props.angkutan?.No_Trayek || "",
    TNKB: props.angkutan?.TNKB || "",
    No_uji: props.angkutan?.No_uji || "",
    No_KP: props.angkutan?.No_KP || "",
    No_NIB: props.angkutan?.No_NIB || "",
    No_SK: props.angkutan?.No_SK || "",
    No_Mesin: props.angkutan?.No_Mesin || "",
    // Tanggal_SK: props.angkutan?.Tanggal_SK || "",
    // Kode_Trayek: props.angkutan?.Kode_Trayek || "",
    No_Seri: props.angkutan?.No_Seri || "",
    Daya_Angkut_Orang: props.angkutan?.Daya_Angkut_Orang || "",
    Daya_Angkut_KG: props.angkutan?.Daya_Angkut_KG || "",
    // Extract only the year from Tahun_Pembuatan if in edit mode
    Tahun_Pembuatan: props.angkutan?.Tahun_Pembuatan ? new Date(props.angkutan.Tahun_Pembuatan).getFullYear().toString() : "",
    Alamat: props.angkutan?.Alamat || "",
    keterangan: props.angkutan?.keterangan || "",
    trayek: props.angkutan?.trayek || "",
    tipe: props.angkutan?.tipe || "",
});


function submit() {
    if (isEditMode) {
        // If in edit mode, use PUT request to update
        form.put(`/angkutan/${props.angkutan?.id}`, {
            onSuccess: () => {
                console.log('Angkutan berhasil diperbarui!');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Angkutan berhasil diperbarui.',
                    timer: 2000,
                    showConfirmButton: false,
                });
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
                        <!-- Jenis Kendaraan -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="jenis_angkutan_id">Jenis Angkutan</Label>
                            <select id="jenis_angkutan_id"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                v-model="form.jenis_angkutan_id" required>
                                <option value="" disabled>Pilih Jenis Angkutan</option>
                                <option v-for="jenis in props.jenis_angkutans_list" :key="jenis.id" :value="jenis.id">
                                    {{ jenis.Nama_Jenis_Angkutan }}
                                </option>
                            </select>
                            <div v-if="form.errors.jenis_angkutan_id" class="text-red-500 text-sm">
                                {{ form.errors.jenis_angkutan_id }}</div>
                        </div>
                        
                        <!-- Nama Perusahaan -->
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

                        <!-- Merek -->
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

                        <!-- Tanggal mulai KP -->
                        <div class="grid gap-2">
                            <Label for="masa_berlaku_kp_start_date">Tanggal mulai KP</Label>
                            <Input id="masa_berlaku_kp_start_date" type="date"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                v-model="form.Masa_Berlaku_KP_Start_Date" />
                            <div v-if="form.errors.Masa_Berlaku_KP_Start_Date" class="text-red-500 text-sm">
                                {{ form.errors.Masa_Berlaku_KP_Start_Date }}</div>
                        </div>
                        
                        <!-- Tanggal Berakhir KP -->
                        <div class="grid gap-2">
                            <Label for="masa_berlaku_kp_end_date">Tanggal Berakhir KP</Label>
                            <Input id="masa_berlaku_kp_end_date" type="date"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                v-model="form.Masa_Berlaku_KP_End_Date" />
                            <div v-if="form.errors.Masa_Berlaku_KP_End_Date" class="text-red-500 text-sm">
                                {{ form.errors.Masa_Berlaku_KP_End_Date }}</div>
                        </div>

                        <!-- Tanggal mulai SK -->
                        <div class="grid gap-2">
                            <Label for="masa_berlaku_sk_start_date">Tanggal mulai SK</Label>
                            <Input id="masa_berlaku_sk_start_date" type="date"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                v-model="form.Masa_Berlaku_SK_Start_Date" />
                            <div v-if="form.errors.Masa_Berlaku_SK_Start_Date" class="text-red-500 text-sm">
                                {{ form.errors.Masa_Berlaku_SK_Start_Date }}</div>
                        </div>

                        <!-- Tanggal Berakhir SK -->
                        <div class="grid gap-2">
                            <Label for="masa_berlaku_sk_end_date">Tanggal berakhir SK</Label>
                            <Input id="masa_berlaku_sk_end_date" type="date"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                v-model="form.Masa_Berlaku_SK_End_Date" />
                            <div v-if="form.errors.Masa_Berlaku_SK_End_Date" class="text-red-500 text-sm">
                                {{ form.errors.Masa_Berlaku_SK_End_Date }}</div>
                        </div>

                        <!-- Jenis BBM -->
                        <div class="grid gap-2">
                            <Label for="jenis_bbm">Jenis BBM</Label>
                            <Input id="jenis_bbm" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan Jenis BBM" v-model="form.Jenis_BBM" />
                            <div v-if="form.errors.Jenis_BBM" class="text-red-500 text-sm">{{ form.errors.Jenis_BBM }}
                            </div>
                        </div>

                        <!-- Masa Berlaku STNK -->
                        <div class="grid gap-2">
                            <Label for="masa_berlaku_stnk">Masa Berlaku STNK</Label>
                            <Input id="masa_berlaku_stnk" type="date"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                v-model="form.Masa_Berlaku_STNK" />
                            <div v-if="form.errors.Masa_Berlaku_STNK" class="text-red-500 text-sm">
                                {{ form.errors.Masa_Berlaku_STNK }}</div>
                        </div>

                        <!-- No. Trayek -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="trayek">Trayek</Label>
                            <Input id="trayek" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan Trayek" v-model="form.trayek" />
                            <div v-if="form.errors.trayek" class="text-red-500 text-sm">{{ form.errors.trayek }}
                            </div>
                        </div>

                        <!-- Tipe -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="tipe">Tipe</Label>
                            <Input id="tipe" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan tipe" v-model="form.tipe" />
                            <div v-if="form.errors.tipe" class="text-red-500 text-sm">{{ form.errors.tipe }}</div>
                        </div>

                        <!-- No. Rangka -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="no_rangka">No. Rangka</Label>
                            <Input id="no_rangka" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan No. Rangka" v-model="form.No_Rangka" />
                            <div v-if="form.errors.No_Rangka" class="text-red-500 text-sm">{{ form.errors.No_Rangka }}
                            </div>
                        </div>

                        <!-- TNKB -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="tnkb">TNKB</Label>
                            <Input id="tnkb" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan TNKB" v-model="form.TNKB" />
                            <div v-if="form.errors.TNKB" class="text-red-500 text-sm">{{ form.errors.TNKB }}</div>
                        </div>

                        <!-- No. Uji -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="no_uji">No. Uji</Label>
                            <Input id="no_uji" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan No. Uji" v-model="form.No_uji" />
                            <div v-if="form.errors.No_uji" class="text-red-500 text-sm">{{ form.errors.No_uji }}</div>
                        </div>

                        <!-- No. KP -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="no_kp">No. KP</Label>
                            <Input id="no_kp" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan No. KP" v-model="form.No_KP" />
                            <div v-if="form.errors.No_KP" class="text-red-500 text-sm">{{ form.errors.No_KP }}</div>
                        </div>

                        <!-- No. NIB -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="no_nib">No. NIB</Label>
                            <Input id="no_nib" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan No. NIB" v-model="form.No_NIB" />
                            <div v-if="form.errors.No_NIB" class="text-red-500 text-sm">{{ form.errors.No_NIB }}</div>
                        </div>

                        <!-- No. SK -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="no_sk">No. SK</Label>
                            <Input id="no_sk" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan No. SK" v-model="form.No_SK" />
                            <div v-if="form.errors.No_SK" class="text-red-500 text-sm">{{ form.errors.No_SK }}</div>
                        </div>

                        <!-- No. Mesin -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="no_mesin">No. Mesin</Label>
                            <Input id="no_mesin" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan No. Mesin" v-model="form.No_Mesin" />
                            <div v-if="form.errors.No_Mesin" class="text-red-500 text-sm">{{ form.errors.No_Mesin }}
                            </div>
                        </div>

                        <!-- No. Seri -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="nomer_seri">No. Seri</Label>
                            <Input id="nomer_seri" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan Nomer Seri" v-model="form.No_Seri" />
                            <div v-if="form.errors.No_Seri" class="text-red-500 text-sm">{{ form.errors.No_Seri }}
                            </div>
                        </div>

                        <!-- Daya Angkut Orang -->
                        <div class="grid gap-2">
                            <Label for="daya_angkut">Daya Angkut Orang</Label>
                            <Input id="daya_angkut" type="number"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan Daya Angkut" v-model="form.Daya_Angkut_Orang" />
                            <div v-if="form.errors.Daya_Angkut_Orang" class="text-red-500 text-sm">
                                {{ form.errors.Daya_Angkut_Orang }}</div>
                        </div>

                        <!-- Daya Angkut KG -->
                        <div class="grid gap-2">
                            <Label for="daya_angkut_kg">Daya Angkut KG</Label>
                            <Input id="daya_angkut_kg" type="number"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan Daya Angkut KG" v-model="form.Daya_Angkut_KG" />
                            <div v-if="form.errors.Daya_Angkut_KG" class="text-red-500 text-sm">{{
                                form.errors.Daya_Angkut_KG }}</div>
                        </div>

                        <!-- Tahun Pembuatan -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="tahun_pembuatan">Tahun Pembuatan</Label>
                            <Input id="tahun_pembuatan" type="number"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan Tahun Pembuatan" v-model="form.Tahun_Pembuatan" />
                            <div v-if="form.errors.Tahun_Pembuatan" class="text-red-500 text-sm">
                                {{ form.errors.Tahun_Pembuatan }}</div>
                        </div>
                    </div>

                     <!-- Keterangan -->
                     <div class="grid gap-2">
                        <Label for="keterangan">Keterangan</Label>
                        <textarea id="keterangan" rows="3"
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Masukkan Keterangan" v-model="form.keterangan"></textarea>
                    </div>
                    
                    <!-- Alamat -->
                    <div class="grid gap-2">
                        <Label for="alamat">Alamat</Label>
                        <textarea id="alamat" rows="3"
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Masukkan Alamat" v-model="form.Alamat"></textarea>
                        <div v-if="form.errors.Alamat" class="text-red-500 text-sm">{{ form.errors.Alamat }}</div>
                    </div>
                    
                    <!-- Keterangan Perizinan -->
                    <div class="grid gap-2">
                        <Label for="keterangan_perizinan">Keterangan Perizinan</Label>
                        <select id="keterangan_perizinan"
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            v-model="form.keterangan_perizinan">
                            <option value="">Pilih Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
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
