<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Submission;

class SubmissionController extends Controller
{
    public function store(Request $req, Assignment $assignment) {
        $user = $req->user();
        // simple access: mahasiswa only
        if (!$user->isMahasiswa()) return response()->json(['message'=>'Forbidden'],403);

        $req->validate([
            'text_answer'=>'nullable|string',
            'files.*'=>'file|mimes:pdf,doc,docx,jpg,png,zip|max:20480'
        ]);

        // create submission (updateOrCreate to allow resubmit)
        $submission = Submission::updateOrCreate(
            ['assignment_id'=>$assignment->id,'user_id'=>$user->id],
            ['text_answer'=>$req->text_answer,'status'=>'submitted','submitted_at'=>now()]
        );

        // handle file uploads if any (optional: create submission_files table)
        if ($req->hasFile('files')) {
            foreach ($req->file('files') as $f) {
                $path = $f->store("submissions/{$submission->id}", 'public');
                // if you created submission_files table, insert there
                // $submission->files()->create([...])
            }
        }

        return response()->json($submission,201);
    }

    public function grade(Request $req, Submission $submission) {
        if (!$req->user()->isDosen() && !$req->user()->isPustakawan()) {
            return response()->json(['message'=>'Forbidden'],403);
        }
        $data = $req->validate(['score'=>'nullable|integer|min:0','grade_feedback'=>'nullable|string']);
        $submission->update(array_merge($data, ['status'=>'graded']));
        return response()->json($submission);
    }
}
