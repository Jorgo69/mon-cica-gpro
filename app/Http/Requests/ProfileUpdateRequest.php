<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'telephone' => ['nullable', 'string', 'max:30'],
            'sexe' => ['nullable', 'string', 'in:Homme,Femme,'],
            'pays' => ['nullable', 'string', 'max:2'],
            'ville' => ['nullable', 'string', 'max:100'],
        ];

        // Dynamic phone validation based on country
        if ($this->pays) {
            $country = collect(config('countries'))->firstWhere('code', $this->pays);
            if ($country && $this->telephone) {
                $digits = $country['digits'];
                $prefix = $country['prefix'];
                // Accept: prefix + digits OR just digits
                $rules['telephone'] = ['nullable', 'string', "regex:/^(\\" . $prefix . ")?[0-9\\s]{" . $digits . "," . ($digits + 4) . "}$/"];
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'telephone.regex' => __('validation.phone_format'),
        ];
    }
}
