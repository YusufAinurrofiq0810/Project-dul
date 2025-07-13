<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Testing\Fluent\Concerns\Has;

class Angkutan extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    // protected $table = 'angkutans';
    protected $fillable = [
        'perusahaan_id',
        'jenis_angkutan_id',
        'Masa_Berlaku_KP_Start_Date',
        'Masa_Berlaku_KP_End_Date',
        'Masa_Berlaku_SK_Start_Date',
        'Masa_Berlaku_SK_End_Date',
        'keterangan_perizinan',
        'NIK',
        'Jenis_BBM',
        'Masa_Berlaku_STNK',
        'No_Trayek',
        'No_Rangka',
        'TNKB',
        'No_uji',
        'No_KP',
        'No_NIB',
        'No_Mesin',
        'No_SK',
        // 'Tanggal_SK',
        'Kode_Trayek',
        'No_Seri',
        'Daya_Angkut_Orang',
        'Daya_Angkut_KG',
        'Tahun_Pembuatan',
        'merek_id',
        'Alamat',
        'keterangan',
    ];

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function merek()
    {
        return $this->belongsTo(Merek::class);
    }
    public function jenisAngkutan()
    {
        return $this->belongsTo(JenisAngkutan::class, 'jenis_angkutan_id', 'id');
    }
}
