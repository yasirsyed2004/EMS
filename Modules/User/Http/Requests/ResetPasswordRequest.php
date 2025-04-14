<?php

namespace Modules\User\Http\Requests;

use App\Rules\ExistsInModel;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Role;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'password' => 'required|string|min:8|confirmed',
        ];

        return $rules;

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
