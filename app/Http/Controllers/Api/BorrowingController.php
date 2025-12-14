<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    /**
     * ADMIN: lihat semua peminjaman
     */
    public function index()
    {
        return Borrowing::with(['user', 'book'])
            ->latest()
            ->get();
    }

    /**
     * MAHASISWA: pinjam buku
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $book = Book::findOrFail($request->book_id);

        if ($book->stock < 1) {
            return response()->json([
                'message' => 'Stok buku habis',
            ], 422);
        }

        Borrowing::create([
            'user_id' => $request->user()->id,
            'book_id' => $book->id,
            'borrow_date' => now(),
            'status' => 'dipinjam',
        ]);

        $book->decrement('stock');

        return response()->json([
            'message' => 'Buku berhasil dipinjam',
        ]);
    }

    /**
     * MAHASISWA: lihat peminjaman sendiri
     */
    public function myBorrowings(Request $request)
    {
        return Borrowing::where('user_id', $request->user()->id)
            ->with('book')
            ->orderByDesc('borrow_date')
            ->get();
    }

    /**
     * MAHASISWA: kembalikan buku
     */
    public function return(Request $request, Borrowing $borrowing)
    {
        // pastikan pemilik
        if ($borrowing->user_id !== $request->user()->id) {
            abort(403, 'Akses ditolak');
        }

        if ($borrowing->status === 'dikembalikan') {
            return response()->json([
                'message' => 'Buku sudah dikembalikan',
            ], 422);
        }

        $borrowing->update([
            'status' => 'dikembalikan',
            'return_date' => now(),
        ]);

        $borrowing->book->increment('stock');

        return response()->json([
            'message' => 'Buku berhasil dikembalikan',
        ]);
    }
}
