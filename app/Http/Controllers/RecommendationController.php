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

    // ベストレコメンド選出
    public function setBest($postId, $recommendationId){

        // 投稿が存在するか確認
        $post = Post::findOrFail($postId);

        // 投稿者のみがベストレコメンドを設定できるように権限チェック
        if (Auth::id() != $post->user_id) {
            return redirect()->route('post.show', $postId)->with('error', 'ベストレコメンドの設定は投稿者のみ可能です');
        }

        // 同じ投稿のほかのレコメンドをリセット
        Recommendation::where('post_id', $postId)
            ->where('is_best', true)
            ->update(['is_best' => false]);

        // 選択されたレコメンドをベストに設定
        $recommendation = Recommendation::findOrFail($recommendationId);
        $recommendation->is_best = true;
        $recommendation->save();

        return redirect()->route('post.show', $postId)->with('success', 'この投稿をベストレコメンドに設定しました');
    }

    // 返信一覧の表示
    public function myRecommendations(){
        $recommendations = Recommendation::where('user_id', Auth::id())
            ->with(['post', 'post.user', 'book'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('recommendations.my-recommendations',[
            'recommendations' => $recommendations,
            'title' => 'あなたの返信一覧'
        ]);
    }

        // is_best が付く前の投稿削除
        public function destroy(Recommendation $recommendation){
        // 認可チェック：自分の返信のみ削除可能 かつ is_best がfalseの場合のみ
        if (Auth::id() !== $recommendation->user_id) {
            return redirect()->back()->with('error', '他のユーザーの返信は削除できません');
        }
        
        if ($recommendation->is_best) {
            return redirect()->back()->with('error', 'ベストレコメンドに選ばれた返信は削除できません');
        }
        
        // 返信の削除
        $recommendation->delete();
        
        return redirect()->back()->with('success', '返信を削除しました');
        }
}
