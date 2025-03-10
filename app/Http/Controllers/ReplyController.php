<?php

namespace App\Http\Controllers;

use App\Models\RecommendationEvaluation;
use App\Models\Recommendation;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    // お礼コメントの保存
    public function storeThankYou(Request $request){
        $validated = $request->validate([
            'recommendation_id' => 'required|exists:recommendations,id',
            'thank_you_message' => 'required|string|max:1000',
        ]);

        // レコメンデーションを取得
        $recommendation = Recommendation::findOrFail($validated['recommendation_id']);

        // 投稿者かどうか確認
        if (auth()->id() !== $recommendation->post->user_id) {
            return redirect()->back()->with('error', '投稿者のみがお礼コメントを送ることができます');
        }

        // お礼コメントを作成
        RecommendationEvaluation::updateOrCreate(
            [
                'recommendation_id' => $validated['recommendation_id'],
                'user_id' => auth()->id(),
            ],
            [
                'thank_you_message' => $validated['thank_you_message'],
            ]
            );
            return redirect()->back()->with('success', 'お礼コメントを送信しました');
    }
}
