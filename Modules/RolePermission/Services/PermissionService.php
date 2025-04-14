<?php

namespace Modules\RolePermission\Services;

use App\Services\BaseService;
use Modules\RolePermission\Contracts\PermissionServiceContract;
use Modules\RolePermission\Repositories\PermissionRepository;

class PermissionService extends BaseService implements PermissionServiceContract
{

    public function __construct(PermissionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function checkPermission($data){
        return  $this->repository->checkPermission($data);
    }
    public function userPermissions($user){
        return  $this->repository->userPermissions($user);
    }

    public function givePermissionToRole($data):array
    {
        return  $this->repository->givePermissions($data);
    }
}
