<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Copy extends Model {
    protected $fillable = ['book_id','barcode','copy_type','status','location'];

    public function book() { return $this->belongsTo(Book::class); }
    public function loans() { return $this->hasMany(Loan::class); }
}
