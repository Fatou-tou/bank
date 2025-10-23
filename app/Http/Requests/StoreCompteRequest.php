<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompteRequest extends FormRequest
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
            'client_id' => 'required|uuid|exists:clients,id',
            'type' => 'required|in:Epargne,Courant',
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'L\'ID du client est obligatoire.',
            'client_id.uuid' => 'L\'ID du client doit être un UUID valide.',
            'client_id.exists' => 'Le client spécifié n\'existe pas.',
            'type.required' => 'Le type de compte est obligatoire.',
            'type.in' => 'Le type de compte doit être soit "Epargne" soit "Courant".',
        ];
    }
}
