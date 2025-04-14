<?php

namespace Modules\RolePermission\Http\Requests;

use App\Http\Requests\BaseRequest;
use App\Rules\BooleanValue;
use Spatie\Permission\Models\Role;

class RolesGetRequest extends BaseRequest
{
    public function __construct()
    {
        parent::__construct(Role::class);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'name' => 'nullable|sometimes|string',
            'display_name' => 'nullable|sometimes|string',
            'all' => ['sometimes', new BooleanValue],
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
