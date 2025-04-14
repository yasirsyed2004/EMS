<?php

namespace Modules\RolePermission\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\RolePermission\Contracts\PermissionRepositoryContract;
use Spatie\Permission\Models\Permission;
use Modules\User\Entities\User;
use Spatie\Permission\Models\Role;

class PermissionRepository extends BaseRepository implements PermissionRepositoryContract
{
    protected $roleModel, 
    $userModel;
    /**
     * PermissionRepository constructor.
     *
     * @param Permission $model The Eloquent model.
     */
    public function __construct(Permission $model)
    {
        parent::__construct($model);
        $this->roleModel = resolve(Role::class);
        $this->userModel = resolve(User::class);
    }


    public function all(array $filters, ?array $withRelations = []): Collection
    {
        return $this->applyQueryFilters($this->getQueryInstance($withRelations), $filters)->get();
    }

    public function index(array $filters, ?array $withRelations = []): LengthAwarePaginator
    {

        return $this->applyQueryFilters($this->getQueryInstance($withRelations), $filters)->paginate($filters['per_page'] ?? 25);
    }

    private function getQueryInstance(?array $withRelations = [])
    {
        return $this->model->newQuery()
            ->with($withRelations);
    }

    private function applyQueryFilters(Builder $query, array $filters): Builder
    {
        foreach ($filters as $filter => $value) {
            $this->applyFilter($query, $filter, $value, $filters);
        }
        return $query;
    }

    public function applyFilter(Builder &$query,  $filter, $value, $allFilters = null): void
    {
        switch ($filter) {
            case 'search':
                $query->where('name', 'LIKE', "%{$value}%");
                break;
            case 'sort_by':
                $order = $allFilters['order'] ?? 'desc';
                $query->orderBy($value, $order);
                break;
        }
    }

    public function store(array $data):Model
    {
        $permission =  $this->model->create($data);
        return $permission;
    }

    public function update(int $id, array $data): Model
    {
        $permission =  $this->model->findById($id);
        $permission->update([
            'name' => $data['name']
        ]);
        return $permission;
    }

    public function destroy(int $id): ?bool
    {
        $role = $this->show($id);
        return $role->delete();
    }

    public function checkPermission($data): ?bool
    {
        $user = $this->userModel->find($data['user_id']);
        $permission = $this->model->find($data['permission_id']);
        return $is_assigned = $user->hasPermissionTo($permission);
    }

    public function userPermissions($user)
    {
        $permissions = $user->getAllPermissions();
        $permissionNames = $permissions->pluck('name')->toArray();
        return $permissionNames;
    }

    public function givePermissions($data):array
    {
        $status = false;
        $role = $this->roleModel->find($data['role_id']);
        $permissions = $this->model->findMany($data['permission_ids']);
        if($permissions){
            $role->givePermissionTo($permissions);
            $status = true;
            return [
                'message' => 'Permission added successfully to role.',
                'status' => $status,
                'role' => $role->name,
                'permissions' => $role->permissions->pluck('name'),
            ];
        }else{
            return [
                'message' => 'Permission Does not exists.',
                'role' => $role->name,
                'status' => $status,
                'permissions' => $role->permissions->pluck('name'),
            ];
        }
    }



}
