<?php

use App\Http\Controllers\BooksController;
use App\Http\Controllers\ReviewsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->to('/books');
});


Route::resource('books', BooksController::class)->only(['index', 'show']);

Route::resource('books.reviews', ReviewsController::class)
->scoped(['review' => 'book'])
->only(['create', 'store']);