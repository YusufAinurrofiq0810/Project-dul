<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Merek extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_merek',
    ];
    public function angkutan()
    {
        return $this->hasMany(Angkutan::class);
    }
}
