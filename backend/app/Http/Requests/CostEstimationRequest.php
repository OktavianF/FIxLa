<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CostEstimationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'road_length'  => 'required|numeric|min:1',
            'road_width'   => 'required|numeric|min:1',
            'damage_type'  => 'required|in:retak,berlubang,amblas',
        ];
    }
}
