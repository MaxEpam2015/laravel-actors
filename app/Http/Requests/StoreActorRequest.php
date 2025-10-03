<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActorRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'email' => ['required','email','unique:actors,email'],
            'description' => ['required','string','unique:actors,description'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email is required.',
            'description.required' => 'Actor description is required.',
        ];
    }
}
