<?php

namespace Modules\RolePermission\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ExistsInModel;
use Modules\User\Entities\User;
use Spatie\Permission\Models\Role;

class GiveUserRoleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'user_id' => [ 'required', new ExistsInModel(User::class,'id')],
            'role_ids' => ['required','array', new ExistsInModel(Role::class,'id')],
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
