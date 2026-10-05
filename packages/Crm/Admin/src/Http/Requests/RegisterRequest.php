<?php

namespace Crm\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('email')) {
            $this->merge([
                'email' => strtolower(trim((string) $this->input('email'))),
            ]);
        }

        if ($this->has('name')) {
            $this->merge([
                'name' => trim((string) $this->input('name')),
            ]);
        }

        if ($this->has('company_name')) {
            $this->merge([
                'company_name' => trim((string) $this->input('company_name')),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'                  => ['required', 'string', 'max:191'],
            'company_name'          => ['required', 'string', 'max:191'],
            'email'                 => ['required', 'string', 'email', 'max:191', 'unique:users,email'],
            'phone'                 => ['nullable', 'string', 'max:50'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8'],
            'agree'                 => ['accepted'],
            'website'               => ['nullable', 'max:0'], // Honeypot field - must remain empty
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'agree.accepted' => 'You must agree to the Terms of Service and Privacy Policy to continue.',
            'website.max'    => 'Spam detection triggered.',
        ];
    }
}
