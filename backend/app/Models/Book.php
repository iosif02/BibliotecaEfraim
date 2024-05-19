<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'author',
        'category_id',
        'year',
        'price',
        'image',
        'quantity',
        'publisher_id',
        'is_recommended',
        'order',
        'is_marked',
    ];

    protected $casts = [
        'is_recommended' => 'boolean',
        'is_marked' => 'boolean'
    ];

    protected $hidden = ['category_id', 'publisher_id'];
    public function Transaction(): HasMany
    {
        return $this->hasMany(Transactions::class);
    }
    public function Category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function Authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'book_authors');
    }

    public function User(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'transactions');
    }

    public function Publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    protected $appends = ['status'];

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->quantity - $this->transaction_count,
        );
    }

//    public function latestTransaction(): HasOne
//    {
//        return $this->HasOne(Transactions::class)->latest();
//    }
}

