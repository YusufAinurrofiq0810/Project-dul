<?php

namespace App\Exports;

use App\Models\Angkutan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AngkutanExport implements FromCollection, WithHeadings, WithMapping
{
    private $rowNumber = 0;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Ambil semua data Angkutan dengan relasi perusahaan dan merek
        return Angkutan::with(['perusahaan', 'merek'])->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Definisikan judul kolom untuk file Excel
        return [
            'Nomer Urut',
            'Nama Perusahaan',
            'Nama Merek',
            'TNKB',
            'No. Uji',
            'No. KP',
            'No. NIB',
            'No. SK',
            'No. Mesin',
            'Tanggal SK',
            'Kode Trayek',
            'No. Seri',
            'Daya Angkut',
            'KG',
            'Tahun Pembuatan',
            'Alamat',
            'Dibuat Pada',
            // 'Diperbarui Pada',
        ];
    }

    /**
     * @var Angkutan $angkutan
     */
    public function map($angkutan): array
    {
        $this->rowNumber++;
        // Petakan data dari objek Angkutan ke dalam baris array untuk Excel
        return [
            $this->rowNumber,
            $angkutan->perusahaan->nama_perusahaan ?? 'N/A', // Ambil nama perusahaan dari relasi
            $angkutan->merek->nama_merek ?? 'N/A',           // Ambil nama merek dari relasi
            $angkutan->TNKB,
            $angkutan->No_uji,
            $angkutan->No_KP,
            $angkutan->No_NIB,
            $angkutan->No_SK,
            $angkutan->NO_Mesin,
            $angkutan->Tanggal_SK,
            $angkutan->Kode_Trayek,
            $angkutan->No_Seri,
            $angkutan->Daya_Angkut,
            $angkutan->KG,
            // Format Tahun_Pembuatan agar hanya menampilkan tahun
            ($angkutan->Tahun_Pembuatan ? \Carbon\Carbon::parse($angkutan->Tahun_Pembuatan)->year : 'N/A'),
            $angkutan->Alamat,
            $angkutan->created_at,
            // $angkutan->updated_at,
        ];
    }
}
