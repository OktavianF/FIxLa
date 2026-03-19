<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRequest extends FormRequest
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
            'status' => 'required|in:submitted,verified,scheduled,under_repair,completed',
            'notes'  => 'nullable|string|max:500',
        ];
    }
}
