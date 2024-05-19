<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface IUserRepository
{
    public function CreateUser(array $fields): User;
    public function GetUserByEmail(string $email): ?User;
    public function GetRoles(): Collection;
    public function GetUserById(int $userId): ?User;
    public function GetUserDetailsById(int $userId): ?User;
    public function SearchUsers(array  $filters): ?LengthAwarePaginator;
    public function SearchUserBorrowedBooks(array $filters): ?LengthAwarePaginator;
    public function SearchUserHistoryBooks(array $filters): ?LengthAwarePaginator;
    public function AddUser(array $fields): bool;
    public function UpdateUser(array $fields): bool;
    public function DeleteUser(int $userId): bool;
}
