<?php

namespace Modules\Department\Http\Requests;

use App\Http\Requests\BaseRequest;
use Modules\Department\Entities\Department;

class DepartmentRequest extends BaseRequest
{

    public function __construct()
    {
        parent::__construct(Department::class);
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
            'code' => 'nullable|sometimes|string|max:255',
            'description' => 'nullable|sometimes|string|max:3000',
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
