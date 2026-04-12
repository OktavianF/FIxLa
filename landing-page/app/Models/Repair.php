<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    protected $fillable = [
        'report_id',
        'nama_petugas',
        'tanggal_mulai',
        'tanggal_selesai',
        'progress',
        'keterangan',
        'status'
    ];

    // CASTING: ubah string tanggal menjadi objek Carbon
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}