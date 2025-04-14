<?php

namespace Modules\RolePermission\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Modules\User\Entities\User;
use Modules\RolePermission\Http\Requests\PermissionCheckRequest;
use Modules\RolePermission\Http\Requests\PermissionGetRequest;
use Modules\RolePermission\Http\Requests\PermissionStoreRequest;
use Modules\RolePermission\Http\Requests\PermissionUpdateRequest;
use Modules\RolePermission\Transformers\PermissionResource;
use Modules\RolePermission\Contracts\PermissionServiceContract;
use Modules\RolePermission\Http\Requests\GivePermissionToRoleRequest;

class PermissionController extends BaseController
{
    protected PermissionServiceContract $service;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function __construct(PermissionServiceContract $service)
    {
        $this->service = $service;
    }

    public function index(PermissionGetRequest $request)
    {
        return $this->tryCatch(function () use ($request) {
            $filters = $this->service->prepareFilters($request->validated());
            $permissions = $this->service->{$filters['method']}($filters, []);
            return PermissionResource::collection($permissions)->response();
        });
    }

    public function store(PermissionStoreRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $permission = $this->service->store($request->validated());
            return (new PermissionResource($permission))->response();
        });
    }


    public function update(PermissionUpdateRequest $request, $id)
    {
        return $this->tryCatch(function () use ($request, $id) {
            $permission = $this->service->update($id, $request->validated());
            return (new PermissionResource($permission))->response();
        });
    }

    public function show($id)
    {
        $this->validateId($id, 'Spatie\Permission\Models\Permission');
        return $this->tryCatch(function () use ($id) {
            $permission = $this->service->show($id, []);
            return (new PermissionResource($permission))->response();
        });
    }

    public function userPermissions(User $user)
    {
        return $this->tryCatch(function () use ($user) {
            if ($user) {
                $permissionNames = $this->service->userPermissions($user);
                return response()->json(['permissions' => $permissionNames], 200);
            } else {
                return response()->json(['permissions' => []], 200);
            }
        });
    }

    public function checkPermission(PermissionCheckRequest $request)
    {
        return $this->tryCatch(function () use ($request) {
            $is_assigned = $this->service->checkPermission($request->validated());
            return response()->json(['status' => $is_assigned], 200);
        });
    }

    public function destroy($id)
    {
        $this->validateId($id, 'Spatie\Permission\Models\Permission');
        return $this->tryCatch(function () use ($id) {
            $response = $this->service->destroy($id);
            if($response){
                return response()->json([
                    'status' => true,
                    'message' => "Record Deleted Successfully",
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => "This Role cannot be deleted",
                ]);
            }
        });
    }

    public function addPermissionToRole(GivePermissionToRoleRequest $request)
    {
        return $this->tryCatch(function () use ($request) {
            $data = $request->validated();
            $response = $this->service->givePermissionToRole($data);
             return response()->json(
                [
                    'message' => $response['message'],
                    'status' => $response['status'],
                    'role' => $response['role'],
                    'permissions' => $response['permissions'],
                ]);
        });
    }
}
