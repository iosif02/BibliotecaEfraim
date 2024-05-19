<?php

namespace App\Interfaces;


use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface IUserService
{
    public function GetRoles(): Collection;
    public function SearchUsers(array $filters): ?LengthAwarePaginator;
    public function SearchUserBorrowedBooks(array $filters): ?LengthAwarePaginator;
    public function SearchUserHistoryBooks(array $filters): ?LengthAwarePaginator;
    public function GetUserDetailsById(int $userId): User;
    public function AddUser(array $fields): bool;
    public function UpdateUser(array $fields): bool;
    public function DeleteUser(int $userId): bool;
}
