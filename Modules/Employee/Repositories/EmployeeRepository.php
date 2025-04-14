<?php

namespace Modules\Employee\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Modules\Employee\Contracts\EmployeeRepositoryContract;
use Modules\Employee\Entities\Employee;
use Modules\User\Entities\User;
use Spatie\Permission\Models\Role;

class EmployeeRepository extends BaseRepository implements EmployeeRepositoryContract
{
    protected $user;
    protected $role;
    /**
     * EmployeetRepository constructor.
     *
     * @param Employee $model The Eloquent model.
     */
    public function __construct(Employee $model)
    {
        parent::__construct($model);
        $this->user = resolve(User::class);
        $this->role = resolve(Role::class);
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
        return   $this->model->newQuery()->with($withRelations);
    }

    private function applyQueryFilters(Builder $query, array $filters): Builder
    {
        if ($this->shouldFilterByDepartment()) {
            // dd($this->getUserDepartmentId());
            $filters['department_id'] = $this->getUserDepartmentId();
        }
        if (auth()->user()?->hasRole('manager')) {
            $query->where('user_id', '!=', auth()->id());
        }
        foreach ($filters as $filter => $value) {
            $this->applyFilter($query, $filter, $value, $filters);
        }
        return $query;
    }
    public function applyFilter(Builder &$query,  $filter, $value, $allFilters = null): void
    {
        switch ($filter) {
            case 'search':
                $query->searchDetails($value);
                break;
            case 'sort_by':
                $order = $allFilters['order'] ?? 'desc';
                $query->orderBy($value, $order);
                break;
            case 'department_id':
                $this->applyConditionalWhere($query, 'department_id', $value);
                break;
        }
    }

    protected function shouldFilterByDepartment(): bool
    {
        $user = Auth::user();
        return $user->isManager();
    }

    protected function getUserDepartmentId(): ?int
    {
        $user = Auth::user();
        return $user->employee->department->id ?? null;
    }
    public function store(array $data): Model
    {
        
        if (!empty($data['email']) && !empty($data['password'])) {
            $userData = [
                'email' => $data['email'],
                'password' => $data['password'],
                'name' => $data['name'] ?? '',
                'phone' => $data['phone'] ?? ''
            ];
            $user = $this->user->create($userData);
            if($user){
            if(!empty($data['role_id'])){
               
                $user->assignRole($data['role_id']);
            }else{
                 $this->assignRoleToEmployee($user);
            }
            $data['user_id'] = $user->id;
            }
        }

        $employee = $this->model->create($data);

        return $employee;
    }

    public function update(int $id, array $data): Model
    {
        $employee = $this->model->findOrFail($id);
        $employee->update($data);

        // Initialize userData array
        $userData = [];
        
        
        if (isset($data['name'])) {
            $userData['name'] = $data['name'];
        }      
        if (!empty($data['email'])) {
            $userData['email'] = $data['email'];
            
            if (isset($data['password']) && !empty($data['password'])) {
                $userData['password'] = $data['password'];
            } 
            $userData['phone'] = $data['phone'];
            

            if ($employee->user()->exists()) {
                $user = $employee->user;
                // if(!$user->isEmployee()){
                //     $this->assignRoleToEmployee($user);
                // }
                $user->update($userData);
            } else {
                $user = $this->user->create($userData);

                if ($user) {
                    if(!empty($data['role_id'])){
               
                        $user->assignRole($data['role_id']);
                    }
                    // $this->assignRoleToEmployee($user);
                    $employee->user_id = $user->id;
                    $employee->save();
                }
            }
        }

        return $employee;
    }

    public function destroy(int $id): ?bool
    {
        $employee = $this->model->find($id);
        if ($employee->user()->exists()) {
            $employee->user()->delete();
        }
        return $employee->delete();
    }

    protected function assignRoleToEmployee($user)
    {
        $role = $this->role->where('name', 'employee')->first();
        $user->assignRole($role);
    }
}
