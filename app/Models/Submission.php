<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model {
    protected $fillable = ['assignment_id','user_id','text_answer','status','submitted_at','score','grade_feedback','graded_by'];

    public function assignment() { return $this->belongsTo(Assignment::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function grader() { return $this->belongsTo(User::class,'graded_by'); }
}
