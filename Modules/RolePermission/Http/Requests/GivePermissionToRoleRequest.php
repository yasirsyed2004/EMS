<?php

namespace Modules\RolePermission\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Permission;
use App\Rules\ExistsInModel;
use Spatie\Permission\Models\Role;

class GivePermissionToRoleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'role_id' => [ 'required', new ExistsInModel(Role::class,'id')],
            'permission_ids' => ['required','array', new ExistsInModel(Permission::class,'id')],
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
