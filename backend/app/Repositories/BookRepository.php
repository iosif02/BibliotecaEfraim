<?php

namespace App\Repositories;

use App\Exceptions\CustomException;
use App\Models\Book;
use App\Models\Transactions;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookRepository implements IBookRepository
{
    /**
     * @throws Exception
     */
    public function AddBook(array $fields): bool
    {
        try {
            DB::beginTransaction();

            $book = Book::create($fields);
            $book->Authors()->attach($fields['authors']);

            DB::commit();
        } catch (Exception) {
            DB::rollBack();

            throw new CustomException('Failed to add the book. Please contact the administrator!');
        }
        return true;
    }

    /**
     * @throws CustomException
     */
    public function UpdateBook(array $fields): bool
    {
        try {
            DB::beginTransaction();

            $book = Book::find($fields['bookId']);
            $book->fill($fields);
            $book->update();
            $book->Authors()->sync($fields['authors']);

            DB::commit();
        } catch (Exception) {
            DB::rollBack();

            throw new CustomException('Failed to update the book. Please contact the administrator!');
        }
        return true;
    }

    /**
     * @throws CustomException
     */
    public function DeleteBook(int $bookId): bool
    {
        try {
            DB::beginTransaction();

            $book = Book::find($bookId);
            $book->Authors()->detach();
            $book->Transaction()->delete();
            $book->delete();

            DB::commit();
        } catch (Exception) {
            DB::rollBack();

            throw new CustomException('Failed to delete the book. Please contact the administrator!');
        }
        return true;
    }

    public function GetBookById(int $bookId): ?Book
    {
        return Book::find($bookId);
    }
    public function GetBookDetailsById(int $bookId): ?Model
    {
        return Book::select('id', 'title', 'image', 'quantity', 'is_marked','publisher_id', 'category_id', 'year', 'price')
            ->with([
                'Category' => fn($query) => $query->select('id', 'name', 'number'),
                'Authors' => fn($query) => $query->select('authors.id', 'name'),
                'Publisher' => fn($query) => $query->select('id', 'name'),
                'Transaction' => function ($query){
                    $query->select('id', 'book_id', 'user_id', 'borrow_date', 'return_date', 'is_returned', 'lender_name')
                        ->where('is_returned', false);
                },
            'Transaction.User' => fn($query) => $query->select('id', 'first_name', 'last_name')
        ])
        ->withCount([
            'Transaction' => function ($query){
                $query->where('is_returned', false);
            }
        ])->find($bookId);
    }

    public function SearchBooks(array $filters): ?LengthAwarePaginator
    {
        $book = Book::select('books.id', 'title', 'category_id', 'quantity', 'image', 'is_marked')
            ->with([
                'Category' => fn($query) => $query->select('id', 'name', 'number'),
                'Authors' => fn($query) => $query->select('name')
            ])
            ->orderByDesc('created_at');

        if(isset($filters['category']) && $filters['category'] != 0){
            $book->where('category_id', $filters['category']);
        }

        if(isset($filters['author']) && $filters['author'] != 0){
            $book->whereHas('Authors', function ($authors) use ($filters) {
                $authors->where('authors.id', $filters['author']);
            });
        }

        if(isset($filters['publisher']) && $filters['publisher'] != 0){
            $book->where('publisher_id', $filters['publisher']);
        }

        if(isset($filters['title']) && $filters['title'] != '') {
            $book->where('title', 'like', '%' . $filters['title'] . '%');
        }

        return $book->paginate($filters['pagination']['per_page'], null, null, $filters['pagination']['page']);
    }

    public function SearchGlobalBooks(array $filters): ?LengthAwarePaginator
    {
        $book = Book::select('books.id', 'title', 'category_id', 'quantity', 'image', 'is_marked')
            ->with([
                'Category' => fn($query) => $query->select('id', 'name', 'number'),
                'Authors' => fn($query) => $query->select('name')
            ])
            ->withCount([
                'Transaction' => function ($query){
                    $query->where('is_returned', false);
                }
            ])
            ->orderByDesc('created_at');

        if(isset($filters['title']) && $filters['title'] != ''){
            $book->where('title', 'like', '%' . $filters['title'] . '%')
                ->orWhereHas('Authors', function ($author) use ($filters) {
                    $author->where('name', 'like', '%' . $filters['title'] . '%');
                })
                ->orWhereHas('Transaction', function ($transaction) use ($filters) {
                    $transaction->where('is_returned', false)
                        ->whereHas('User', function ($user) use ($filters) {
                            $user->where(function ($query) use($filters) {
                                $words = explode(" ", $filters['title']);
                                if(count($words) >= 2){
                                    $firstName = trim(preg_replace('/\s+/', ' ', implode(" ", array_slice($words, 0, count($words) - 1))));
                                    $lastName = $words[count($words) - 1];
                                    $query->where('first_name', 'like', '%'.$firstName.'%')
                                        ->Where('last_name', 'like', '%'.$lastName.'%')
                                        ->orWhere(function ($query) use($filters) {
                                            $query->where('first_name', 'like', '%'.$filters['title'].'%');
                                        });
                                }elseif (count($words) != ""){
                                    $query->where('first_name', 'like', '%' . $words[0] . '%')
                                        ->orWhere('last_name', 'like', '%' . $words[0] . '%');
                                }
                            });
                        });
                });
        }

        return $book->paginate($filters['pagination']['per_page'], null, null, $filters['pagination']['page']);

    }

    public function SearchDelayedBooks(array $filters): ?LengthAwarePaginator
    {
        return Transactions::select('id', 'borrow_date', 'return_date', 'user_id', 'book_id')
            ->where('return_date', '<', Carbon::now('Europe/Bucharest')->subDay())->where('is_returned', false)
            ->with([
                'Book' => fn($query) => $query->select('id', 'title', 'category_id', 'image'),
                'User' => fn($query) => $query->select('id', 'first_name', 'last_name'),
                'Book.Category' => fn($query) => $query->select('id', 'name', 'number', 'description')
            ])
            ->whereHas('Book', function ($query) use ($filters) {
                if(isset($filters['title']) && $filters['title'] != '') {
                    $query->where('title', 'like', '%'.$filters['title'].'%');
                }
            })
            ->paginate($filters['pagination']['per_page'], null, null, $filters['pagination']['page']);

//        // TODO: get delayed directly from query, do not manipulate the data
//        $transaction->getCollection()->transform(function ($book) {
//            $book->append('delayed');
//            return $book;
//        });
//
//        return $transaction;
    }

    public function SearchPopularBooks(array $filters): LengthAwarePaginator
    {
//        $books = Book::select('id', 'title', 'category_id', 'image')
//            ->withCount('Transaction')
//            ->with([
//                'Category' => fn($query) => $query->select('id', 'name', 'number'),
//                'Authors' => fn($query) => $query->select('authors.id', 'name'),
//            ])
//            ->orderByDesc('transaction_count')
//            ->take($filters['pagination']['per_page'])
//            ->get();
//
//        if (isset($filters['title']) && $filters['title'] != '') {
//            $books = $books->filter(function ($book) use ($filters) {
//                return stripos($book->title, $filters['title']) !== false;
//            });
//        }
//
//        return $books;

        $books = DB::table('books AS b')
            ->select('b.id', 'b.title', 'b.category_id', 'b.image', 'is_marked', 'c.name AS category', 'c.number AS number',)
            ->selectRaw('COUNT(t.id) AS count')
            ->join('categories AS c', 'b.category_id', '=', 'c.id')
            ->join('transactions AS t', 'b.id', '=', 't.book_id')
            ->groupBy('b.id', 'b.title', 'b.category_id', 'b.image', 'is_marked', 'c.name', 'c.number')
            ->orderByDesc('count')
            ->having('count', '>', 13);

        if(isset($filters['title']) && $filters['title'] != '') {
            $books->where('title', 'like', '%'.$filters['title'].'%');
        }

        return $books->paginate($filters['pagination']['per_page'], null, null, $filters['pagination']['page']);
    }

    public function GetAuthorsById(array $bookIds): Collection
    {
        return DB::table('authors as a')
            ->select('ba.book_id', 'a.name', 'a.id')
            ->join('book_authors as ba', 'ba.author_id', '=', 'a.id')
            ->whereIn('ba.book_id', $bookIds)
            ->groupBy('ba.book_id', 'a.id', 'a.name')
            ->get();
    }

    public function SearchRecommendedBooks(array $filters): ?LengthAwarePaginator
    {
        $query = Book::select('id', 'title', 'category_id', 'image')
            ->with([
                'Category' => fn($query) => $query->select('id', 'name', 'number'),
                'Authors' => fn($query) => $query->select('name'),
            ])
            ->where('is_recommended', true)->orderBy('order', 'asc');

        if(isset($filters['title']) && $filters['title'] != '') {
            $query->where('title', 'like', '%'.$filters['title'].'%');
        }

        return $query->paginate($filters['pagination']['per_page'], null, null, $filters['pagination']['page']);
    }

    public function CheckIfBookIsAvailable(int $bookId): bool
    {
        $book = Book::select('id', 'quantity')->withCount([
            'Transaction' => fn($query) => $query->where('is_returned', 0)
        ])->find($bookId);
        return $book->status > 0;
    }

    public function CheckIfUserCanBorrowBook(int $userId): bool
    {
        $user = User::select('id')->withCount([
            'Transaction' => fn($query) => $query->where('is_returned', 0)
        ])->find($userId);
        return $user->transaction_count < 2;
    }

    public function BorrowBook(array $fields): Transactions
    {
        return Transactions::create($fields);
    }

    public function GetTransactionById(int $transactionId): ?Transactions
    {
        return Transactions::find($transactionId);
    }

    public function ReturnBook(int $transactionId): Transactions
    {
        $transaction = Transactions::find($transactionId);
        $transaction->is_returned = true;
        $transaction->receiver_name = Auth::user()->full_Name;
        $transaction->update();
        return $transaction;
    }
    public function ExtendBook(int $transactionId): bool
    {
        $transaction = Transactions::find($transactionId);
        $twoWeeksLater = Carbon::now('Europe/Bucharest')->addWeeks(2);
        $transaction->return_date = $twoWeeksLater;
        $transaction->update();
        return true;
    }

}
