<?php

namespace Modules\Department\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Department\Contracts\DepartmentServiceContract;
use Modules\Department\Http\Requests\DepartmentRequest;
use Modules\Department\Http\Requests\DepartmentStoreRequest;
use Modules\Department\Transformers\DepartmentResource;

class DepartmentController extends BaseController
{
    protected DepartmentServiceContract $service;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function __construct(DepartmentServiceContract $service)
    {
        $this->service = $service;
        $this->middleware('permission:create_department', ['only' => ['store']]);
        $this->middleware('permission:update_department', ['only' => ['update']]);
        $this->middleware('permission:delete_department', ['only' => ['destroy']]);
        $this->middleware('permission:show_department', ['only' => ['show','index']]);
    }

    public function index(DepartmentRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $filters = $this->service->prepareFilters($request->all());
            $departments = $this->service->{$filters['method']}($filters, []);
            $resourceCollection = DepartmentResource::collection($departments);
            return $resourceCollection->response();
        });
    }
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(DepartmentStoreRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $validatedData = $request->validated();
            $department = $this->service->store($this->service->prepareDataForStorage($validatedData));
            return (new DepartmentResource($department))->response();
        });
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id): JsonResponse
    {
        $this->validateId($id, 'Modules\Department\Entities\Department');

        return $this->tryCatch(function () use ($id) {
            $department = $this->service->show($id);
            $department = $this->loadDepartmentRelationships($department);
            return (new DepartmentResource($department))->response();
        });
    }
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(DepartmentStoreRequest $request, $id): JsonResponse
    {
        $this->validateId($id, 'Modules\Department\Entities\Department');

        return $this->tryCatch(function () use ($request, $id) {
            $visa = $this->service->update($id, $this->service->prepareDataForStorage($request->validated()));
            return (new DepartmentResource($visa))->response();
        });
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id): JsonResponse
    {
        $this->validateId($id, 'Modules\Department\Entities\Department');
        
        return $this->tryCatch(function () use ($id) {
            $this->service->destroy($id);
            return response()->json([
                'status' => true,
                'message' => "Record Deleted Successfully",
            ]);
        });
    }

    /**
     * Load necessary relationships for a department.
     *
     * @param mixed $department The department entity.
     * @return mixed The department entity with loaded relationships.
     */
    protected function loadDepartmentRelationships($department)
    {
        return $department->load([ 
        ]);
    }
}
