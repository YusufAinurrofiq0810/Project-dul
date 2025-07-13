<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Testing\Fluent\Concerns\Has;

class Perusahaan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_perusahaan',
    ];

    public function angkutan()
    {
        return $this->hasMany(Angkutan::class);
    }
}
