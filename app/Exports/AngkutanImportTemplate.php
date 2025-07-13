<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AngkutanImportTemplate implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect([]);
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            'Nama Perusahaan',
            'Nama Merek',
            'Nama Jenis Angkutan (AKDP/AKAP/Bus Pariwisata/Angkutan Barang/Angkutan Desa)',
            'Masa Berlaku KP Mulai (YYYY-MM-DD)',
            'Masa Berlaku KP Selesai (YYYY-MM-DD)',
            'Masa Berlaku SK Mulai (YYYY-MM-DD)',
            'Masa Berlaku SK Selesai (YYYY-MM-DD)',
            'Jenis BBM',
            'NIK',
            'Masa Berlaku STNK (YYYY-MM-DD)',
            'Nomor Trayek',
            'Kode Trayek',
            'Nomor Rangka',
            'TNKB',
            'Nomor Uji',
            'Nomor KP',
            'Nomor NIB',
            'Nomor SK',
            'Nomor Mesin',
            'Nomor Seri',
            'Daya Angkut Orang',
            'Daya Angkut KG',
            'Tahun Pembuatan',
            'Alamat',
            'Keterangan',
            'Keterangan Perizinan (Aktif/Tidak Aktif)',
        ];
    }
}
