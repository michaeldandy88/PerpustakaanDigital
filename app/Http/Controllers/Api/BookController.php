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
        $books = Book::orderBy('title')->get()->map(function($b){
            // tambahkan properti yang dipakai frontend
            $b->cover_url = $b->cover_path ? Storage::url($b->cover_path) : null;
            $b->pdf_url = $b->pdf_path ? Storage::url($b->pdf_path) : null;
            return $b; // <-- PENTING: kembalikan object
        });

        return response()->json($books);
    }

    public function show(Book $book)
    {
        $book->cover_url = $book->cover_path ? Storage::url($book->cover_path) : null;
        $book->pdf_url = $book->pdf_path ? Storage::url($book->pdf_path) : null;

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
            $data['cover_path'] = $req->file('cover')->store('books/covers', 'public');
        }

        if ($req->hasFile('pdf')) {
            $data['pdf_path'] = $req->file('pdf')->store('books/pdfs', 'public');
        }

        $book = Book::create($data);

        $book->cover_url = $book->cover_path ? Storage::url($book->cover_path) : null;
        $book->pdf_url = $book->pdf_path ? Storage::url($book->pdf_path) : null;

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

        // Cover update: hapus cover lama jika ada, lalu simpan path baru
        if ($req->hasFile('cover')) {
            if ($book->cover_path && Storage::disk('public')->exists($book->cover_path)) {
                Storage::disk('public')->delete($book->cover_path);
            }
            $data['cover_path'] = $req->file('cover')->store('books/covers', 'public');
        }

        // PDF update: hapus file pdf lama jika ada, lalu simpan path baru
        if ($req->hasFile('pdf')) {
            if ($book->pdf_path && Storage::disk('public')->exists($book->pdf_path)) {
                Storage::disk('public')->delete($book->pdf_path);
            }
            $data['pdf_path'] = $req->file('pdf')->store('books/pdfs', 'public');
        }

        $book->update($data);

        $book->cover_url = $book->cover_path ? Storage::url($book->cover_path) : null;
        $book->pdf_url = $book->pdf_path ? Storage::url($book->pdf_path) : null;

        return response()->json($book);
    }

    public function destroy(Request $req, Book $book)
    {
        if ($book->cover_path && Storage::disk('public')->exists($book->cover_path)) {
            Storage::disk('public')->delete($book->cover_path);
        }
        if ($book->pdf_path && Storage::disk('public')->exists($book->pdf_path)) {
            Storage::disk('public')->delete($book->pdf_path);
        }

        $book->delete();
        return response()->json(null, 204);
    }
}
