<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        Book::factory(33)->create()->each(function($book) {

            $review_count = random_int(3, 30);

            Review::factory()->count($review_count)
            ->good()
            ->for($book)
            ->create();

            Review::factory()->count($review_count)
            ->average()
            ->for($book)
            ->create();
            
            Review::factory()->count($review_count)
            ->poor()
            ->for($book)
            ->create();


        });

    }
}
