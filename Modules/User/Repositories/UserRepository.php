<?php

namespace Modules\User\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\BaseRepository;
use App\Utils\HttpStatusCode;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Modules\User\Contracts\UserRepositoryContract;
use Modules\User\Entities\User;

class UserRepository extends BaseRepository implements UserRepositoryContract
{
    /**
     * UsertRepository constructor.
     *
     * @param User $model The Eloquent model.
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
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
                $query->search($value);
                break;
            case 'role_ids':
                $query->whereHas('roles', function ($query) use ($value) {
                    $this->applyConditionalWhere($query, 'id', $value);
                });
                break;
            case 'permission_ids':
                $query->whereHas('permissions', function ($query) use ($value) {
                    $this->applyConditionalWhere($query, 'id', $value);
                });
                break;
            case 'from_roles':
                $query->where('name', '!=', config('app.admin_role'));
                break;
            case 'sort_by':
                $order = $allFilters['order'] ?? 'desc';
                $query->orderBy($value, $order);
                break;
        }
    }

    public function authenticate($data)
    {
        if (!Auth::attempt($data)) {
            return [
                'code' => HttpStatusCode::UNAUTHORIZED,
                'message' => 'Invalid Email/Password',
            ];
        }
        $user = Auth::user();
        $token = $user->createToken(env('POSTMAN_TOKEN_NAME'))->accessToken;
        $admin = $user->name === config('app.admin_role');
        if ($admin) {
            $user->assignRole(config('app.admin_role'));
        }
        $user_permissions = $user->getAllPermissions()->map(function ($permission) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'description' => $permission->description,
            ];
        })->toArray();
        $user->last_login = now()->format('Y-m-d h:i:s');
        $user->save();
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'last_login' => $user->last_login->format('Y-m-d h:i:s'),
            'permissions' => $user_permissions,
            'roles' => $user->rolesWithoutPivot()->toArray(),
            'admin' => $admin,
            'token' => $token,
        ];
    }

    public function logout($user)
    {
        $user->token()->revoke();
        return [
            'code' => HttpStatusCode::OK,
            'message' => 'User Logged out successfully',
        ];
    }

    public function getCurrentUser()
    {
        $user = Auth::user();
        if (!$user) {
            return [
                'code' => HttpStatusCode::OK,
                'message' => 'User Not Found',
            ];
        }
        $admin = false;
        if ($user->hasRole(config('app.admin_role'))) {
            $admin = true;
        }
        $permissions = $user->getAllPermissions();
        $user_permissions = [];
        foreach ($permissions as $permission) {
            $user_permissions[] = array(
                'id' => $permission->id,
                'name' => $permission->name,
                'description' => $permission->description,
            );
        }
        $return_result = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'permissions' => $user_permissions,
            'roles' => $user->rolesWithoutPivot(),
            'admin' => $admin,
        ];
        return $return_result;
    }

    public function store(array $data): Model
    {
        $user = $this->model->create($data);
        if (isset($data['role_ids']) && !empty($data['role_ids'])) {
            $roles = array_map('trim', explode(',', $data['role_ids']));
            foreach ($roles as $role) {
                $user->assignRole($role);
            }
        }
        return $user;
    }

    public function update(int $id, array $data): Model
    {
        $user = $this->model->find($id);
        $user->update($data);
        if (isset($data['role_ids']) && !empty($data['role_ids'])) {
            $roles = array_map('trim', explode(',', $data['role_ids']));
            $user->syncRoles($roles);
        } else {
            $user->roles()->detach();
        }
        return $user;
    }

    public function reset(int $id, array $data): Model
    {
        $user = $this->model->find($id);
        $user->update([
            'password' => $data['password']
        ]);
        return $user;
    }

    public function destroy(int $id): ?bool
    {
        $user = $this->show($id);

        // $hasRestrictedRole = $user->roles->contains(function ($role) {
        //     return in_array($role->name, ['Agency', 'Pax']);
        // });

        // if ($hasRestrictedRole) {
        //     return false;
        // }

        return $user->delete();
    }

}
