<?php

namespace Modules\RolePermission\Services;

use App\Services\BaseService;
use Modules\RolePermission\Contracts\RolesServiceContract;
use Modules\RolePermission\Repositories\RolesRepository;

class RolesService extends BaseService implements RolesServiceContract
{

    public function __construct(RolesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function giveRoleToUser($data):array
    {
        return $this->repository->roleToUser($data);
    }
}
