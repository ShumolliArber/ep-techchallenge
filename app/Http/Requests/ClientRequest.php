<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:190',
            'email' => 'nullable|email:strict,dns,filter|unique:clients|required_without:phone',
            'phone' => 'nullable|regex:/^[\d\s\+]+$/|required_without:email',
        ];
    }
}
