<?php

namespace App\Imports;

use App\Models\Angkutan;
use App\Models\Merek;
use App\Models\Perusahaan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportAngkutan implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Temukan ID perusahaan berdasarkan nama perusahaan
        // Jika tidak ditemukan, Anda bisa memilih untuk membuat yang baru,
        // atau mengabaikan baris ini, atau mengembalikan null untuk error.
        $perusahaan = Perusahaan::firstOrCreate(
            ['nama_perusahaan' => $row['nama_perusahaan']], // Kolom di Excel harus 'nama_perusahaan'
            ['alamat' => 'Alamat Default'] // Tambahkan kolom lain yang diperlukan jika membuat baru
        );

        // Temukan ID merek berdasarkan nama merek
        $merek = Merek::firstOrCreate(
            ['nama_merek' => $row['nama_merek']] // Kolom di Excel harus 'nama_merek'
            // Tambahkan kolom lain yang diperlukan jika membuat baru
        );

        // Pastikan 'tahun_pembuatan' adalah angka dan format tanggal SK
        $tahunPembuatan = (int) $row['tahun_pembuatan'];
        // Periksa apakah 'tanggal_sk' ada dan tidak kosong sebelum memparsing
        $tanggalSK = !empty($row['tanggal_sk']) ? Carbon::parse($row['tanggal_sk'])->format('Y-m-d') : null;

        return new Angkutan([
            'perusahaan_id' => $perusahaan->id,
            'merek_id' => $merek->id,
            'TNKB' => $row['tnkb'],
            'No_uji' => $row['no_uji'],
            'No_KP' => $row['no_kp'],
            'No_NIB' => $row['no_nib'],
            'No_SK' => $row['no_sk'],
            'NO_Mesin' => $row['no_mesin'] ?? null, // Handle nullable
            'Tanggal_SK' => $tanggalSK,
            'Kode_Trayek' => $row['kode_trayek'],
            'No_Seri' => $row['no_seri'],
            'Daya_Angkut' => (int) $row['daya_angkut'],
            'KG' => (int) $row['kg'],
            'Tahun_Pembuatan' => $tahunPembuatan . '-01-01', // Sesuaikan dengan format DATE di DB
            'Alamat' => $row['alamat'],
        ]);
    }

    /**
     * Define validation rules for each row.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'nama_perusahaan' => 'required|string|max:255',
            'nama_merek' => 'required|string|max:255',
            'tnkb' => 'required|string|max:255',
            'no_uji' => 'required|string|max:255',
            'no_kp' => 'required|string|max:255',
            'no_nib' => 'required|string|max:255',
            'no_sk' => 'required|string|max:255',
            'no_mesin' => 'nullable|string|max:255',
            'tanggal_sk' => 'required|date_format:Y-m-d', // Sesuaikan format tanggal di Excel
            'kode_trayek' => 'required|string|max:255',
            'no_seri' => 'required|string|max:255',
            'daya_angkut' => 'required|numeric',
            'kg' => 'required|numeric',
            'tahun_pembuatan' => 'required|numeric|min:1900|max:' . date('Y'),
            'alamat' => 'required|string|max:255',
        ];
    }
}
