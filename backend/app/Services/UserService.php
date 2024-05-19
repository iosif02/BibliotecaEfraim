<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Interfaces\IFileService;
use App\Interfaces\IUserService;
use App\Models\User;
use App\Repositories\IUserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class UserService implements IUserService
{
    private IUserRepository $userRepository;

    private IFileService $fileService;

    public function __construct(IUserRepository $userRepository, IFileService $fileService){
        $this->userRepository = $userRepository;
        $this->fileService = $fileService;
    }

    public function GetRoles(): Collection
    {
        return $this->userRepository->GetRoles();
    }

    /**
     * @throws CustomException
     */
    public function GetUserDetailsById(int $userId): User
    {
        $user = $this->userRepository->GetUserDetailsById($userId);
        if(!$user)
            throw new CustomException('User not found. Please contact the administrator');
        return $user;
    }

    public function SearchUsers(array $filters): ?LengthAwarePaginator
    {
        return $this->userRepository->SearchUsers($filters);
    }

    public function SearchUserBorrowedBooks(array $filters): ?LengthAwarePaginator
    {
        return $this->userRepository->SearchUserBorrowedBooks($filters);
    }

    public function SearchUserHistoryBooks(array $filters): ?LengthAwarePaginator
    {
        return $this->userRepository->SearchUserHistoryBooks($filters);
    }

    public function AddUser(array $fields): bool
    {
        return $this->userRepository->AddUser($fields);
    }

    /**
     * @throws CustomException
     */
    public function UpdateUser(array $fields): bool
    {
        $user = $this->userRepository->GetUserById($fields['userId']);
        if(!$user)
            throw new CustomException('User not found!');

        if(isset($fields['imageFile']) && $fields['imageFile'] != ''){
            $imageName = $this->fileService->StoreFile($fields['imageFile'], 'profile');
            $fields['photo_url'] = $imageName;

            $this->fileService->DeleteFile($user->UserDetails['photo_url']);
        }

        if(isset($fields['password']) && $fields['password']!= '')
            $fields['password'] = bcrypt($fields['password']);

        return $this->userRepository->UpdateUser($fields);
    }

    /**
     * @throws CustomException
     */
    public function DeleteUser(int $userId): bool
    {
        $user = $this->userRepository->GetUserById($userId);
        if(!$user)
            throw new CustomException('User not found!');
        return $this->userRepository->DeleteUser($userId);
    }

}
