<?php

namespace App\Http\Requests;

use Propaganistas\LaravelPhone\Rules\Phone as PhoneRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Classes\Phone;

class StoreCheckinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->id == $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'mobile' => ['nullable', (new PhoneRule)->mobile()->country('PH')],
            'email' => ['nullable', 'email'],
        ];
    }

    /**
     * @return array
     */
    public function validationData(): array
    {
        $data = parent::validationData();

        return array_merge($data, [
            'mobile' => Phone::number($data['mobile']),
        ]);
    }
}
