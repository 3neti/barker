<?php

namespace App\Http\Requests;

use App\Classes\Barker;
use Propaganistas\LaravelPhone\Rules\Phone as PhoneRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Classes\Phone;
use App\Rules\Type;

class StoreCampaignRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:campaigns,name'],
            'type' => Barker::hasTypes()
                ? ['required', 'string', new Type]
                : null,
            'mobile' => ['nullable', (new PhoneRule)->mobile()->country('PH')],
            'email' => ['nullable', 'email'],
            'url' => ['nullable', 'url'],
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
