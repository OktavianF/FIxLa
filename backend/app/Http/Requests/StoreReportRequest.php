<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
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
            'latitude'    => 'required|numeric|between:-90,90',
            'longitude'   => 'required|numeric|between:-180,180',
            'address'     => 'nullable|string|max:500',
            'district'    => 'nullable|string|max:100',
            'damage_level'=> 'required|in:ringan,sedang,berat',
            'description' => 'nullable|string|max:500',
            'road_length' => 'nullable|numeric|min:0',
            'road_width'  => 'nullable|numeric|min:0',
            'photos'      => 'required|array|min:1|max:5',
            'photos.*'    => 'image|mimes:jpeg,png,jpg|max:5120',
        ];
    }
}
