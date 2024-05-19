<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddBookRequest;
use App\Http\Requests\BookGlobalSearchRequest;
use App\Http\Requests\BookSearchRequest;
use App\Http\Requests\BorrowBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Interfaces\IBookService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BookController extends Controller
{
    private IBookService $bookService;

    public function __construct(IBookService $bookService) {
        $this->bookService = $bookService;
    }

    public function GetHomepage(): JsonResponse
    {
        $result = $this->bookService->GetHomepage();
        return response()->json($result);
    }

    public function SearchDelayedBooks(BookSearchRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $result = $this->bookService->SearchDelayedBooks($validated);

        return response()->json($result);
    }

    public function SearchRecommendedBooks(BookSearchRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $result = $this->bookService->SearchRecommendedBooks($validated);

        return response()->json($result);
    }

    public function SearchPopularBooks(BookSearchRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $result = $this->bookService->SearchPopularBooks($validated);

        return response()->json($result);
    }

    public function AddBook(AddBookRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $result = $this->bookService->AddBook($validated);

        return response()->json($result);
    }

    public function UpdateBook(UpdateBookRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $result = $this->bookService->UpdateBook($validated);

        return response()->json($result);
    }

    public function DeleteBook(int $bookId): JsonResponse
    {
        $result = $this->bookService->DeleteBook($bookId);
        return response()->json($result);
    }

    public function GetBookDetails(int $bookId): JsonResponse
    {
        $result = $this->bookService->GetBookDetailsById($bookId);
        return response()->json($result);
    }

    public function SearchBooks(BookSearchRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $result = $this->bookService->SearchBooks($validated);

        return response()->json($result);
    }

    public function SearchGlobalBooks(BookGlobalSearchRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $result = $this->bookService->SearchGlobalBooks($validated);

        return response()->json($result);
    }

    public function BorrowBook(BorrowBookRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $result = $this->bookService->BorrowBook($validated);

        return response()->json($result);
    }

    public function ReturnBook(int $transactionId): JsonResponse
    {
        $result = $this->bookService->ReturnBook($transactionId);
        return response()->json($result);
    }

    public function ExtendBook(int $transactionId): JsonResponse
    {
        $result = $this->bookService->ExtendBook($transactionId);
        return response()->json($result);
    }

    public function GetImage($context, $filename): BinaryFileResponse
    {
        return $this->bookService->GetImage($context, $filename);
    }
}
