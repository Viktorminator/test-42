<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tariff_id' => 'required|exists:tariffs,id',
            'user_count' => 'required|integer|min:1',
            'payment_frequency' => 'required|string|in:monthly,yearly',
        ];
    }


    public function messages()
    {
        return [
            'tariff_id.required' => 'Please select a valid tariff.',
            'tariff_id.exists' => 'The selected tariff does not exist.',
            'user_count.required' => 'Please specify the number of users.',
            'user_count.integer' => 'The user count must be an integer.',
            'payment_frequency.in' => 'The payment frequency must be either monthly or yearly.',
        ];
    }
}

