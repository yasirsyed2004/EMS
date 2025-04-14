<?php

namespace Modules\RolePermission\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Permission;
use App\Rules\ExistsInModel;

class RoleStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'name' => 'required|unique:Spatie\Permission\Models\Role,name',
            'display_name' => 'nullable',
            'selectedPermissions' => ['nullable','sometimes', new ExistsInModel(Permission::class, 'id')]
        ];

        if($this->isMethod('put')){
            $rules['name'] = 'sometimes|unique:Spatie\Permission\Models\Role,name,'. $this->route('role');
        }

        // if($this->isMethod('post')){
        //     $rules['name'] = 'required|unique:Spatie\Permission\Models\Role,name';
        // }

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
