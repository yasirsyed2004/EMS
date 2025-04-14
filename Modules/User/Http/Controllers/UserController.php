<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\User\Contracts\UserServiceContract;
use Modules\User\Http\Requests\ResetPasswordRequest;
use Modules\User\Http\Requests\UserRegisterRequest;
use Modules\User\Http\Requests\UserRequest;
use Modules\User\Transformers\UserResource;

class UserController extends BaseController
{
    protected UserServiceContract $service;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function __construct(UserServiceContract $service)
    {
        $this->service = $service;

        // $this->middleware('permission:USERS_GET', ['only' => ['index', 'show']]);
        // $this->middleware('permission:USER_ADD', ['only' => ['store']]);
        // $this->middleware('permission:USER_EDIT', ['only' => ['update']]);
        // $this->middleware('permission:USER_DELETE', ['only' => ['destroy']]);

    }

    public function index(UserRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $filters = $this->service->prepareFilters($request->validated());
            $users = $this->service->{$filters['method']}($filters, ['roles.permissions']);
            $resourceCollection = UserResource::collection($users);
            return $resourceCollection->response();
        });
    }
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(UserRegisterRequest $request): JsonResponse
    {
        return $this->tryCatch(function () use ($request) {
            $validatedData = $request->validated();
            $user = $this->service->store($validatedData);
            return (new UserResource($user))->response();
        });
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id): JsonResponse
    {
        $this->validateId($id, 'Modules\User\Entities\User');
        return $this->tryCatch(function () use ($id) {
            $user = $this->service->show($id, ['roles.permissions']);
            return (new UserResource($user))->response();
        });
    }
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(UserRegisterRequest $request, $id): JsonResponse
    {
        $this->validateId($id, 'Modules\User\Entities\User');
        return $this->tryCatch(function () use ($request, $id) {
            $user = $this->service->update($id, $request->validated());
            return (new UserResource($user))->response();
        });
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id): JsonResponse
    {
        $this->validateId($id, 'Modules\User\Entities\User');
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
                    'message' => "This user cannot be deleted",
                ]);
            }
        });
    }

    public function resetPassword(ResetPasswordRequest $request, $id){
        $this->validateId($id, 'Modules\User\Entities\User');
        return $this->tryCatch(function () use ($request, $id) {
            $user = $this->service->resetPassword($id, $request->validated());
            return (new UserResource($user))->response();
        });
    }
}
