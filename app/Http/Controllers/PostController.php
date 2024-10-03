<?php

namespace App\Http\Controllers;
//use宣言は外部にあるクラスをPostController内にインポートできる。
//この場合、App\Models内のPostクラスをインポートしている。
use App\Models\Post;

use Illuminate\Http\Request;

class PostController extends Controller
{
    
    /**
     * Post一覧を表示する
     * 
     * @param Post Postモデル
     * @return array Postモデルリスト
     */
    public function index(Post $post)//インポートしたPostをインスタンス化して$postとして使用。
    {
        // $test = $post->orderBy('updated_at', 'DESC')->limit(2)->toSql(); //確認用に追加
        // dd($test); //確認用に追加
        return view('posts.index')->with(['posts'=>$post->getPaginateByLimit()]);
    }
}
