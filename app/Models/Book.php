<?php

namespace App\Models;

use App\Models\Review;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Book extends Model
{
    use HasFactory;

    public function reviews() 
    {
        return $this->hasMany(Review::class);
    }

    public function scopeTitle(Builder $builder, $title) 
    {
        return $builder->where('title', 'LIKE', "%$title%");
    }

    public function good_review() 
    {
        return $this->reviews()->one()->ofMany('rating', 'max');
    }

    public function scopeWithReviewsCount(Builder $builder, $from = null, $to = null) : Builder 
    {
        return $builder->withCount([
            'reviews' => function($q) use ($from, $to) { $this->dateFilterRange($q, $from, $to); }
        ]);
    }

    public function scopeWithAvgRating(Builder $builder, $from = null, $to = null) : Builder 
    {
        return $builder->withAvg([
            'reviews' => function($q) use ($from, $to) { $this->dateFilterRange($q, $from, $to); }
        ], 'rating');
    }

    public function scopePopular(Builder $builder, $from = null, $to = null) : Builder 
    {
        return $builder->withReviewsCount()
                ->latest('reviews_count');
    }
    

    public function scopeHighestRated(Builder $builder, $from = null, $to = null) : Builder
    {
        return $builder->withAvgRating()
            ->latest('reviews_avg_rating');
    }

    public function scopeMinReviews(Builder $builder, int $min_reviews) : Builder 
    {
        return $builder->having('reviews_count', '>=', $min_reviews);
    }

    private function dateFilterRange(Builder $query, $from, $to) 
    {
        if($from && !$to) {
            $query->where('created_at', '>=', $from);
        }
        else if (!$from && $to) {
            $query->where('created_at', '<=', $to);
        }
        else if ($from && $to) {
            $query->whereBetween('created_at', [$from , $to]);
        }
    }

    public function scopePopularLastMonth(Builder $builder) : Builder | QueryBuilder 
    {
        return $builder->popular(now()->subMonth(), now())
                ->highestRated(now()->subMonth(), now())
                ->minReviews(2);
    }

    public function scopePopularLast6Months(Builder $builder) : Builder | QueryBuilder 
    {
        return $builder->popular(now()->subMonths(6), now())
                ->highestRated(now()->subMonths(6), now())
                ->minReviews(5);
    }

    public function scopeHighestRatedLastMonth(Builder $builder) : Builder | QueryBuilder 
    {
        
        return $builder->highestRated(now()->subMonth(), now())
                ->popular(now()->subMonth(), now())
                ->minReviews(2);
    }

    public function scopeHighestRatedLast6Months(Builder $builder) : Builder | QueryBuilder 
    {
        
        return $builder->highestRated(now()->subMonths(6), now())
                ->popular(now()->subMonths(6), now())
                ->minReviews(5);
    }

    public static function booted() 
    {   
        static::updated(fn(Book $book) => cache()->forget('book:' . $book->id));
        static::deleted(fn(Book $book) => cache()->forget('book:' . $book->id));
    }


}
