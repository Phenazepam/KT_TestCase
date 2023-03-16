<?php

namespace App\Http\Requests;

use App\Rules\SerialNumberRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEquipmentRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        // return[];
        return [
            'type_id' => 'required|numeric',
            'serial_number' => ['required', new SerialNumberRule],
            'comment' => 'nullable'
        ];
    }
}
