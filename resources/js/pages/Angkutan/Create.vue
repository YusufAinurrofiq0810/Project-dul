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

// Define props for the component, including lists for dropdowns
interface Props {
    perusahaans_list: Perusahaan[];
    mereks_list: Merek[];
    jenis_angkutans_list: JenisAngkutan[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Angkutan create',
        href: '/angkutan/create',
    },
];

// Fixed form initialization to match model's fillable fields exactly
const form = useForm({
    perusahaan_id: "",
    merek_id: "",
    jenis_angkutan_id: "",
    // Match model's exact field names (note the capitalization)
    Masa_Berlaku_KP_Start_Date: "",
    Masa_Berlaku_KP_End_Date: "",
    Masa_Berlaku_SK_Start_Date: "",
    Masa_Berlaku_SK_End_Date: "",
    keterangan_perizinan: "", // lowercase in model
    NIK: "",
    Jenis_BBM: "",
    Masa_Berlaku_STNK: "",
    No_Rangka: "",
    // No_Trayek: "",
    TNKB: "",
    No_uji: "",
    No_KP: "",
    No_NIB: "",
    No_SK: "",
    No_Mesin: "",
    // Kode_Trayek: "",
    No_Seri: "",
    Daya_Angkut_Orang: 0,
    Daya_Angkut_KG: 0,
    Tahun_Pembuatan: 0,
    Alamat: "",
    keterangan: "",
    trayek: "",
    tipe: "",
});

function submit() {
    // Convert string values to appropriate types before submission
    const submitData = {
        ...form.data(),
        perusahaan_id: parseInt(form.perusahaan_id) || null,
        merek_id: parseInt(form.merek_id) || null,
        jenis_angkutan_id: parseInt(form.jenis_angkutan_id) || null,
        Daya_Angkut_Orang: parseInt(form.Daya_Angkut_Orang.toString()) || 0,
        Daya_Angkut_KG: parseInt(form.Daya_Angkut_KG.toString()) || 0,
        Tahun_Pembuatan: parseInt(form.Tahun_Pembuatan.toString()) || 0,
    };

    form.transform(() => submitData).post(route('angkutan.store'), {
        onSuccess: () => {
            form.reset();
            console.log('Angkutan berhasil dibuat!');
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Angkutan berhasil dibuat.',
                timer: 2000,
                showConfirmButton: false,
            });

        },
        onError: (errors) => {
            console.error('Terjadi kesalahan saat membuat angkutan:', errors);
            Swal.fire({
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat menyimpan angkutan.',
                icon: 'error',
                confirmButtonText: 'OK',
            });
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
                <!-- Display validation errors if any -->
                <div v-if="form.errors && Object.keys(form.errors).length > 0"
                    class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <h4 class="font-bold">Validation Errors:</h4>
                    <ul class="list-disc list-inside">
                        <li v-for="(error, field) in form.errors" :key="field">
                            {{ field }}: {{ error }}
                        </li>
                    </ul>
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Jenis Kendaraan -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="jenis_angkutan_id">Jenis Angkutan</Label>
                            <select id="jenis_angkutan_id"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                v-model="form.jenis_angkutan_id" required>
                                <option value="" disabled>Pilih Jenis Angkutan</option>
                                <option v-for="jenis in props.jenis_angkutans_list" :key="jenis.id"
                                    :value="jenis.id.toString()">
                                    {{ jenis.Nama_Jenis_Angkutan }}
                                </option>
                            </select>
                        </div>

                        <!-- Nama Perusahaan -->
                        <div class="grid gap-2">
                            <Label for="perusahaan_id">Nama Perusahaan</Label>
                            <select id="perusahaan_id"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                v-model="form.perusahaan_id" required>
                                <option value="" disabled>Pilih Perusahaan</option>
                                <option v-for="perusahaan in props.perusahaans_list" :key="perusahaan.id"
                                    :value="perusahaan.id.toString()">
                                    {{ perusahaan.nama_perusahaan }}
                                </option>
                            </select>
                        </div>

                        <!-- Merek -->
                        <div class="grid gap-2">
                            <Label for="merek_id">Merek</Label>
                            <select id="merek_id"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                v-model="form.merek_id" required>
                                <option value="" disabled>Pilih Merek</option>
                                <option v-for="merek in props.mereks_list" :key="merek.id" :value="merek.id.toString()">
                                    {{ merek.nama_merek }}
                                </option>
                            </select>
                        </div>

                        <!-- Tanggal mulai KP -->
                        <div class="grid gap-2">
                            <Label for="masa_berlaku_kp_start_date">Tanggal mulai KP</Label>
                            <Input id="masa_berlaku_kp_start_date" type="date"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                v-model="form.Masa_Berlaku_KP_Start_Date" />
                        </div>

                        <!-- Tanggal Berakhir KP -->
                        <div class="grid gap-2">
                            <Label for="masa_berlaku_kp_end_date">Tanggal Berakhir KP</Label>
                            <Input id="masa_berlaku_kp_end_date" type="date"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                v-model="form.Masa_Berlaku_KP_End_Date" />
                        </div>

                        <!-- Tanggal mulai SK -->
                        <div class="grid gap-2">
                            <Label for="masa_berlaku_sk_start_date">Tanggal mulai SK</Label>
                            <Input id="masa_berlaku_sk_start_date" type="date"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                v-model="form.Masa_Berlaku_SK_Start_Date" />
                        </div>

                        <!-- Tanggal Berakhir SK -->
                        <div class="grid gap-2">
                            <Label for="masa_berlaku_sk_end_date">Tanggal Berakhir SK</Label>
                            <Input id="masa_berlaku_sk_end_date" type="date"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                v-model="form.Masa_Berlaku_SK_End_Date" />
                        </div>

                        <!-- Jenis BBM -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="jenis_bbm">Jenis BBM</Label>
                            <Input id="jenis_bbm" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan Jenis BBM" v-model="form.Jenis_BBM" />
                        </div>

                        <!-- NIK -->
                        <div class="grid gap-2">
                            <Label for="nik">NIK</Label>
                            <Input id="nik" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan NIK" v-model="form.NIK" />
                        </div>

                        <!-- Masa Berlaku STNK -->
                        <div class="grid gap-2">
                            <Label for="masa_berlaku_stnk">Masa Berlaku STNK</Label>
                            <Input id="masa_berlaku_stnk" type="date"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                v-model="form.Masa_Berlaku_STNK" />
                        </div>

                        <!-- Trayek -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="trayek">Trayek</Label>
                            <Input id="trayek" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan Trayek" v-model="form.trayek" />
                        </div>

                        <!-- Tipe -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="tipe">Tipe</Label>
                            <Input id="tipe" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan tipe" v-model="form.tipe" />
                        </div>
                        
                        <!-- No. Rangka -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="no_rangka">No. Rangka</Label>
                            <Input id="no_rangka" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan No. Rangka" v-model="form.No_Rangka" />
                        </div>

                        <!-- TNKB -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="tnkb">TNKB</Label>
                            <Input id="tnkb" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan TNKB" v-model="form.TNKB" />
                        </div>

                        <!-- No. Uji -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="no_uji">No. Uji</Label>
                            <Input id="no_uji" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan No. Uji" v-model="form.No_uji" />
                        </div>

                        <!-- No. KP -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="no_kp">No. KP</Label>
                            <Input id="no_kp" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan No. KP" v-model="form.No_KP" />
                        </div>

                        <!-- No. NIB -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="no_nib">No. NIB</Label>
                            <Input id="no_nib" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan No. NIB" v-model="form.No_NIB" />
                        </div>

                        <!-- No. SK -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="no_sk">No. SK</Label>
                            <Input id="no_sk" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan No. SK" v-model="form.No_SK" />
                        </div>

                        <!-- No. Mesin -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="no_mesin">No. Mesin</Label>
                            <Input id="no_mesin" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan No. Mesin" v-model="form.No_Mesin" />
                        </div>

                        <!-- No. Seri -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="no_seri">No. Seri</Label>
                            <Input id="no_seri" type="text"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan No. Seri" v-model="form.No_Seri" />
                        </div>

                        <!-- Daya Angkut Orang -->
                        <div class="grid gap-2">
                            <Label for="daya_angkut_orang">Daya Angkut Orang</Label>
                            <Input id="daya_angkut_orang" type="number"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan Daya Angkut Orang" v-model.number="form.Daya_Angkut_Orang" />
                        </div>

                        <!-- Daya Angkut KG -->
                        <div class="grid gap-2">
                            <Label for="daya_angkut_kg">Daya Angkut KG</Label>
                            <Input id="daya_angkut_kg" type="number"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan Daya Angkut KG" v-model.number="form.Daya_Angkut_KG" />
                        </div>

                        <!-- Tahun Pembuatan -->
                        <div class="grid col-span-2 gap-2">
                            <Label for="tahun_pembuatan">Tahun Pembuatan</Label>
                            <Input id="tahun_pembuatan" type="number" min="1900" :max="new Date().getFullYear()"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Masukkan Tahun Pembuatan" v-model.number="form.Tahun_Pembuatan" />
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="grid gap-2">
                        <Label for="alamat">Alamat</Label>
                        <textarea id="alamat" rows="3"
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Masukkan Alamat" v-model="form.Alamat"></textarea>
                    </div>

                    <!-- Keterangan -->
                    <div class="grid gap-2">
                        <Label for="keterangan">Keterangan</Label>
                        <textarea id="keterangan" rows="3"
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Masukkan Keterangan" v-model="form.keterangan"></textarea>
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

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <Button type="submit" :disabled="form.processing"
                            class="px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed">
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Angkutan' }}
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
