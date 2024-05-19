<?php

namespace App\Repositories;


use App\Exceptions\CustomException;
use App\Models\Book;
use App\Models\Role;
use App\Models\Transactions;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserRepository implements IUserRepository
{
    /**
     * @throws Exception
     */
    public function CreateUser(array $fields): User
    {
        $fields['password'] = bcrypt($fields['password']);
        return User::create($fields);
    }

    public function GetUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function GetRoles(): Collection
    {
       return Role::select('id', 'name')->get();
    }

    public function GetUserById(int $userId): ?User
    {
        return User::select('id')
            ->with([
                'UserDetails' =>
                    fn($query) => $query->select('user_id','photo_url'),
            ])->find($userId);
    }

    public function GetUserDetailsById(int $userId): ?User
    {
        return User::select('id', 'email', 'first_name', 'last_name')
            ->with([
                'UserDetails' =>
                    fn($query) => $query->select('user_id' ,'identity_number', 'address', 'phone', 'occupation', 'birth_date', 'photo_url'),
                'Roles'
            ])->find($userId);
    }

    public function SearchUsers(array $filters): ?LengthAwarePaginator
    {
        $query = User::select('id', 'first_name', 'last_name');
//            ->whereDoesntHave('Roles', function ($query) {
//                $query->where('name','admin');
//            });

        $words = explode(" ", $filters['name']);
        if(count($words) >= 2){
            $firstName = trim(preg_replace('/\s+/', ' ', implode(" ", array_slice($words, 0, count($words) - 1))));
            $lastName = $words[count($words) - 1];
            $query->where('first_name', 'like', '%'.$firstName.'%')
                ->Where('last_name', 'like', '%'.$lastName.'%')
                ->orWhere(function ($query) use($filters) {
                    $query->where('first_name', 'like', '%'.$filters['name'].'%');
                });
        }elseif($words[0] != ""){
            $query->where(function ($query) use ($words){
                $query->where('first_name', 'like', '%' . $words[0] . '%')
                    ->orWhere('last_name', 'like', '%' . $words[0] . '%');
            });
        }

        $query->withCount([
            'Transaction' => fn($query) => $query->where('is_returned', false),
        ]);

        return $query->paginate($filters['pagination']['per_page'], null, null, $filters['pagination']['page']);
    }

    public function SearchUserBorrowedBooks(array $filters): ?LengthAwarePaginator
    {
        $transaction = Transactions::select('id', 'book_id', 'borrow_date', 'return_date', 'lender_name', 'receiver_name')
            ->where('user_id', $filters['user'])
            ->where('is_returned', $filters['isReturned'])
            ->with([
                'Book' => fn($query) => $query->select('id', 'title', 'image', 'is_marked'),
            ])
            ->orderByDesc('updated_at');

        if(isset($filters['title']) && $filters['title'] != '') {
            $transaction->whereHas('Book', function ($book) use ($filters) {
                $book->where('title', 'like', '%' . $filters['title'] . '%');
            });
        }

        return $transaction->paginate($filters['pagination']['per_page'], null, null, $filters['pagination']['page']);
    }

    public function SearchUserHistoryBooks(array $filters): ?LengthAwarePaginator
    {
        $book = Book::select('id', 'title', 'image', 'is_marked')
            ->with([
                'Transaction' => fn($query) => $query->select('id', 'book_id', 'borrow_date', 'return_date', 'lender_name', 'receiver_name')
            ])
            ->whereHas('Transaction', function ($transaction) use ($filters) {
                $transaction->where('is_returned', false)
                    ->where('user_id', $filters['user']);
            });

        if(isset($filters['title']) && $filters['title'] != '') {
            $book->where('title', 'like', '%' . $filters['title'] . '%');
        }

        return $book->paginate($filters['pagination']['per_page'], null, null, $filters['pagination']['page']);
    }

    /**
     * @throws CustomException
     */
    public function AddUser(array $fields): bool
    {
        try {
            DB::beginTransaction();

            $fields['password'] = bcrypt($fields['password']);
            $user = User::create($fields);
            $user->UserDetails()->create($fields);
            $user->Roles()->attach($fields['roles']);

            DB::commit();
        } catch (Exception) {
            DB::rollBack();

            throw new CustomException('Failed to add the user. Please contact the administrator');
        }
        return true;
    }

    /**
     * @throws CustomException
     */
    public function UpdateUser(array $fields): bool
    {
        try {
            DB::beginTransaction();

            $user = User::find($fields['userId']);
            $user->fill($fields)->update();
            if(!$user->UserDetails){
                $user->userDetails()->create($fields);
            }else {
                $user->UserDetails->fill($fields)->update();
            }
            $user->Roles()->sync($fields['roles']);

            DB::commit();
        } catch (Exception) {
            DB::rollBack();

            throw new CustomException('Failed to update the user. Please contact the administrator');
        }
        return true;
    }

    /**
     * @throws CustomException
     */
    public function DeleteUser(int $userId): bool
    {
        try {
            DB::beginTransaction();

            $user = User::find($userId);
            $user->UserDetails()->delete();
            $user->Roles()->detach();
            $user->delete();

            DB::commit();
        } catch (Exception) {
            DB::rollBack();

            throw new CustomException('Failed to delete the user. Please contact the administrator');
        }
        return true;
    }

}
