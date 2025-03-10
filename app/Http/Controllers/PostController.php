<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post; // Postモデルをインポート
use Illuminate\Support\Facades\Auth; // Authファサードをインポート、Auth ファサードは、Laravelの認証システムにアクセスするためのインターフェースを提供


class PostController extends Controller
{
    // 一覧
    public function index(){
        // dd('PostControllerのindex is called'); // このメッセージが表示されるか確認
        $posts = Post::with(['user', 'post_tags', 'recommendations'])
            ->latest() //新着順
            ->paginate(10);

        // dd($posts); // データが取得できているか確認

    return view('dashboard', compact('posts'));

    }


    // ユーザーに見せる画面は return view してview ファイルにコンテンツを書く
    //投稿
    public function create(){
    return view('post.create');

    }

    // 投稿されたデータを受け取り、DBへ保存
    // Request $requestは引数。
    public function store(Request $request){
        // バリデーション
        $validatedData = $request->validate([
            'content' => 'required|string',
            'status' => 'nullable|string|max:255',
        ]);

        // 保存データ
        $post = new Post();
        $post->user_id = Auth::id();
        $post->content = $validatedData['content'];
        $post->status = $validatedData['status'] ?? 'default status';
        
        // DBに保存
        $post->save();

        //編集画面にリダイレクト
        return redirect()->route('post.edit', $post->id)->with('success', '投稿が作成されました');
	}

    // 編集画面を表示
    public function edit($id){
        $post = Post::findOrFail($id);
        return view('post.edit', compact('post'));
    }

    // 編集された投稿内容をDBに保存
    public function update(Request $request, $id){
        // バリデーション
         $validatedData = $request->validate([
        'content' => 'required|string',
        'status' => 'nullable|string|max:255',
    ]);

        // 投稿の更新
        $post = Post::findOrFail($id);
        $post->content = $validatedData['content'];
        $post->status = $validatedData['status'] ?? 'default status'; // デフォルト値を設定
        $post->save();

        return redirect()->route('post.edit', $post->id)->with('success', '投稿されました');
	
	}
	
	public function delete(){
	
	
	}

    // 検索
    public function search(Request $request){
        $query = $request->input('query'); //検索ボックスに入力した文字列を取得
        $posts = Post::where('content', 'like', "%{$query}%")->get(); //postsテーブル内を検索

        return view('post.search-results', compact('posts')); //$posts変数をview に渡す
    }

    // 検索結果の表示
    public function show($id){
        $post = Post::findOrFail($id);
        return view('post.show', compact('post'));

        // 返信も表示
        $post = Post::with(['user', 'recommendations.user', 'recommendations.book'])->findOrFail($id);
        return view('post.show', compact('post'));
    }

    // 投稿一覧の表示
    public function myPosts() {
        $posts = Post::where('user_id', Auth::id())
            ->with(['user', 'post_tags', 'recommendations'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        // 既存のダッシュボードのビューを再利用
       return view('dashboard',[
        'posts' => $posts,
        'title' =>'あなたの投稿一覧'
       ]);
    }

 }
