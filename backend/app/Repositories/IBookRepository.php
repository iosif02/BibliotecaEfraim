<?php

namespace App\Repositories;

use App\Models\Book;
use App\Models\Transactions;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface IBookRepository
{
    public function AddBook(array $fields): bool;
    public function UpdateBook(array $fields): bool;
    public function DeleteBook(int $bookId): bool;
    public function GetBookById(int $bookId): ?Book;
    public function GetBookDetailsById(int $bookId): ?Model;
    public function SearchBooks(array $filters): ?LengthAwarePaginator;
    public function SearchGlobalBooks(array $filters): ?LengthAwarePaginator;
    public function SearchDelayedBooks(array $filters): ?LengthAwarePaginator;
    public function SearchPopularBooks(array $filters): ?LengthAwarePaginator;
    public function GetAuthorsById(array $bookIds): Collection;
    public function SearchRecommendedBooks(array $filters): ?LengthAwarePaginator;
    public function CheckIfBookIsAvailable(int $bookId): bool;
    public function CheckIfUserCanBorrowBook(int $userId): bool;
    public function BorrowBook(array $fields): Transactions;
    public function GetTransactionById(int $transactionId): ?Transactions;
    public function ReturnBook(int $transactionId): Transactions;
    public function ExtendBook(int $transactionId): bool;
}
