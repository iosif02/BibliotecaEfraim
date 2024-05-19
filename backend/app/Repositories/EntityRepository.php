<?php

namespace App\Repositories;

use App\Models\Author;
use App\Models\Category;
use App\Models\Publisher;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class EntityRepository implements IEntityRepository
{
    public function GetPublishers(): Collection
    {
        return Publisher::select('id', 'name')->get();
    }

    public function GetAuthors(): Collection
    {
        return Author::select('id', 'name')->get();
    }

    public function GetCategories(): Collection
    {
        return Category::select('id', 'name')->get();
    }

    public function GetAuthorById(int $authorId): ?Author
    {
        return Author::select('id', 'name')->find($authorId);
    }

    public function SearchAuthors(array $filters): ?LengthAwarePaginator
    {
        $query = Author::select('id', 'name')->withCount('Book');

        if(isset($filters['name']) && $filters['name'] != '') {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }

        return $query->paginate($filters['pagination']['per_page'], null, null, $filters['pagination']['page']);
    }

    public function AddAuthor(array $fields): Author
    {
        return Author::create($fields);
    }

    public function UpdateAuthor(array $fields): Author
    {
        $author = Author::find($fields['authorId']);
        $author->fill($fields);
        $author->update();
        return $author;
    }

    public function DeleteAuthor(int $authorId): int
    {
        return Author::destroy($authorId);
    }

    public function GetPublisherById(int $publisherId): ?Publisher
    {
        return Publisher::select('id', 'name', 'city')->find($publisherId);
    }

    public function SearchPublisher(array $filters): ?LengthAwarePaginator
    {
        $query = Publisher::select('id', 'name')->withCount('Book');

        if(isset($filters['name']) && $filters['name'] != '') {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }

        return $query->paginate($filters['pagination']['per_page'], null, null, $filters['pagination']['page']);
    }

    public function AddPublisher(array $fields): Publisher
    {
        return Publisher::create($fields);
    }

    public function UpdatePublisher(array $fields): Publisher
    {
        $publisher = Publisher::find($fields['publisherId']);
        $publisher->fill($fields);
        $publisher->update();
        return $publisher;
    }

    public function DeletePublisher(int $publisherId): int
    {
        return Publisher::destroy($publisherId);
    }

    public function GetCategoryById(int $categoryId): ?Category
    {
        return Category::select('id', 'name', 'description', 'number')->find($categoryId);
    }

    public function SearchCategories(array $filters): ?LengthAwarePaginator
    {
        $query = Category::select('id', 'name', 'number')->withCount('Book');

        if(isset($filters['name']) && $filters['name'] != '') {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }

        $query->orderBy('number');

        return $query->paginate($filters['pagination']['per_page'], null, null, $filters['pagination']['page']);
    }

    public function AddCategory(array $fields): Category
    {
        return Category::create($fields);
    }

    public function UpdateCategory(array $fields): Category
    {
        $category = Category::find($fields['categoryId']);
        $category->fill($fields);
        $category->update();
        return $category;
    }

    public function DeleteCategory(int $categoryId): int
    {
        return Category::destroy($categoryId);
    }
}
