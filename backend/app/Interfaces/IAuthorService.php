<?php

namespace App\Interfaces;

interface IAuthorService
{
    public function SearchAuthors($filters);
    public function SearchPublisher($filters);
}
