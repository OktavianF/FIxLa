<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportPhoto extends Model
{
  use HasFactory;

  protected $fillable = [
    'report_id',
    'photo_path',
    'original_name',
    'is_primary',
  ];

  protected $casts = [
    'is_primary' => 'boolean',
  ];

  public function report(): BelongsTo
  {
    return $this->belongsTo(Report::class);
  }

  public function getUrlAttribute(): string
  {
    return asset('storage/' . $this->photo_path);
  }

  protected $appends = ['url'];
}