<?php

namespace App\Http\Controllers;
//use宣言は外部にあるクラスをPostController内にインポートできる。
//この場合、App\Models内のPostクラスをインポートしている。
use App\Models\Post;

use App\Http\Requests\PostRequest; // useする
use App\Models\Category;

class PostController extends Controller
{

    /**
     * Post一覧を表示する
     * 
     * @param Post Postモデル
     * @return array Postモデルリスト
     */
    public function index(Post $post)
    {
        // クライアントインスタンス生成
        /*
        verify:検証
        ローカル環境 (app.envがlocalの場合) では、SSL証明書の検証を無効にします。
        */
        $client = new \GuzzleHttp\Client(
            ['verify' => config('app.env') !== 'local'],
        );

        // GET通信するURL
        $url = 'https://teratail.com/api/v1/questions';

        // リクエスト送信と返却データの取得
        // Bearerトークンにアクセストークンを指定して認証を行う
        /*
        このリクエストは、Teratail APIに対して認証付きのGETリクエストを送信するために使用されます。
        [] は、PHPの配列（Array）キー: Bearer 値: config('services.teratail.token') の戻り値
        */
        $response = $client->request(
            'GET',
            $url,
            ['Bearer' => config('services.teratail.token')]
        );

        // API通信で取得したデータはjson形式なので
        // PHPファイルに対応した連想配列にデコードする
        $questions = json_decode($response->getBody(), true);

        // index bladeに取得したデータを渡す
        return view('posts.index')->with([
            'posts' => $post->getPaginateByLimit(),
            //このコードは、$questions配列から'questions'キーに対応する値を取り出し、ビューで使う'questions'に再度割り当てています。
            'questions' => $questions['questions'],
        ]);
    }
    /**
     * 特定IDのpostを表示する
     *
     * @params Object Post // 引数の$postはid=1のPostインスタンス
     * @return Response post view
     */
    public function show(Post $post)
    {
        return view('posts.show')->with(['post' => $post]);
        //'post'はbladeファイルで使う変数。中身は$postはid=1のPostインスタンス。
    }
    public function create(Category $category)
    {
        return view('posts.create')->with(['categories' => $category->get()]);
    }
    public function store(Post $post, PostRequest $request)
    {
        /*
        fill：埋める
        fill($input)はリクエストデータ（ユーザーがサーバーに送信したデータ）をモデルに上書きする
        save() は、モデルに設定されたデータをデータベースに保存します。
        モデルデータ：メモリ上に存在し、プログラムの実行中のみ有効です。
        データベースのデータ：ディスク上に永続的に保存され、プログラムの実行後も残ります。
        */
        $input = $request['post'];
        $post->fill($input)->save();
        return redirect('/posts/' . $post->id);
    }
    public function edit(Post $post)
    {
        return view('posts.edit')->with(['post' => $post]);
    }
    public function update(PostRequest $request, Post $post)
    {
        $input_post = $request['post'];
        $post->fill($input_post)->save();

        return redirect('/posts/' . $post->id);
    }
    public function delete(Post $post)
    {
        $post->delete();
        return redirect('/');
    }
}
