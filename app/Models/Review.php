<?php

namespace App\Models;

use App\Models\Book;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'reviews', 
        'rating',
        'book_id'
    ];

    public function book() 
    {
        return $this->belongsTo(Book::class);
    }

    
    public static function booted() 
    {
        static::updated(fn(Review $review) => cache()->forget('book:' . $review->book_id));
        static::deleted(fn(Review $review) => cache()->forget('book:' . $review->book_id));
        static::created(fn(Review $review) => cache()->forget('book:' . $review->book_id));
    }

    
}
