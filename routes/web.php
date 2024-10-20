<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;


/* 
auth: このミドルウェアは、ユーザーがログインしているかどうかをチェックします。
ログインしていない場合は、ログインページにリダイレクトされます。
verified: このミドルウェアは、ユーザーのメールアドレスが検証されているかどうかをチェックします。
メールアドレスが検証されていない場合は、エラーが発生します。
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


/* 
get(URL,メソッド)
group：ルートを囲むように使用され、グループ内のすべてのルートに共通の設定を適用します。
この場合、middleware(['auth']) はグループ内のすべてのルートに適用されます。
Route::controllerはRoute クラスの controller という静的メソッドを呼び出し
static（静的）メソッド：オブジェクトのインスタンスを作成せずに、クラスから直接呼び出すことができるメソッド
::class は、クラス名を文字列として取得するための演算子。"App\Http\Controllers\PostController" という文字列
*/
Route::controller(PostController::class)->middleware(['auth'])->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/posts', 'store')->name('store');
    Route::get('/posts/create', 'create')->name('create');
    Route::get('/posts/{post}', 'show')->name('show');
    Route::put('/posts/{post}', 'update')->name('update');
    Route::delete('/posts/{post}', 'delete')->name('delete');
    Route::get('/posts/{post}/edit', 'edit')->name('edit');
});


/* 
'/categories/{category}'：URLのパスと動的パラメータを定義。
[CategoryController::class, 'index']：どのコントローラのメソッドが実行されるかを指定。
->middleware('auth')：アクセス前に認証をチェックするミドルウェアを指定。
*/
Route::get('/categories/{category}', [CategoryController::class, 'index'])->middleware("auth");

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* 
__DIR__ は、現在のスクリプトファイル (web.php) が存在するディレクトリを指します。
require ステートメントは、指定されたファイルを読み込み、その内容を現在のスクリプトファイルにインクルードします。

*/
require __DIR__ . '/auth.php';
