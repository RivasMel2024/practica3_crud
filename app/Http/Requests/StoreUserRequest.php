<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'unique:users,email'],
            'dui' => ['required', 'string', 'unique:users,dui', 'regex:/^\d{8}-\d$/'],
            'phone' => ['nullable', 'string', 'regex:/^[0-9]{8,15}$/'],
            'birthdate' => ['required', 'date', 'before:today'],
            'hiring_date' => ['nullable', 'date', 'before_or_equal:today'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Si no se envía hiring_date, asignar la fecha actual
        if (!$this->has('hiring_date')) {
            $this->merge([
                'hiring_date' => now()->toDateString(),
            ]);
        }
    }
}
