<?php

namespace Modules\Employee\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Employee\Contracts\EmployeeServiceContract;
use Modules\Employee\Http\Requests\EmployeeRequest;
use Modules\Employee\Http\Requests\EmployeeStoreRequest;
use Modules\Employee\Transformers\EmployeeResource;

class EmployeeController extends BaseController
{
    protected EmployeeServiceContract $service;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function __construct(EmployeeServiceContract $service)
    {
        $this->service = $service;
        $this->middleware('permission:create_employee', ['only' => ['store']]);
        $this->middleware('permission:update_employee', ['only' => ['update']]);
        $this->middleware('permission:delete_employee', ['only' => ['destroy']]);
        $this->middleware('permission:show_employee', ['only' => ['show','index']]);
    }

    public function index(EmployeeRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $filters = $this->service->prepareFilters($request->all());
            $employee = $this->service->{$filters['method']}($filters, ['user','department']);
            $resourceCollection = EmployeeResource::collection($employee);
            return $resourceCollection->response();
        });
    }
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(EmployeeStoreRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $validatedData = $request->validated();
            $employee = $this->service->store($validatedData);
            return (new EmployeeResource($employee))->response();
        });
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id): JsonResponse
    {
        $this->validateId($id, 'Modules\Employee\Entities\Employee');

        return $this->tryCatch(function () use ($id) {
            $employee = $this->service->show($id);
            $employee = $this->loadEmployeeRelationships($employee);
            return (new EmployeeResource($employee))->response();
        });
    }
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(EmployeeStoreRequest $request, $id): JsonResponse
    {
        $this->validateId($id, 'Modules\Employee\Entities\Employee');

        return $this->tryCatch(function () use ($request, $id) {
            // dd($request->validated());
            $employee = $this->service->update($id, $request->validated());
            return (new EmployeeResource($employee))->response();
        });
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id): JsonResponse
    {
        $this->validateId($id, 'Modules\Employee\Entities\Employee');
        
        return $this->tryCatch(function () use ($id) {
            $this->service->destroy($id);
            return response()->json([
                'status' => true,
                'message' => "Record Deleted Successfully",
            ]);
        });
    }

    /**
     * Load necessary relationships for a Employee.
     *
     * @param mixed $employee The Employee entity.
     * @return mixed The Employee entity with loaded relationships.
     */
    protected function loadEmployeeRelationships($employee)
    {
        return $employee->load([ 
            'user.roles',
            'department',
        ]);
    }
}
