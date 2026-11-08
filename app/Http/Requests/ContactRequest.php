<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'project_type' => ['nullable', 'in:web,shop,admin,api,care,consult'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            'kvkk' => ['accepted'],
            'website' => ['prohibited'], // bot tuzağı: dolu gelirse reddedilir
        ];
    }

    public function messages(): array
    {
        $m = __('site.contact.errors');

        return [
            'name.*' => $m['name'],
            'email.*' => $m['email'],
            'message.*' => $m['message'],
            'kvkk.*' => $m['kvkk'],
            'website.*' => __('site.contact.error'),
            'project_type.*' => __('site.contact.error'),
        ];
    }
}
