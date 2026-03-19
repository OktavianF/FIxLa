<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CostEstimationRequest;
use Illuminate\Http\JsonResponse;

class CostEstimationController extends Controller
{
    /** Price per square meter (IDR). */
    private const ASPHALT_PRICE_PER_M2  = 450_000;
    private const CONCRETE_PRICE_PER_M2 = 780_000;

    /** Material breakdown prices per m². */
    private const MATERIALS = [
        'aspal' => [
            'Agregat Base'    => 85_000,
            'Agregat Sub-base'=> 65_000,
            'Aspal AC-WC'     => 150_000,
            'Aspal AC-BC'     => 120_000,
            'Tenaga Kerja'    => 30_000,
        ],
        'beton' => [
            'Pasir'           => 95_000,
            'Kerikil'         => 110_000,
            'Semen'           => 180_000,
            'Besi Tulangan'   => 250_000,
            'Bekisting'       => 85_000,
            'Tenaga Kerja'    => 60_000,
        ],
    ];

    /**
     * Calculate road repair cost estimation for asphalt vs. concrete.
     */
    public function estimate(CostEstimationRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $area       = $validated['road_length'] * $validated['road_width'];
        $multiplier = $this->damageMultiplier($validated['damage_type']);

        $asphaltCost  = (int) round($area * self::ASPHALT_PRICE_PER_M2 * $multiplier);
        $concreteCost = (int) round($area * self::CONCRETE_PRICE_PER_M2 * $multiplier);

        return response()->json([
            'success' => true,
            'data'    => [
                'input' => [
                    'road_length' => $validated['road_length'],
                    'road_width'  => $validated['road_width'],
                    'area'        => $area,
                    'damage_type' => $validated['damage_type'],
                ],
                'aspal' => [
                    'total_cost'     => $asphaltCost,
                    'formatted_cost' => 'Rp ' . number_format($asphaltCost, 0, ',', '.'),
                    'durability'     => '5-7 tahun',
                    'repair_time'    => '3-5 hari',
                    'pros'           => ['Lebih murah', 'Perbaikan cepat', 'Cocok untuk lalu lintas ringan-sedang'],
                    'breakdown'      => $this->generateBreakdown('aspal', $area, $multiplier),
                ],
                'beton' => [
                    'total_cost'     => $concreteCost,
                    'formatted_cost' => 'Rp ' . number_format($concreteCost, 0, ',', '.'),
                    'durability'     => '15-20 tahun',
                    'repair_time'    => '7-14 hari',
                    'pros'           => ['Lebih tahan lama', 'Cocok untuk jalan berat', 'Perawatan minimal'],
                    'breakdown'      => $this->generateBreakdown('beton', $area, $multiplier),
                ],
            ],
        ]);
    }

    // ─── Private Helpers ────────────────────────────────────────

    /**
     * Get the cost multiplier based on damage type.
     */
    private function damageMultiplier(string $type): float
    {
        return match ($type) {
            'retak'     => 0.6,
            'berlubang' => 0.8,
            'amblas'    => 1.0,
            default     => 1.0,
        };
    }

    /**
     * Generate a breakdown of material costs.
     *
     * @return array<int, array<string, mixed>>
     */
    private function generateBreakdown(string $type, float $area, float $multiplier): array
    {
        $breakdown = [];

        foreach (self::MATERIALS[$type] as $name => $pricePerM2) {
            $total = (int) round($area * $pricePerM2 * $multiplier);

            $breakdown[] = [
                'material'   => $name,
                'volume'     => round($area, 1) . ' m²',
                'unit_price' => 'Rp ' . number_format($pricePerM2, 0, ',', '.'),
                'total'      => 'Rp ' . number_format($total, 0, ',', '.'),
                'total_raw'  => $total,
            ];
        }

        return $breakdown;
    }
}