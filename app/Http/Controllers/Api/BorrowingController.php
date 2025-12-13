<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Book;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    // ADMIN: lihat semua peminjaman
    public function index()
    {
        return Borrowing::with(['user', 'book'])->get();
    }

    // MAHASISWA: pinjam buku
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $book = Book::findOrFail($validated['book_id']);

        if ($book->stock < 1) {
            return response()->json(['message' => 'Stock habis'], 400);
        }

        $book->decrement('stock');

        return Borrowing::create([
            'user_id' => $request->user()->id,
            'book_id' => $book->id,
            'borrow_date' => now(),
            'status' => 'dipinjam',
        ]);
    }

    // MAHASISWA: lihat peminjaman sendiri
    public function show(Request $request)
    {
        return Borrowing::where('user_id', $request->user()->id)
            ->with('book')
            ->get();
    }

    // ADMIN: pengembalian
    public function update(Borrowing $borrowing)
    {
        $borrowing->update([
            'status' => 'dikembalikan',
            'return_date' => now(),
        ]);

        $borrowing->book->increment('stock');

        return $borrowing;
    }
}