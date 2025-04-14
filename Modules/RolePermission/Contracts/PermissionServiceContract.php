<?php

namespace Modules\RolePermission\Contracts;

use App\Contracts\BaseServiceContract;

/**
 * Contract for the Passport service.
 */
interface PermissionServiceContract extends BaseServiceContract
{
      public function checkPermission($data);
      public function userPermissions($user);
}
