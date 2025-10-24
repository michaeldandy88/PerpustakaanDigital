<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model {
    protected $fillable = ['user_id','copy_id','borrowed_at','due_at','returned_at','status'];

    public function user() { return $this->belongsTo(User::class); }
    public function copy() { return $this->belongsTo(Copy::class); }
}
