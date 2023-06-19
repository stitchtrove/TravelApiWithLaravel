<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TourRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'travel_id' => ['required', Rule::exists('travels', 'id')],
            'name' => ['required'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:starting_date'],
            'price' => ['required', 'numeric'],
        ];
    }
}
