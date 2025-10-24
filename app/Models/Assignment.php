<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model {
    protected $fillable = ['title','slug','description','available_from','due_at','submission_type','created_by'];

    public function creator() { return $this->belongsTo(User::class,'created_by'); }
    public function submissions() { return $this->hasMany(Submission::class); }
}
