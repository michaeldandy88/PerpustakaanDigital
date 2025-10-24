<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Assignment;

class AssignmentController extends Controller
{
    public function index()
    {
        return response()->json(Assignment::orderBy('due_at', 'asc')->get());
    }

    public function store(Request $req)
    {
        if (!$req->user()->isDosen() && !$req->user()->isPustakawan()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $req->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'available_from' => 'nullable|date',
            'due_at' => 'nullable|date|after_or_equal:available_from',
            'submission_type' => 'nullable|in:file,text,both'
        ]);

        // gunakan alias Str langsung
        $data['slug'] = Str::slug($data['title']).'-'.Str::random(5);
        $data['created_by'] = $req->user()->id;

        $assignment = Assignment::create($data);
        return response()->json($assignment, 201);
    }

    public function show(Assignment $assignment)
    {
        return response()->json($assignment);
    }

    public function update(Request $req, Assignment $assignment)
    {
        // similar to store with authorization
    }

    public function destroy(Assignment $assignment)
    {
        // only dosen/pustakawan
    }
}
