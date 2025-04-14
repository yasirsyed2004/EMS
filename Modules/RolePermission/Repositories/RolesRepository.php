<?php

namespace Modules\RolePermission\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\RolePermission\Contracts\RolesRepositoryContract;
use Modules\User\Entities\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RolesRepository extends BaseRepository implements RolesRepositoryContract
{
    protected $permissionModel,
    $userModel;
    /**
     * RolesRepository constructor.
     *
     * @param Role $model The Eloquent model.
     */
    public function __construct(Role $model)
    {
        parent::__construct($model);
        $this->permissionModel = resolve(Permission::class);
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
                $query->where('name', 'LIKE', "%{$value}%")
                ->orWhere('display_name', 'LIKE', "%{$value}%");
                break;
            case 'sort_by':
                $order = $allFilters['order'] ?? 'desc';
                $query->orderBy($value, $order);
                break;
        }
    }





    public function store(array $data):Model
    {

        $role = $this->model->create([
            'name' => $data['name'],
            'display_name' => $data['display_name'],
            'guard_name' => 'api'
        ]);
        if(isset($data['selectedPermissions']) && count($data['selectedPermissions'])){
            foreach ($data['selectedPermissions'] as  $permission) {
                $permission = $this->permissionModel->findById($permission);
                $permission->assignRole($role);
            }
        }
        return $role;
    }

    public function update(int $id, array $data): Model
    {
        $role = $this->model->find($id);
        $role->update([
            'name' => $data['name'],
            'display_name' => $data['display_name'],
        ]);

        $role->permissions()->sync($data['selectedPermissions']);
        return $role;
    }

    public function destroy(int $id): ?bool
    {
        $role = $this->show($id);
        return $role->delete();
    }

    public function roleToUser($data):array
    {
        $user = $this->userModel->findOrFail($data['user_id']);
        
        $currentRoleIds = $user->roles->pluck('id')->toArray();
        $mergedRoleIds = array_unique(array_merge($currentRoleIds, $data['role_ids']));

        $user->syncRoles($mergedRoleIds);
        return [
            'message' => 'Roles added successfully.',
            'status' => true,
            'roles' => $user->getRoleNames(),
        ];
    }








}
