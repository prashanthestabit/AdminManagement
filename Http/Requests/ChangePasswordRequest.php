<?php

namespace Modules\AdminManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'old_password' => 'required',
            'password' => ['required', 'string', 'min:8', 'confirmed',
                function ($attribute, $value, $fail) {
                    $pass = $attribute;
                if ($value === $this->old_password) {
                    $fail(__('adminmanagement::auth.new_and_old_password_same'));
                }
            }],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
