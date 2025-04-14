<?php

namespace App\Http\Requests;

use App\Rules\BooleanValue;
use App\Rules\ColumnExists;
use Modules\User\Entities\User;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Department\Entities\Department;
use Modules\Employee\Entities\Employee;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class BaseRequest extends FormRequest
{
    protected $model;

    public function __construct($model)
    {
        parent::__construct();

        $this->model = $model;
    }
    /**
     * Retrieve the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules()
    {
        // Define the allowed models for validation
        $allowedModels = [
            Role::class,
            Permission::class,
            User::class,
            Department::class,
            Employee::class,
        ];

        // Check if the provided model is valid
        if (!in_array($this->model, $allowedModels)) {
            throw new \InvalidArgumentException("Invalid model for validation");
        }

        // Get the table name of the model
        $tableName = (new $this->model)->getTable();

        // Define the common validation rules
        return [
            'per_page' => 'sometimes|integer|min:1|max:100',
            'sort_by' => ['sometimes', 'string', new ColumnExists($tableName)],
            'order' => 'sometimes|in:asc,desc',
            'all' => ['sometimes', new BooleanValue],
            'search' => 'sometimes|string|max:255',
        ];
    }
}
