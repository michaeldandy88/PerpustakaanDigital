<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Copy;

class CopyController extends Controller
{
    public function index(Request $request)
    {
        $q = Copy::with('book:id,title');
        if ($request->filled('book_id')) $q->where('book_id', $request->book_id);
        return $q->latest()->paginate(10);
    }

    public function show(Copy $copy)
    {
        return $copy->load('book_id, title');
    }

    public function store(Request $request)
    {
        
    }
}
