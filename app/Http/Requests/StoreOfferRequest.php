<?php

namespace App\Http\Requests;

use App\Enum\Currency;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Class StoreOfferRequest
 *
 * @package App\Http\Requests
 */
class StoreOfferRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'currency_from' => ['required', Rule::enum(Currency::class)],
            'currency_to'   => ['required', Rule::enum(Currency::class)],
            'amount_from'   => 'required|numeric',
            'amount_to'     => 'required|numeric',
        ];
    }
}
