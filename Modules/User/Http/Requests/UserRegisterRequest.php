<?php

namespace Modules\User\Http\Requests;

use App\Rules\ExistsInModel;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Role;

class UserRegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'name' => 'required|string',
            'email' => 'required|email|unique:Modules\User\Entities\User,email',
            'password' => 'required|string|min:8|confirmed',
            'role_ids' => ['nullable', 'sometimes', new ExistsInModel(Role::class,'id')],
        ];

        if ($this->isMethod('put')) {
            $id = $this->route('user') ?? $this->route('id');
            $rules['email'] = 'unique:Modules\User\Entities\User,email,' . $id;
            $rules['password'] = 'nullable|string|min:8|confirmed';
            $rules['role_ids'] = 'sometimes';
        }

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
