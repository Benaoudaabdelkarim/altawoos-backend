<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRealestateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => 'exists:users,id',
            'name' => 'required|string|max:1000',
            'images' => 'nullable',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
            'featred' => 'required|boolean',
            'type' => 'required|string',
            'size' => 'nullable',
            'bedrooms' => 'nullable',
            'bethrooms' => 'nullable',
            'price_sell' => 'nullable',
            'price_rent' => 'nullable',
            'localisation' => 'nullable|array',
            'price_rent' => 'nullable|array',
        ];
    }
}
