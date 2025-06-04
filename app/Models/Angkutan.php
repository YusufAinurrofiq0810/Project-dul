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
        'TNKB',
        'No_uji',
        'No_KP',
        'No_NIB',
        'No_Mesin',
        'No_SK',
        'Tanggal_SK',
        'Kode_Trayek',
        'No_Seri',
        'Daya_Angkut',
        'KG',
        'Tahun_Pembuatan',
        'merek_id',
        'Alamat',
    ];

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function merek()
    {
        return $this->belongsTo(Merek::class);
    }
}
