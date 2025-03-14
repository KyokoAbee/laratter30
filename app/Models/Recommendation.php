<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'reason',
        'book_title',
        'book_author',
        'book_thumbnail',
        'book_description',
        'book_id',
        'is_best'
    ];

    //post との関連付け
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // ユーザーとの関連付け
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book(){
        return $this->belongsTo(Book::class);
    }

    public function evaluation(){
        return $this->hasOne(RecommendationEvaluation::class);
    }


}
