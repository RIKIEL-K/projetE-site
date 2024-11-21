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
        return [
            'nom'=>['required','min:2'],
            'prenom'=>['required','min:2'],
            'telephone'=>['required'],
            'email' => [
            'required',
            Rule::unique('users')->ignore($this->route()->parameter('utilisateur')) // Validation unique sauf pour l'utilisateur actuel
            ],
            'date_naissance'=>['required'],
            'statut'=>['nullable','boolean'],
            'password' => ['required', 'min:6'],
        ];
    }
}
