<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Recommendation;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RecommendationController extends Controller
{
    //返信フォーム
    public function create($id){

        // 返信する投稿を取得
        $post = Post::with(['user', 'post_tags'])->findOrFail($id);

        return view('recommendations.create', compact('post'));
    }

    // ここにテーブル構造確認コードを追加 後で消す
    public function __construct()
    {
        try {
            $columns = \Illuminate\Support\Facades\DB::select('SHOW COLUMNS FROM recommendations');
            Log::info('Table columns:', array_map(function($col) { return (array)$col; }, $columns));
        } catch (\Exception $e) {
            Log::error('Error showing columns: ' . $e->getMessage());
        }
    }



// 返信を保存
public function store(Request $request, $id)
{
    // デバッグ情報
    Log::info('Recommendation store method called');
    Log::info('Request data:', $request->all());

    // 投稿が存在するか確認
    $post = Post::findOrFail($id);

    // バリデーション
    $validatedData = $request->validate([
        'comment' => 'required|string', 
        'book_title' => 'required|string|max:255',
        'book_author' => 'required|string|max:255',
        'book_thumbnail' => 'nullable|url',
        'book_description' => 'nullable|string',
        'book_id' => 'nullable|string',
    ]);

    try {
        // 1. 本が存在するか確認し、なければ作成
        $book = Book::firstOrCreate(
            ['google_books_id' => $validatedData['book_id']],
            [
                'title' => $validatedData['book_title'],
                'author' => $validatedData['book_author'],
                'thumbnail' => $validatedData['book_thumbnail'] ?? null,
                'description' => $validatedData['book_description'] ?? null,
            ]
        );

        Log::info('Book record:', $book->toArray());

        // 2. レコメンデーションを作成
        $recommendation = new Recommendation();
        $recommendation->user_id = Auth::id();
        $recommendation->post_id = $id;
        $recommendation->book_id = $book->id; // 内部IDを参照

        // コメントの保存
        $recommendation->reason = $validatedData['comment'];
        $recommendation->is_best = false;

        // デバッグ出力
        Log::info('Recommendation model before save:', $recommendation->toArray());

        // 保存処理
        if ($recommendation->save()) {
            Log::info('Save result: success');
            Log::info('Recommendation ID: ' . $recommendation->id);
        } else {
            Log::error('Failed to save recommendation');
            return back()->withErrors(['database' => '保存に失敗しました'])->withInput();
        }

    } catch (\Exception $e) {
        // エラーハンドリング
        Log::error('Exception class: ' . get_class($e));
        Log::error('Exception message: ' . $e->getMessage());
        Log::error('Exception trace: ' . $e->getTraceAsString());
        return back()->withErrors(['database' => 'データベース保存中にエラーが発生しました: ' . $e->getMessage()])->withInput();
    }

    // リダイレクト先URLを明示的に構築
    $redirectUrl = "/post/{$id}";
    Log::info('Redirect URL: ' . $redirectUrl);
    return redirect($redirectUrl)->with('success', 'おすすめの本を投稿しました');
}

    // 編集
    public function edit($postId, $recommendationId){
        $recommendation = Recommendation::findOrFail($recommendationId);
        $post = Post::findOrFail($postId);

        // 自分の返信のみ編集できるようにする
        if ($recommendation->user_id !== Auth::id()) {
            return redirect()->route('post.show', $postId)->with('error', '編集権限がありません');
        }
        return view('recommendation.edit', compact('recommendation', 'post'));
    }

    // 更新
    public function update(Request $request, $postId, $recommendationId){
        $recommendation = Recommendation::FIndOrDail($recommendationId);

        // 自分の返信のみ更新可能
        if ($recommendation->user_id !== Auth::id()){
            return redirect()->route('post.show', $postId)->with('erroe', '編集権限がありません');
        }

        // バリデーション
        $validatedData = $request->validate([
            'reason' => 'required|string',
            'book_title' => 'required|string|max:255',
            'book_author' => 'required|string|max:255',
            'book_thumbnail' => 'nullable|url',
            'book_description' => 'nullable|string',
        ]);

        // レコメンデーションの更新
        $recommendation->reason = $validatedData['reason'];
        $recommendation->book_title = $validatedData['book_title'];
        $recommendation->book_author = $validatedData['book_author'];
        $recommendation->book_thumbnail = $validatedData['book_thumbnail'] ?? $recommendation->book_thumbnail;
        $recommendation->book_description = $validatedData['book_description'] ?? $recommendation->book_description;
        $recommendation->save();
        
        return redirect()->route('post.show', $postId)->with('success', 'おすすめの本を更新しました');
    }
}
