<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Copy;
use Illuminate\Validation\Rule;

class CopyController extends Controller
{
    public function index(Request $request)
    {
        $q = Copy::with('book:id,title');

        if ($request->filled('book_id')){
            $q->where('book_id', $request->integer('book_id'));
        } 

        return $q->latest()->paginate(10);
    }

    public function show(Copy $copy)
    {
        return $copy->load('book_id, title');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'book_id' => ['required','exists:books,id'],
            'barcode' => ['required','string','max:100','unique:copies','barcode'],
            'copy_type' => ['nullable','string','max:50'],
            'status' => ['required',Rule::in(['tersedia','dipinjam'])],
            'location' => ['nullable','string','max:100'],
        ]);

        $copy = Copy::create($data);
        return response()->json($copy->load('book:id','title'),201);
    }

    public function update(Request $request, Copy $copy){
        $data = $request->validate([
            'book_id' => ['sometimes','required','exists:book,id'],
            'barcode' => ['sometimes','required','string','max:100', Rule::unique('copies','barcode')->ignore($copy->id)],
            'copy_type' => ['sometimes','nullable','string','max:50'],
            'status' => ['sometimes','required',Rule::in(['tersedia','dipinjam'])],
            'location' => ['sometimes','nullable','string','max:100']
        ]);

        $copy->update($data);
        return response()->json($copy->load('book:id','title'));
    }

    public function destroy(Copy $copy){
        $copy->delete();
        return response()->noContent();
    }
}
