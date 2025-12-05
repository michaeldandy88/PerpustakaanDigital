<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Copy;
use App\Models\Loan;
use Illuminate\Support\Carbon;

class StatsController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // Books & Copies
        $totalBooks = Book::count();
        $totalCopies = Copy::count();
        $availableCopies = Copy::where('status', 'tersedia')->count();
        $borrowedCopies = Copy::where('status', 'dipinjam')->count();

        // Loans
        $activeLoans = Loan::whereNull('returned_at')->count();
        $overdueLoans = Loan::whereNull('returned_at')
            ->where('due_at', '<', $now)
            ->count();

        // Recent books
        $recentBooks = Book::orderByDesc('id')
            ->limit(5)
            ->get(['id', 'title', 'year', 'cover_path']);

        return response()->json([
            'totalBooks'      => $totalBooks,
            'totalCopies'     => $totalCopies,
            'availableCopies' => $availableCopies,
            'borrowedCopies'  => $borrowedCopies,
            'activeLoans'     => $activeLoans,
            'overdueLoans'    => $overdueLoans,   // fixed typo
            'recentBooks'     => $recentBooks,
        ]);
    }
}
