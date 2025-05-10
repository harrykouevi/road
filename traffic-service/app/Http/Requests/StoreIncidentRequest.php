<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreIncidentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => 'required|string',
            'id_type' => 'required|integer|exists:report_types,id',
            'emplacement.latitude' => 'required|numeric',
            'emplacement.longitude' => 'required|numeric',
            'emplacement.adresse' => 'nullable|string',
            'image' => 'nullable|string', // base64 image
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = array_values($validator->errors()->toArray());

        throw new HttpResponseException(
            app('App\Http\Controllers\Controller')->sendError($errors, 422)
        );
    }
}
