<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\PhoneValidationService;

class StoreClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'prenom' => 'required|string|max:20',
            'nom' => 'required|string|max:20',
            'telephone' => 'required|string|max:15|unique:clients,telephone',
            'email' => 'nullable|email|unique:clients,email',
            'adresse' => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'prenom.required' => 'Le prénom est obligatoire.',
            'prenom.max' => 'Nombre de caractères (20) atteint.',
            'nom.required' => 'Le nom est obligatoire.',
            'nom.max' => 'Nombre de caractères (20) atteint.',
            'telephone.required' => 'Le numéro de téléphone est obligatoire.',
            'telephone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
            'email.email' => 'Le format de l’adresse e-mail est invalide.',
            'email.unique' => 'Cette adresse e-mail est déjà enregistrée.',
        ];
    }

    
}
