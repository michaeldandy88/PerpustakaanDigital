<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function publicIndex(Request $request)
    {
        $userId = $request->user()->id;

        return Book::withCount([
            'borrowings as is_borrowed' => function ($q) use ($userId) {
                $q->where('user_id', $userId)
                ->where('status', 'dipinjam');
            }
        ])->get();
    }

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
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:1',
            'pdf' => 'required|file|mimes:pdf|max:10240',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $pdfPath = $request->file('pdf')->store('books/pdf', 'public');

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('books/cover', 'public');
        }

        return Book::create([
            'title' => $validated['title'],
            'author' => $validated['author'],
            'publisher' => $validated['publisher'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'stock' => $validated['stock'],
            'pdf_path' => $pdfPath,      
            'cover_path' => $coverPath,  
        ]);
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
            'publisher' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:1',
            'pdf' => 'nullable|file|mimes:pdf|max:10240',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('pdf')) {
            Storage::disk('public')->delete($book->pdf_path);
            $book->pdf_path = $request->file('pdf')->store('books/pdf', 'public');
        }

        if ($request->hasFile('cover')) {
            Storage::disk('public')->delete($book->cover_path);
            $book->cover_path = $request->file('cover')->store('books/cover', 'public');
        }

        $book->update([
            'title' => $validated['title'],
            'author' => $validated['author'],
            'category_id' => $validated['category_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'stock' => $validated['stock'],
        ]);

        return $book;
    }

    public function destroy(Book $book)
    {
        Storage::disk('public')->delete([
            $book->pdf_path,
            $book->cover_path,
        ]);

        $book->delete();

        return response()->json(['message' => 'Buku dihapus']);
    }
}