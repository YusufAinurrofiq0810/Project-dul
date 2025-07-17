<?php

namespace App\Exports;

use App\Models\Angkutan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon; // Pastikan Carbon diimpor jika belum

class AngkutanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $rowNumber = 0;
    protected $jenisAngkutanId;
    protected $perusahaanId;

    public function __construct($jenisAngkutanId = null, $perusahaanId = null)
    {
        $this->jenisAngkutanId = $jenisAngkutanId;
        $this->perusahaanId = $perusahaanId;
    }

    /**
     * Mengambil koleksi data Angkutan dengan filter.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Mulai query dengan relasi yang diperlukan
        $query = Angkutan::with(['perusahaan', 'merek', 'jenisAngkutan']);

        // Terapkan filter berdasarkan jenis angkutan jika disediakan
        if ($this->jenisAngkutanId) {
            $query->where('jenis_angkutan_id', $this->jenisAngkutanId);
        }

        // Terapkan filter berdasarkan perusahaan jika disediakan
        if ($this->perusahaanId) {
            $query->where('perusahaan_id', $this->perusahaanId);
        }

        // Ambil data yang sudah difilter
        return $query->get();
    }

    /**
     * Mendefinisikan judul kolom untuk file Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nomer Urut',
            'Nama Perusahaan',
            'Nama Merek',
            'Nama Jenis Angkutan',
            'Masa Berlaku KP Mulai',
            'Masa Berlaku KP Selesai',
            'Masa Berlaku SK Mulai',
            'Masa Berlaku SK Selesai',
            'Keterangan Perizinan',
            'Jenis BBM',
            'Masa Berlaku STNK',
            'Trayek',
            'No. Rangka',
            'TNKB',
            'No. Uji',
            'No. KP',
            'No. NIB',
            'No. SK',
            'No. Mesin',
            'Keterangan',
            'Tipe',
            'No. Seri',
            'Daya Angkut Orang',
            'Daya Angkut KG',
            'Tahun Pembuatan',
            'Alamat',
            'Dibuat Pada',
        ];
    }

    /**
     * Memetakan data dari objek Angkutan ke dalam baris array untuk Excel.
     *
     * @param Angkutan $angkutan
     * @return array
     */
    public function map($angkutan): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $angkutan->perusahaan->nama_perusahaan ?? 'N/A',
            $angkutan->merek->nama_merek ?? 'N/A',
            $angkutan->jenisAngkutan->Nama_Jenis_Angkutan ?? 'N/A',
            $angkutan->Masa_berlaku_KP_Start_date,
            $angkutan->Masa_berlaku_KP_End_date,
            $angkutan->Masa_berlaku_SK_Start_date,
            $angkutan->Masa_berlaku_SK_End_date,
            $angkutan->keterangan_perizinan ? 'Aktif' : 'Tidak Aktif',
            $angkutan->Jenis_BBM,
            $angkutan->Masa_Berlaku_STNK,
            $angkutan->trayek,
            $angkutan->No_Rangka,
            $angkutan->TNKB,
            $angkutan->No_uji,
            $angkutan->No_KP,
            $angkutan->No_NIB,
            $angkutan->No_SK,
            $angkutan->No_Mesin,
            $angkutan->keterangan,
            $angkutan->tipe,
            $angkutan->No_Seri,
            $angkutan->Daya_Angkut_Orang,
            $angkutan->Daya_Angkut_KG,
            // Format Tahun_Pembuatan agar hanya menampilkan tahun
            ($angkutan->Tahun_Pembuatan ? Carbon::parse($angkutan->Tahun_Pembuatan)->year : 'N/A'),
            $angkutan->Alamat,
            $angkutan->created_at,
        ];
    }
}
