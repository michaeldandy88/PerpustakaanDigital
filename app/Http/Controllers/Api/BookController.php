<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Book;

class BookController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        return response()->json(Book::orderBy('title')->get());
    }

    public function show(Book $book)
    {
        return response()->json($book);
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'title' => 'required|string',
            'slug' => 'nullable|unique:books,slug',
            'isbn' => 'nullable|string',
            'year' => 'nullable|digits:4',
            'description' => 'nullable|string'
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['title']) . '-' . Str::random(4);

        $book = Book::create($data);
        return response()->json($book, 201);
    }

    public function update(Request $req, Book $book)
    {
        $data = $req->validate([
            'title' => 'required|string',
            'isbn' => 'nullable|string',
            'year' => 'nullable|digits:4',
            'description' => 'nullable|string'
        ]);

        $book->update($data);
        return response()->json($book);
    }

    public function destroy(Request $req, Book $book)
    {
        $book->delete();
        return response()->json(null, 204);
    }
}
