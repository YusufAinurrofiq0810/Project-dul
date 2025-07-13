<?php

namespace App\Imports;

use App\Models\Angkutan;
use App\Models\JenisAngkutan;
use App\Models\Merek;
use App\Models\Perusahaan;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportAngkutanV2 implements ToCollection, WithHeadingRow
{   
    /**
    * @param Collection $rows
    * Metode ini akan dipanggil untuk setiap baris data dalam file Excel.
    */
    public function collection(Collection $rows)
    {
        $newAngkutans = [];
        foreach ($rows as $index => $row) {
            $data = $row->toArray();
            
            $perusahaan = Perusahaan::where('nama_perusahaan', $row['nama_perusahaan'])->first();
            $merek = Merek::where('nama_merek', $row['nama_merek'])->first();
            $jenisAngkutan = JenisAngkutan::where('Nama_Jenis_Angkutan', $row['nama_jenis_angkutan_akdpakapbus_pariwisataangkutan_barangangkutan_desa'])->first();

            if (empty($perusahaan)) {
                $perusahaan = Perusahaan::create([
                    'nama_perusahaan' => $row['nama_perusahaan'],
                ]);
            }

            if (empty($merek)) {
                $merek = Merek::create([
                    'nama_merek' => $row['nama_merek'],
                ]);
            }

            if (empty($jenisAngkutan)) {
                $baris = $index + 1;
                throw new \Exception("Jenis angkutan tidak ditemukan. Baris {$baris}");
            }

            $keteranganPerizinan = $this->validateKeteranganPerizinan($data['keterangan_perizinan_aktiftidak_aktif'], $index + 1);
            
            $newAngkutans[] = [
                'perusahaan_id' => $perusahaan->id,
                'merek_id' => $merek->id,
                'jenis_angkutan_id' => $jenisAngkutan->id,
    
                'Masa_Berlaku_KP_Start_Date' => $this->parseExcelDate($data['masa_berlaku_kp_mulai_yyyy_mm_dd']),
                'Masa_Berlaku_KP_End_Date' => $this->parseExcelDate($data['masa_berlaku_kp_selesai_yyyy_mm_dd']),
                'Masa_Berlaku_SK_Start_Date' => $this->parseExcelDate($data['masa_berlaku_sk_mulai_yyyy_mm_dd']),
                'Masa_Berlaku_SK_End_Date' => $this->parseExcelDate($data['masa_berlaku_sk_selesai_yyyy_mm_dd']),
                'Masa_Berlaku_STNK' => $this->parseExcelDate($data['masa_berlaku_stnk_yyyy_mm_dd']),
    
                'keterangan_perizinan' => $keteranganPerizinan,
    
                'NIK' => $data['nik'],
                'Jenis_BBM' => $data['jenis_bbm'],
                'No_Trayek' => $data['nomor_trayek'],
                'Kode_Trayek' => $data['kode_trayek'],
                'No_Rangka' => $data['nomor_rangka'],
                'TNKB' => $data['tnkb'],
                'No_uji' => $data['nomor_uji'],
                'No_KP' => $data['nomor_kp'],
                'No_NIB' => $data['nomor_nib'],
                'No_SK' => $data['nomor_sk'],
                'No_Mesin' => $data['nomor_mesin'],
                'No_Seri' => $data['nomor_seri'],
                'Daya_Angkut_Orang' => $data['daya_angkut_orang'],
                'Daya_Angkut_KG' => $data['daya_angkut_kg'],
                'Tahun_Pembuatan' => $data['tahun_pembuatan'],
                'Alamat' => $data['alamat'],
                'keterangan' => $data['keterangan'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        Angkutan::insert($newAngkutans);
    }

    private function parseExcelDate($value)
    {
        if (is_numeric($value)) {
            return \Carbon\Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays($value - 2)->format('Y-m-d');
        }

        return $value; // if already in Y-m-d format
    }

    private function validateKeteranganPerizinan($value, $indexRow)
    {
        if ($value !== 'Aktif' && $value !== 'Tidak Aktif') {
            throw new \Exception("Keterangan perizinan harus 'Aktif' atau 'Tidak Aktif'. Baris : {$indexRow}");
        }

        return $value === 'Aktif' ? true : false;
    }
}
