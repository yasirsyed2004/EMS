<?php

namespace Modules\RolePermission\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Modules\RolePermission\Http\Requests\RoleStoreRequest;
use Modules\RolePermission\Http\Requests\RolesFilterRequest;
use Modules\RolePermission\Http\Requests\RoleUpdateRequest;
use Modules\RolePermission\Transformers\RolesResource;
use Modules\RolePermission\Contracts\RolesServiceContract;
use Modules\RolePermission\Http\Requests\GiveUserRoleRequest;
use Modules\RolePermission\Http\Requests\RolesGetRequest;

class RoleController extends BaseController
{
    protected RolesServiceContract $service;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function __construct(RolesServiceContract $service)
    {
        $this->service = $service;
    }
    public function index(RolesGetRequest $request)
    {

        return $this->tryCatch(function () use ($request) {
            $filters = $this->service->prepareFilters($request->validated());
            $roles = $this->service->{$filters['method']}($filters, ['users','permissions']);
            return RolesResource::collection($roles)->response();
        });
    }

    public function store(RoleStoreRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
             $role = $this->service->store($request->validated());
            return (new RolesResource($role))->response();
        });
    }

    public function update(RoleUpdateRequest $request, $id): JsonResponse
    {

        return $this->tryCatch(function () use ($request, $id) {
            $role = $this->service->update($id, $request->validated());

            return (new RolesResource($role))->response();
        });
    }

     public function show($id): JsonResponse
    {
        $this->validateId($id, 'Spatie\Permission\Models\Role');
        return $this->tryCatch(function () use ($id) {
            $role = $this->service->show($id, []);
            return (new RolesResource($role))->response();
        });
    }

    public function destroy($id): JsonResponse
    {
        $this->validateId($id, 'Spatie\Permission\Models\Role');
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

    public function updateUserRoles(GiveUserRoleRequest $request)
    { 
        return $this->tryCatch(function () use ($request) {
            $data = $request->validated();
            $response = $this->service->giveRoleToUser($data);
            return response()->json(
                [
                    'message' => $response['message'],
                    'status' => $response['status'],
                    'roles' => $response['roles'],
                ]);
        });
    }
}
