<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRealestateRequest extends FormRequest
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
    protected function prepareForValidation()
    {
        error_log('this is the user id: ' . request()->user()->id);
        $this->merge([
            'user_id' => $this->user()->id 
        ]);
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
            'images' => 'nullable|array',
            'images.*' => 'required|mimetypes:image/*',
            'description' => 'nullable|string',
            'status' => 'required',
            'featred' => 'required',
            'type' => 'required|string',
            'size' => 'nullable',
            'bedrooms' => 'nullable',
            'bethrooms' => 'nullable',
            'price_sell' => 'nullable',
            'price_rent' => 'nullable',
            'localisation' => 'nullable|array',
            'tags' => 'nullable|array',
        ];
    }
}
