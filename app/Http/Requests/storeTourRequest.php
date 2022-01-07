<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeTourRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
//todo        dk why cant make description Required
        $rules = [
            'name' => 'required',
            'tour_code' => 'required|unique:App\Tour,tour_code',
            'destination' => 'required',
            'category' => 'required',
            'itinerary' => 'required|mimes:pdf',
            'thumbnail' => 'required|mimes:jpeg,bmp,png',
            'des.0' => 'required',
            'place.0' => 'required',
            'des.1' => 'required',
            'place.1' => 'required',
            'des.2' => 'required',
            'place.2' => 'required',
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'des.0.required' => 'Description 1 is required',
            'place.0.required' => 'Place 1 is required',
            'des.1.required' => 'Description 2 is required',
            'place.1.required' => 'Place 2 is required',
            'des.2.required' => 'Description 3 is required',
            'place.2.required' => 'Place 3 is required',
        ];
    }
}
