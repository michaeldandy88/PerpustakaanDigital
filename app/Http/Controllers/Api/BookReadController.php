<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class BookReadController extends Controller
{
    public function read(Request $request, Book $book)
    {
        // cek apakah user sedang meminjam buku ini
        $hasBorrowed = Borrowing::where('user_id', $request->user()->id)
            ->where('book_id', $book->id)
            ->where('status', 'dipinjam')
            ->exists();

        if (!$hasBorrowed) {
            abort(403, 'Anda tidak berhak membaca buku ini');
        }

        return response()->json([
            'id' => $book->id,
            'title' => $book->title,
            'author' => $book->author,
            'pdf_path' => $book->pdf_path,
        ]);
    }
}
