<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'google_books_id',
        'title',
        'author',
        'isbn',
        'publisher',
        'published_date',
        'thumbnail',
        'description',
    ];

    public function recommendations() {
        return $this->hasMany(Recommendation::class);
    }
}
