<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Copy;
use App\Models\Loan;
use Illuminate\Support\Carbon;

class StatsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $now = Carbon::now();

        $totalBooks = Book::count();
        $totalCopies = Copy::count();
        $availableCopies = Copy::where('status','tersedia')->count();
        $borrowedCopies = Copy::where('status','dipinjam')->count();

        $activeLoans = Loan::whereNull('returned_at')->count();
        $overdueLoans = Loan::whereNull('returned_at')->where('due_at','<',$now)->count();

        $recentBooks = Book::orderByDesc('id')->limit(5)->get(['id','title','year']);

        return response()->json([
            'totalBooks' => $totalBooks,
            'totalCopies' => $totalCopies,
            'availableCopies' => $availableCopies,
            'borrowedCopies' => $borrowedCopies,
            'activeLoans' => $activeLoans,
            'overduaLoans' => $overdueLoans,
            'recentBooks' => $recentBooks,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
