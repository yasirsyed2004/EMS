<?php

namespace Modules\Employee\Http\Requests;

use App\Rules\ExistsInModel;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Employee\Entities\Employee;
use Spatie\Permission\Contracts\Role;

class EmployeeStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'joining_date' => 'required|date',
            'email' => ['required','sometimes','email','unique:Modules\User\Entities\User,email'],
            'phone' => 'required|sometimes|string|max:20|unique:Modules\User\Entities\User,phone',
            'department_id' => 'required|sometimes|integer|exists:Modules\Department\Entities\Department,id',
            'role_id' => ['nullable', 'sometimes', 'integer', new ExistsInModel(Role::class, 'id')],
            'password' => ['nullable','string', 'min:8','max:255','confirmed'],
        ];

        if ($this->isMethod('post')) {
            if($this->input('email')){
                $rules['password'][] = 'required';
            }
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            if($this->input('email')){
                $user_id = $this->getModelUserId('employee',$this->route('employee'));
                $rules['email'] = 'sometimes|unique:Modules\User\Entities\User,email,' . $user_id;
                // $rules['password'][] = 'required';
            }
            $rules['phone'] = 'sometimes|unique:Modules\User\Entities\User,phone,' . $user_id;
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

    function getModelUserId($Model, $id)
{
    switch ($Model) {
        case 'employee':
            $modelClass = Employee::class;
            break;
        default:
            return null;
    }
    $record = $modelClass::find($id);
    if ($record) {
        return $record->user_id;
    }
}
}
