<?php

namespace App\Services;

use App\Interfaces\IBookService;
use App\Interfaces\IFileService;
use App\Repositories\IBookRepository;
use App\Repositories\IEntityRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\ArrayShape;

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

    #[ArrayShape(["delayedBooks" => "", "popularBooks" => "", "categories" => ""])]
    public function GetHomepage(): array
    {
        $filters = ['pagination' => [ 'per_page' => 3, 'page' => 1]];
        $delayedBooks = $this->bookRepository->SearchDelayedBooks($filters);
        $popularBooks = $this->bookRepository->SearchPopularBooks($filters);
        $categories = $this->entityRepository->SearchCategories($filters);

        return [
            "delayedBooks" => $delayedBooks,
            "popularBooks" => $popularBooks,
            "categories" => $categories
        ];
    }

    public function AddBook($fields): bool
    {
        try {
            $imageName = $this->fileService->StoreFile($fields['image']);
            $fields['image'] = $imageName;
            $result = $this->bookRepository->AddBook($fields);
        } catch (Exception $exception) {
            Log::error('Add book error: ' . $exception->getMessage());
            return false;
        }

        return $result;
    }

    public function UpdateBook($fields): bool
    {
        try {
            if(!$fields['bookId'])
                throw new Exception('bookId is required!');

            if(isset($fields['image']) && $fields['image'] != ''){
                $imageName = $this->fileService->StoreFile($fields['image']);
                $fields['image'] = $imageName;
            }

            $result = $this->bookRepository->UpdateBook($fields);
        } catch (Exception $exception) {
            Log::error('Update book error: ' . $exception->getMessage());
            return false;
        }

        return $result;
    }

    public function DeleteBook($bookId): bool
    {
        try {
            if(!$bookId)
                throw new Exception('bookId is required!');

            $result = $this->bookRepository->DeleteBook($bookId);
        } catch (Exception $exception) {
            Log::error('Delete book error: ' . $exception->getMessage());
            return false;
        }

        return $result;
    }

    public function GetBookById($bookId) {
        if(!$bookId) return null;

        return $this->bookRepository->GetBookById($bookId);
    }

    public function SearchBooks($filters)
    {
        try {
            $result = $this->bookRepository->SearchBooks($filters);
        } catch (Exception $exception) {
            Log::error('Search book error: ' . $exception->getMessage());
            return null;
        }

        return $result;
    }

    public function SearchDelayedBooks($filters)
    {
        try {
            $result = $this->bookRepository->SearchDelayedBooks($filters);
        } catch (Exception $exception) {
            Log::error('Search delayed book error: ' . $exception->getMessage());
            return null;
        }

//        $result = collect($result);
//        $result['data'] = collect($result['data'])->map(function ($entry) {
//            return [
//                'transaction_id' => $entry['id'],
//                'book_title' => $entry['book']['title'],
//                'category' => $entry['book']['category'],
//                'image' => $entry['book']['image'],
//                'user_name' => $entry['user']['name']
//            ];
//        });

        return $result;
    }

    public function SearchPopularBooks($filters)
    {
        try {
            $result = $this->bookRepository->SearchPopularBooks($filters);
        } catch (Exception $exception) {
            Log::error('Search popular book error: ' . $exception->getMessage());
            return null;
        }
        return $result;
    }

    public function SearchRecommendedBooks($filters)
    {
        try {
            $result = $this->bookRepository->SearchRecommendedBooks($filters);
        } catch (Exception $exception) {
            Log::error('Search recommended book error: ' . $exception->getMessage());
            return null;
        }
        return $result;
    }

    public function BorrowBook($fields): ?bool
    {
        try {
            $fields['borrow_date'] = Carbon::today();
            $fields['return_date'] = Carbon::today()->addWeeks(2);
            $fields['is_returned'] = false;

            $result = $this->bookRepository->BorrowBook($fields);
        } catch (Exception $exception) {
            Log::error('Borrow book error: ' . $exception->getMessage());
            return null;
        }
        return (bool)$result;
    }

    public function ReturnBook($transactionId): ?bool
    {
        try {
            $result = $this->bookRepository->ReturnBook($transactionId);
        } catch (Exception $exception) {
            Log::error('Borrow book error: ' . $exception->getMessage());
            return null;
        }
        return (bool)$result;
    }

}
