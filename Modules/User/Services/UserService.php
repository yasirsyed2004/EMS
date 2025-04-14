<?php

namespace Modules\User\Services;

use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Contracts\UserRepositoryContract;
use Modules\User\Contracts\UserServiceContract;

class UserService extends BaseService implements UserServiceContract
{
    /**
     * CompanyService constructor.
     *
     * @param UserRepositoryContract $repository
     */
    public function __construct(UserRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    public function authenticate($data)
    {
        return  $this->repository->authenticate($data);
    }
    public function logout($data)
    {
        return  $this->repository->logout($data);
    }
    public function getCurrentUser()
    {
        return  $this->repository->getCurrentUser();
    }
    public function registerUser($data)
    {
         return  $this->repository->registerUser($data);
    }

    public function resetPassword(int $id, array $data):Model
    {
        return $this->repository->reset($id, $data);
    }
}
