<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Book extends Model {
    protected $fillable = ['title','slug','isbn','year','description','cover_path','pdf_path'];

    public function copies() { return $this->hasMany(Copy::class); }
}
