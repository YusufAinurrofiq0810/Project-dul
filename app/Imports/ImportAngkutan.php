<?php

namespace App\Imports;

use App\Models\Angkutan;
use App\Models\JenisAngkutan;
use App\Models\Merek;
use App\Models\Perusahaan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date; // Import Date from PhpSpreadsheet

class ImportAngkutan implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Debugging: Dump the row data to see what's being read
        // IMPORTANT: UNCOMMENT THIS AND RUN THE IMPORT AGAIN TO SEE THE EXACT KEYS.
        // dd($row);

        // Temukan ID perusahaan berdasarkan nama perusahaan
        // Excel header: 'Nama Perusahaan' -> becomes 'nama_perusahaan'
        $perusahaan = Perusahaan::firstOrCreate(
            ['nama_perusahaan' => $row['nama_perusahaan'] ?? 'Nama Perusahaan Default'],
            ['alamat' => $row['alamat'] ?? 'Alamat Default Perusahaan'] // Use 'alamat' from Excel for Perusahaan address if available
        );

        // Temukan ID merek berdasarkan nama merek
        // Excel header: 'Nama Merek' -> becomes 'nama_merek'
        $merek = Merek::firstOrCreate(
            ['nama_merek' => $row['nama_merek'] ?? 'Nama Merek Default']
        );

        // Temukan atau buat jenis angkutan berdasarkan nama jenis angkutan
        // Excel header: 'Nama Jenis Angkutan' -> becomes 'nama_jenis_angkutan'
        $jenisAngkutan = null;
        if (isset($row['nama_jenis_angkutan']) && !empty($row['nama_jenis_angkutan'])) {
            $jenisAngkutan = JenisAngkutan::firstOrCreate(
                ['Nama_Jenis_Angkutan' => $row['nama_jenis_angkutan']]
            );
        }

        // Helper function to parse dates, handling Excel serial dates
        $parseDate = function ($dateValue) {
            if (empty($dateValue)) {
                return null;
            }
            // Check if the value is numeric (could be Excel serial date)
            if (is_numeric($dateValue) && $dateValue > 0 && $dateValue < 2958466) {
                try {
                    return Carbon::instance(Date::excelToDateTimeObject($dateValue))->format('Y-m-d');
                } catch (\Exception $e) {
                    // Fallback to string parsing if excelToDateTimeObject fails for numeric
                    // You might log this error: error_log("Excel serial date parse failed for value: " . $dateValue . " - " . $e->getMessage());
                }
            }
            // Try parsing as a string date (e.g., "YYYY-MM-DD")
            try {
                return Carbon::parse($dateValue)->format('Y-m-d');
            } catch (\Exception $e) {
                // Return null if parsing as string also fails
                // You might log this error: error_log("String date parse failed for value: " . $dateValue . " - " . $e->getMessage());
                return null;
            }
        };

        // Excel header: 'Tahun Pembuatan' -> becomes 'tahun_pembuatan'
        $tahunPembuatan = (int) ($row['tahun_pembuatan'] ?? date('Y'));
        $tahunPembuatanDate = Carbon::createFromDate($tahunPembuatan, 1, 1)->format('Y-m-d');

        return new Angkutan([
            'perusahaan_id' => $perusahaan->id,
            'merek_id' => $merek->id,
            'jenis_angkutan_id' => $jenisAngkutan ? $jenisAngkutan->id : null,

            // Corrected keys based on Excel headers (snake_case)
            // Excel header: 'Masa Berlaku KP Mulai' -> 'masa_berlaku_kp_mulai'
            'Masa_Berlaku_KP_Start_Date' => $parseDate($row['masa_berlaku_kp_mulai'] ?? null),
            // Excel header: 'Masa Berlaku KP Akhir' -> 'masa_berlaku_kp_akhir'
            'Masa_Berlaku_KP_End_Date' => $parseDate($row['masa_berlaku_kp_selesai'] ?? null),
            // Excel header: 'Masa Berlaku SK Mulai' -> 'masa_berlaku_sk_mulai'
            'Masa_Berlaku_SK_Start_Date' => $parseDate($row['masa_berlaku_sk_mulai'] ?? null),
            // Excel header: 'Masa Berlaku SK selesai' -> 'masa_berlaku_sk_selesai'
            'Masa_Berlaku_SK_End_Date' => $parseDate($row['masa_berlaku_sk_selesai'] ?? null),

            // Missing in Excel: 'keterangan_perizinan', 'nik'
            'keterangan_perizinan' => $row['keterangan_perizinan'] ?? null, // Will be null if column not in Excel
            'NIK' => $row['nik'] ?? null,                                   // Will be null if column not in Excel

            // Excel header: 'Jenis BBM' -> 'jenis_bbm'
            'Jenis_BBM' => $row['jenis_bbm'] ?? null,
            // Excel header: 'Masa Berlaku STNK' (not in image) assuming 'masa_berlaku_stnk' from previous code
            'Masa_Berlaku_STNK' => $parseDate($row['masa_berlaku_stnk'] ?? null),
            // Excel header: 'No Trayek' -> 'no_trayek'
            'No_Trayek' => $row['no_trayek'] ?? null,
            // Excel header: 'TNKB' -> 'tnkb'
            'TNKB' => $row['tnkb'] ?? null,
            // Excel header: 'No Rangka' -> 'no_rangka'
            'No_Rangka' => $row['no_rangka'] ?? null,
            // Excel header: 'No Uji' -> 'no_uji'
            'No_uji' => $row['no_uji'] ?? null,
            // Excel header: 'No KP' -> 'no_kp'
            'No_KP' => $row['no_kp'] ?? null,
            // Excel header: 'No NIB' -> 'no_nib'
            'No_NIB' => $row['no_nib'] ?? null,
            // Excel header: 'No SK' -> 'no_sk'
            'No_SK' => $row['no_sk'] ?? null,
            // Excel header: 'No Mesin' -> 'no_mesin'
            'No_Mesin' => $row['no_mesin'] ?? null,
            // Excel header: 'Kode Trayek' -> 'kode_trayek'
            'Kode_Trayek' => $row['kode_trayek'] ?? null,
            // Excel header: 'No Seri' -> 'no_seri'
            'No_Seri' => $row['no_seri'] ?? null,
            // Excel header: 'Daya Angkut Orang' -> 'daya_angkut_orang'
            'Daya_Angkut_Orang' => (int) ($row['daya_angkut_orang'] ?? 0),
            // Excel header: 'Daya Angkut Kg' -> 'daya_angkut_kg'
            'Daya_Angkut_KG' => (int) ($row['daya_angkut_kg'] ?? 0),
            // Excel header: 'Tahun Pembuatan' -> 'tahun_pembuatan'
            'Tahun_Pembuatan' => $tahunPembuatanDate,
            // Excel header: 'Alamat' -> 'alamat'
            'Alamat' => $row['alamat'] ?? null,
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

            // Excel header: 'Nama Jenis Angkutan' -> 'nama_jenis_angkutan'
            'nama_jenis_angkutan' => 'nullable|string|max:255',

            // Corrected keys based on Excel headers (snake_case)
            'masa_berlaku_kp_mulai' => 'nullable|date',
            'masa_berlaku_kp_selesai' => 'nullable|date',
            'masa_berlaku_sk_mulai' => 'nullable|date',
            'masa_berlaku_sk_selesai' => 'nullable|date',

            // These columns are not in your Excel file, they will be null unless added
            'keterangan_perizinan' => 'nullable|boolean',
            'nik' => 'nullable|string|max:255',
            'masa_berlaku_stnk' => 'nullable|date', // This column isn't in your current Excel image, but was in previous code

            'jenis_bbm' => 'nullable|string|max:255',
            'no_trayek' => 'nullable|string|max:255',
            'no_rangka' => 'nullable|string|max:255',
            'tnkb' => 'nullable|string|max:255',
            'no_uji' => 'nullable|string|max:255',
            'no_kp' => 'nullable|string|max:255',
            'no_nib' => 'nullable|string|max:255',
            'no_sk' => 'nullable|string|max:255',
            'no_mesin' => 'nullable|string|max:255',
            'kode_trayek' => 'nullable|string|max:255',
            'no_seri' => 'nullable|string|max:255',
            'daya_angkut_orang' => 'nullable|integer|min:0',
            'daya_angkut_kg' => 'nullable|numeric|min:0',
            'tahun_pembuatan' => 'nullable|integer|min:1900|max:' . (date('Y') + 5),
            'alamat' => 'nullable|string|max:255',
            'alamat_perusahaan' => 'nullable|string|max:255', // Still here if you want to use it for Perusahaan's address
        ];
    }
}
