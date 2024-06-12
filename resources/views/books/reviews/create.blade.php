@extends('layouts.app')

@section('content')
  <h1 class="mb-10 text-2xl">Add Review for {{ $book->title }}</h1>

  <form method="POST" action="{{ route('books.reviews.store', $book) }}">
    @csrf

    <div class="mb-4">

        <label for="review">Review</label>
        <textarea 
            name="reviews" 
            id="review" 
            class="input @error('review') is-invalid @enderror"
        ></textarea>
    
        @error('review')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

    </div>

    <div class="mb-4">

        <label for="rating">Rating</label>
    
        <select 
            name="rating" 
            id="rating" 
            class="input @error('rating') is-invalid @enderror" 
        >
          <option value="">Select a Rating</option>
          @for ($i = 1; $i <= 5; $i++)
            <option value="{{ $i }}">{{ $i }}</option>
          @endfor
        </select>
    
        @error('rating')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror


    </div>


    <button type="submit" class="btn">Add Review</button>
  </form>
@endsection