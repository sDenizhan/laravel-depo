<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmailSettingRequest extends FormRequest
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
            'smtp_host' => 'required',
            'smtp_port' => 'required|numeric',
            'smtp_username' => 'required',
            'smtp_password' => 'required',
            'smtp_encryption' => 'required',
            'smtp_from_name' => 'required',
            'smtp_from_email' => 'required|email',
        ];
    }
}
