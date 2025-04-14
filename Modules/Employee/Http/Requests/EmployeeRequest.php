<?php

namespace Modules\Employee\Http\Requests;

use App\Http\Requests\BaseRequest;
use Modules\Employee\Entities\Employee;

class EmployeeRequest extends BaseRequest
{

    public function __construct()
    {
        parent::__construct(Employee::class);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'name' => 'nullable|sometimes|string|max:255',
            'department_id' => 'nullable|sometimes|integer|exists:app\Models\Department,id',
        ]);
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
