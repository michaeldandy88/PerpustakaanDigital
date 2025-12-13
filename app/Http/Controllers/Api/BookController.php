<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return Book::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'publisher' => 'nullable|string',
            'year' => 'nullable|integer',
            'stock' => 'required|integer|min:0',
        ]);

        return Book::create($validated);
    }

    public function show(Book $book)
    {
        return $book;
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'publisher' => 'nullable|string',
            'year' => 'nullable|integer',
            'stock' => 'required|integer|min:0',
        ]);

        $book->update($validated);

        return $book;
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json([
            'message' => 'Book deleted successfully'
        ]);
    }
}