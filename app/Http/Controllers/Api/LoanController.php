<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Copy;
use App\Models\Loan;

class LoanController extends Controller
{
    public function borrow(Request $req, $copyId) {
        $user = $req->user();
        $copy = Copy::findOrFail($copyId);
        if ($copy->status !== 'available') {
            return response()->json(['message'=>'Copy tidak tersedia'], 422);
        }
        $due = now()->addDays(7);
        $loan = Loan::create([
            'user_id' => $user->id,
            'copy_id' => $copy->id,
            'borrowed_at' => now(),
            'due_at' => $due,
            'status' => 'borrowed'
        ]);
        $copy->update(['status'=>'borrowed']);
        return response()->json($loan, 201);
    }

    public function return(Request $req, $loanId) {
        $loan = Loan::findOrFail($loanId);

        if ($req->user()->id !== $loan->user_id && !$req->user()->isPustakawan()) {
            return response()->json(['message'=>'Forbidden'], 403);
        }
        $loan->update(['returned_at'=>now(),'status'=>'returned']);
        $loan->copy->update(['status'=>'available']);
        return response()->json($loan);
    }
}