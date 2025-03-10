<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecommendationEvaluation extends Model
{
    protected $fillable = [
        'recommendation_id',
        'user_id',
        'thank_you_message'
    ];

    // レコメンデーションとの関係
    public function recommendation(){
        return $this->belongsTo(Recommendation::class);
    }

    // ユーザとの関係
    public function user(){
        return $this->belongsTo(User::class);
    }
}
