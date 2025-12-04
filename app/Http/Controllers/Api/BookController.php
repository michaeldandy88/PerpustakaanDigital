<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;

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
            'description' => 'nullable|string',
            'cover' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'pdf' => 'nullable|mimes:pdf|max:2048',
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['title']) . '-' . Str::random(4);

        if ($req->hasFile('cover')) {
            $data['cover_path'] = $req -> file('cover')->store('books/covers', 'public');
        }

        if ($req->hasFile('pdf')) {
            $data['pdf_path'] = $req ->file('pdf')->store('books/pdfs', 'public');
        }

        $book = Book::create($data);
        return response()->json($book, 201);
    }

    public function update(Request $req, Book $book)
    {
        $data = $req->validate([
            'title' => 'required|string',
            'isbn' => 'nullable|string',
            'year' => 'nullable|digits:4',
            'description' => 'nullable|string',
            'cover' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'pdf' => 'nullable|mimes:pdf|max:2048',
        ]);

        if ($req->hasFile('cover')) {
            if ($book->cover_path && Storage::disk('public')->exists($book->cover_path)){
                Storage::disk('public')->delete($book->cover_path);
            }
            $data['cover_path'] = $req->file('cover')->store('books/covers', 'public');
        }

        if ($req->hasFile('pdf')) {
            if ($book->cover_path && Storage::disk('public')->exists($book->pdf)){
                Storage::disk('public')->delete($book->pdf);
            }
            $data['cover_path'] = $req->file('pdf')->store('books/pdfs', 'public');
        }

        $book->update($data);
        return response()->json($book);
    }

    public function destroy(Request $req, Book $book)
    {
        $book->delete();
        return response()->json(null, 204);
    }
}
