<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUserRequest;
use App\Http\Requests\BookSearchRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserBorrowedBookRequest;
use App\Http\Requests\UserSearchRequest;
use App\Interfaces\IUserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    private IUserService $userService;

    public function __construct(IUserService $userService){
        $this->userService = $userService;
    }

    public function GetRoles(): JsonResponse
    {
        $result = $this->userService->GetRoles();
        return response()->json($result);
    }

    public function GetUserDetails(int $userId): JsonResponse
    {
        $result = $this->userService->GetUserDetailsById($userId);
        return response()->json($result);
    }

    public function SearchUsers(UserSearchRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $result = $this->userService->SearchUsers($validated);

        return response()->json($result);
    }

    public function SearchUserBorrowedBooks(UserBorrowedBookRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $result = $this->userService->SearchUserBorrowedBooks($validated);

        return response()->json($result);
    }

    public function SearchUserHistoryBooks(UserBorrowedBookRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $result = $this->userService->SearchUserHistoryBooks($validated);

        return response()->json($result);
    }

    public function AddUser(AddUserRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $result = $this->userService->AddUser($validated);

        return response()->json($result);
    }

    public function UpdateUser(UpdateUserRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $result = $this->userService->UpdateUser($validated);

        return response()->json($result);
    }

    public function DeleteUser(int $userId): JsonResponse
    {
        $result = $this->userService->DeleteUser($userId);
        return response()->json($result);
    }

    public function GetProfile(): JsonResponse
    {
        $result = $this->userService->GetUserDetailsById(auth()->id());
        return response()->json($result);
    }

}
