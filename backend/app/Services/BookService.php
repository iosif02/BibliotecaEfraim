<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Interfaces\IBookService;
use App\Interfaces\IFileService;
use App\Models\Transactions;
use App\Repositories\IBookRepository;
use App\Repositories\IEntityRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BookService implements IBookService
{
    private IBookRepository $bookRepository;
    private IEntityRepository $entityRepository;
    private IFileService $fileService;

    public function __construct(IBookRepository $bookRepository, IFileService $fileService, IEntityRepository $entityRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->fileService = $fileService;
        $this->entityRepository = $entityRepository;
    }
    public function GetHomepage(): array
    {
        $filters = ['pagination' => [ 'per_page' => 3, 'page' => 1]];
        $delayedBooks = $this->bookRepository->SearchDelayedBooks($filters);
        $popularBooks = $this->SearchPopularBooks($filters);
        $categories = $this->entityRepository->SearchCategories($filters);

        return [
            "delayedBooks" => $delayedBooks,
            "popularBooks" => $popularBooks,
            "categories" => $categories
        ];
    }

    public function AddBook(array $fields): bool
    {
        if(isset($fields['imageFile']) && $fields['imageFile'] != ''){
            $imageName = $this->fileService->StoreFile($fields['imageFile'], 'book');
            $fields['image'] = $imageName;
        }

        $booleanValue = filter_var($fields['is_marked'], FILTER_VALIDATE_BOOLEAN);
        $fields['is_marked'] = $booleanValue;

        return $this->bookRepository->AddBook($fields);
    }

    /**
     * @throws CustomException
     */
    public function UpdateBook(array $fields): bool
    {
        $book = $this->bookRepository->GetBookById($fields['bookId']);
        if(!$book)
            throw new CustomException('Book not found!');

        if(isset($fields['imageFile']) && $fields['imageFile'] != '') {
            $imageName = $this->fileService->StoreFile($fields['imageFile'], 'book');
            $fields['image'] = $imageName;

            $this->fileService->DeleteFile($book['image']);
        }

        $booleanValue = filter_var($fields['is_marked'], FILTER_VALIDATE_BOOLEAN);
        $fields['is_marked'] = $booleanValue;

        return $this->bookRepository->UpdateBook($fields);
    }

    /**
     * @throws CustomException
     */
    public function DeleteBook(int $bookId): bool
    {
        $book = $this->bookRepository->GetBookById($bookId);
        if(!$book)
            throw new CustomException('Book not found!');

        return $this->bookRepository->DeleteBook($bookId);
    }

    /**
     * @throws CustomException
     */
    public function GetBookDetailsById(int $bookId): Model
    {
        $book = $this->bookRepository->GetBookDetailsById($bookId);
        if(!$book)
            throw new CustomException('Book not found!');

        return $book;
    }

    public function SearchBooks(array $filters): ?LengthAwarePaginator
    {
        return $this->bookRepository->SearchBooks($filters);
    }

    public function SearchGlobalBooks(array $filters): ?LengthAwarePaginator
    {
        return $this->bookRepository->SearchGlobalBooks($filters);
    }

    public function SearchDelayedBooks(array $filters): ?LengthAwarePaginator
    {
        return $this->bookRepository->SearchDelayedBooks($filters);
    }

    public function SearchPopularBooks(array $filters): ?LengthAwarePaginator
    {
        $books = $this->bookRepository->SearchPopularBooks($filters);
        $bookIds = array_column($books->items(), 'id');
        $bookAuthors = $this->bookRepository->GetAuthorsById($bookIds);

        foreach ($books as $book) {
            $book->authors = [];
            foreach ($bookAuthors as $author) {
                if ($book->id == $author->book_id) {
                    $book->authors[] = $author;
                }
            }
        }

        return $books;
    }

    public function SearchRecommendedBooks(array $filters): ?LengthAwarePaginator
    {
        return $this->bookRepository->SearchRecommendedBooks($filters);
    }

    /**
     * @throws CustomException
     */
    public function BorrowBook(array $fields): Transactions
    {
        $book = $this->bookRepository->CheckIfBookIsAvailable($fields['book_id']);
        if(!$book)
            throw new CustomException('Book is unavailable!');
//        $user = $this->bookRepository->CheckIfUserCanBorrowBook($fields['user_id']);
//        if(!$user)
//            throw new CustomException('The user have 2 borrow book already!');

        $fields['borrow_date'] = Carbon::now('Europe/Bucharest');
        $fields['return_date'] = Carbon::now('Europe/Bucharest')->addWeeks(2);
        $fields['is_returned'] = false;
        $fields['lender_name'] = Auth::user()->full_Name;

        return $this->bookRepository->BorrowBook($fields);
    }

    /**
     * @throws CustomException
     */
    public function ReturnBook(int $transactionId): Transactions
    {
        $transaction = $this->bookRepository->GetTransactionById($transactionId);
        if(!$transaction)
            throw new CustomException('Transaction not found!');

        return $this->bookRepository->ReturnBook($transactionId);
    }

    /**
     * @throws CustomException
     */
    public function ExtendBook(int $transactionId): bool
    {
        $transaction = $this->bookRepository->GetTransactionById($transactionId);
        if(!$transaction)
            throw new CustomException('Transaction not found!');

        if(Carbon::parse($transaction->return_date)->isPast() || Carbon::parse($transaction->return_date)->isToday()){
            return $this->bookRepository->ExtendBook($transactionId);
        }

        throw new CustomException("The return date didn't passed!");
    }

    public function GetImage(string $context, string $filename): BinaryFileResponse
    {
        return $this->fileService->GetFile($context, $filename);
    }
}
