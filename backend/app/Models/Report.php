<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'latitude',
    'longitude',
    'address',
    'district',
    'damage_level',
    'description',
    'road_length',
    'road_width',
    'confidence_score',
    'is_ai_classified',
    'estimated_cost_asphalt',
    'estimated_cost_concrete',
    'priority_score',
    'report_count',
    'traffic_level',
    'facility_proximity',
    'status',
    'verified_at',
    'scheduled_at',
    'repair_started_at',
    'completed_at',
  ];

  protected $casts = [
    'latitude' => 'decimal:7',
    'longitude' => 'decimal:7',
    'priority_score' => 'decimal:2',
    'traffic_level' => 'decimal:2',
    'facility_proximity' => 'decimal:2',
    'road_length' => 'decimal:2',
    'road_width' => 'decimal:2',
    'confidence_score' => 'decimal:4',
    'is_ai_classified' => 'boolean',
    'estimated_cost_asphalt' => 'decimal:2',
    'estimated_cost_concrete' => 'decimal:2',
    'verified_at' => 'datetime',
    'scheduled_at' => 'datetime',
    'repair_started_at' => 'datetime',
    'completed_at' => 'datetime',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function photos(): HasMany
  {
    return $this->hasMany(ReportPhoto::class);
  }

  public function statusHistories(): HasMany
  {
    return $this->hasMany(StatusHistory::class);
  }

  public function notifications(): HasMany
  {
    return $this->hasMany(AppNotification::class);
  }

  /**
   * Calculate Road Repair Cost Estimation
   */
  public function calculateCostEstimation(): void
  {
    if (!$this->road_length || $this->road_length <= 0) return;
    
    $width = $this->road_width ?? 4.0; 
    $area = $this->road_length * $width;
    
    // Multiplier based on damage level
    $multiplier = match ($this->damage_level) {
        'ringan' => 0.6,
        'sedang' => 0.8,
        'berat' => 1.0,
        default => 1.0,
    };
    
    // Pricing constants from CostEstimationController
    $asphaltPricePerM2 = 450000;
    $concretePricePerM2 = 780000;
    
    $this->estimated_cost_asphalt = round($area * $asphaltPricePerM2 * $multiplier, 2);
    $this->estimated_cost_concrete = round($area * $concretePricePerM2 * $multiplier, 2);
  }

  /**
   * Calculate Smart Priority Score
   * Score = (report_count * 0.4) + (damage_level * 0.3) + (traffic_level * 0.2) + (facility_proximity * 0.1)
   */
  public function calculatePriorityScore(): float
  {
    $damageValue = match ($this->damage_level) {
        'ringan' => 33,
        'sedang' => 66,
        'berat' => 100,
        default => 0,
      };

    $reportFactor = min($this->report_count * 10, 100);
    $trafficFactor = $this->traffic_level * 100;
    $facilityFactor = $this->facility_proximity * 100;

    $score = ($reportFactor * 0.4)
      + ($damageValue * 0.3)
      + ($trafficFactor * 0.2)
      + ($facilityFactor * 0.1);

    return round(min($score, 100), 2);
  }

  /**
   * Scope for nearby reports (within radius in km)
   */
  public function scopeNearby($query, float $lat, float $lng, float $radiusKm = 0.05)
  {
    $latDiff = $radiusKm / 111.0;
    $lngDiff = $radiusKm / (111.0 * cos(deg2rad($lat)));

    return $query->whereBetween('latitude', [$lat - $latDiff, $lat + $latDiff])
      ->whereBetween('longitude', [$lng - $lngDiff, $lng + $lngDiff]);
  }
}