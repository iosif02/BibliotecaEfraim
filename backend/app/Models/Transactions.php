<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transactions extends Model
{
    use HasFactory;


    protected $casts = [
        'is_returned' => 'boolean',
    ];
    protected $fillable = ['borrow_date', 'is_returned', 'return_date', 'book_id', 'user_id', 'lender_name', 'receiver_name'];

    public function Book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function UserDetails(): BelongsTo
    {
        return $this->belongsTo(UserDetails::class, 'user_id', 'user_id');
    }

    protected $appends = ['delayed'];

    protected function delayed(): Attribute
    {
        return Attribute::make(
            get: function() {
                $now = Carbon::now('Europe/Bucharest');
                return $now->diffInDays($this->attributes['return_date'], false);
            },
        );
    }

    protected function lenderName(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ?? ucwords($value),
            set: fn (string $value) => strtolower($value),
        );
    }

    protected function receiverName(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ?? ucwords($value),
            set: fn (string $value) => strtolower($value),
        );
    }

}
