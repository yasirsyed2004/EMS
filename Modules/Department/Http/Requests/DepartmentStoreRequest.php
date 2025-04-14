<?php

namespace Modules\Department\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255|unique:Modules\Department\Entities\Department,name',            
            'code' => 'required|string|max:255',
            'description' => 'required|string|max:3000',
        ];

        if ($this->isMethod('put')) {
            $id = $this->route('department') ?? $this->route('id');
            $rules['name'] = 'unique:Modules\Department\Entities\Department,name,' . $id;
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
