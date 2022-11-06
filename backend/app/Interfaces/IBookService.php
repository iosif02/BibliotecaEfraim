<?php

namespace App\Interfaces;

interface IBookService
{
    public function AddBook($fields);
    public function GetBookById($bookId);
    public function SearchBooks($filters);
}
