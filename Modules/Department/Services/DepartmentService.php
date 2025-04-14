<?php

namespace Modules\Department\Services;

use App\Services\BaseService;
use Modules\Department\Contracts\DepartmentRepositoryContract;
use Modules\Department\Contracts\DepartmentServiceContract;

class DepartmentService extends BaseService implements DepartmentServiceContract
{
  /**
     * CompanyService constructor.
     *
     * @param DepartmentRepositoryContract $repository
     */
    public function __construct(DepartmentRepositoryContract $repository)
    {
        $this->repository = $repository;
    }
   
}
