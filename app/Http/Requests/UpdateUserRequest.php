<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user');
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($userId)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($userId)],
            'dui' => ['required', 'string', Rule::unique('users')->ignore($userId), 'regex:/^\d{8}-\d$/'],
            'phone' => ['nullable', 'string', 'regex:/^[0-9]{8,15}$/'],
            'birthdate' => ['required', 'date', 'before:today'],
            'hiring_date' => ['sometimes', 'date', 'before_or_equal:today'],
        ];
    }
}
