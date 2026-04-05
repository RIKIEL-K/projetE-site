<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class utilisateurFormRequest extends FormRequest
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
        // En modification (PUT/PATCH), le mot de passe est optionnel
        $passwordRule = ($this->isMethod('PUT') || $this->isMethod('PATCH'))
            ? ['nullable', 'min:6']
            : ['required', 'min:6'];

        return [
            'nom'            => ['required', 'min:2'],
            'prenom'         => ['required', 'min:2'],
            'telephone'      => ['required'],
            'email'          => [
                'required',
                Rule::unique('users')->ignore($this->route()->parameter('utilisateur'))
            ],
            'date_naissance' => ['required'],
            'statut'         => ['nullable', 'boolean'],
            'password'       => $passwordRule,
        ];
    }
}
