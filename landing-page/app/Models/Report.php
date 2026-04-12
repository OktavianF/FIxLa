<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'nama_pelapor',
        'lokasi',
        'foto',
        'deskripsi',
        'status',
        'latitude',
        'longitude'
    ];

    public function repair()
    {
        return $this->hasOne(Repair::class);
    }
}