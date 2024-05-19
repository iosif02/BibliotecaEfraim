<?php

namespace App\Interfaces;

use App\Models\Transactions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

interface IBookService
{
    public function GetHomepage(): array;
    public function AddBook(array $fields): bool;
    public function UpdateBook(array $fields): bool;
    public function DeleteBook(int $bookId): bool;
    public function GetBookDetailsById(int $bookId): Model;
    public function SearchBooks(array $filters): ?LengthAwarePaginator;
    public function SearchGlobalBooks(array $filters): ?LengthAwarePaginator;
    public function SearchDelayedBooks(array $filters): ?LengthAwarePaginator;
    public function SearchPopularBooks(array $filters): ?LengthAwarePaginator;
    public function SearchRecommendedBooks(array $filters): ?LengthAwarePaginator;
    public function BorrowBook(array $fields): Transactions;
    public function ReturnBook(int $transactionId): Transactions;
    public function ExtendBook(int $transactionId): bool;
    public function GetImage(string $context, string $filename): BinaryFileResponse;
}
