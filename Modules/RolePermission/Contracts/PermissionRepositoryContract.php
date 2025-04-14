<?php

namespace Modules\RolePermission\Contracts;

use App\Contracts\BaseRepositoryContract;

/**
 * Contract for the Passport service.
 */
interface PermissionRepositoryContract extends BaseRepositoryContract
{
    public function checkPermission($data);
    public function userPermissions($user);
}
