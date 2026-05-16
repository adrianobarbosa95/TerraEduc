<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],

            'bio' => ['nullable', 'string'],

            'github' => ['nullable', 'url'],
            'linkedin' => ['nullable', 'url'],
            'instagram' => ['nullable', 'url'],
            'website' => ['nullable', 'url'],
            'lattes' => ['nullable', 'url'],

            'photo' => ['nullable', 'image', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'github.url' => 'Informe uma URL válida (ex: https://github.com/seuusuario)',
            'linkedin.url' => 'Informe uma URL válida (ex: https://linkedin.com/in/seuusuario)',
            'instagram.url' => 'Informe uma URL válida (ex: https://instagram.com/seuusuario)',
            'website.url' => 'Informe uma URL válida (ex: https://seusite.com)',
            'lattes.url' => 'Informe uma URL válida do Lattes',
        ];
    }
}