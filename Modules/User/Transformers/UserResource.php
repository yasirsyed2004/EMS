<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\RolePermission\Transformers\RolesResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'last_login' => $this->last_login,
            'phone' => $this->phone,
            'roles' => RolesResource::collection($this->whenLoaded('roles')),
            'admin' => $this->hasRole(config('app.admin_role')) ? true : false
        ];
    }
}
