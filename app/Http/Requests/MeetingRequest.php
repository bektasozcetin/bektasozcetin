<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date_format:Y-m-d'],
            'time' => ['required', 'date_format:H:i'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'topic' => ['nullable', 'in:web,shop,admin,api,care,consult'],
            'note' => ['nullable', 'string', 'max:2000'],
            'kvkk' => ['accepted'],
            'website' => ['prohibited'],
        ];
    }

    public function messages(): array
    {
        $m = __('site.contact.errors');

        return [
            'date.*' => __('site.booking.errors.slot'),
            'time.*' => __('site.booking.errors.slot'),
            'name.*' => $m['name'],
            'email.*' => $m['email'],
            'kvkk.*' => $m['kvkk'],
            'note.*' => __('site.contact.error'),
            'topic.*' => __('site.contact.error'),
            'website.*' => __('site.contact.error'),
        ];
    }
}
