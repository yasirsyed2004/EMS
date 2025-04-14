<?php

namespace Modules\Employee\Services;

use App\Services\BaseService;
use Modules\Employee\Contracts\EmployeeRepositoryContract;
use Modules\Employee\Contracts\EmployeeServiceContract;

class EmployeeService extends BaseService implements EmployeeServiceContract
{
  /**
     * CompanyService constructor.
     *
     * @param EmployeeRepositoryContract $repository
     */
    public function __construct(EmployeeRepositoryContract $repository)
    {
        $this->repository = $repository;
    }
   
}
