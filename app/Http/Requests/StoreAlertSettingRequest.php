<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlertSettingRequest extends FormRequest
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
            'email_enabled' => 'required|boolean',
            'email_address' => 'required_if:email_enabled,1|email',
            'email_subject' => 'required_if:email_enabled,1',
            'email_message' => 'required_if:email_enabled,1',
            'sms_enabled' => 'required|boolean',
            'sms_number' => 'required_if:sms_enabled,1',
            'sms_message' => 'required_if:sms_enabled,1|max:150'
        ];
    }
}
