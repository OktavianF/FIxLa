<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusHistory extends Model
{
  use HasFactory;

  protected $fillable = [
    'report_id',
    'changed_by',
    'from_status',
    'to_status',
    'notes',
  ];

  public function report(): BelongsTo
  {
    return $this->belongsTo(Report::class);
  }

  public function changedBy(): BelongsTo
  {
    return $this->belongsTo(User::class , 'changed_by');
  }
}