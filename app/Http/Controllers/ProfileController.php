<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        //この変数には、現在ログインしているユーザーの情報が格納されています。
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        /*
        ユーザーのメールアドレスが変更された場合、メールアドレスの検証ステータスをリセットします。
        isDirty()は、Eloquentモデル（この場合はUserモデル）が提供するメソッドの一つ 元の値から変更されたかどうかをチェック
        */
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();
        /*
        プロフィール編集ページにリダイレクトし、更新完了のメッセージを表示します。
        */
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        /*
        validateWithBagメソッドは、指定されたバッグ（この場合はuserDeletion）にバリデーションエラーを保存します。
        validateWithBagは、通常のvalidateメソッドと似ていますが、エラーを保存する場所を指定できる点が異なります。
        passwordフィールドが必須 (required)ユーザーはパスワードを入力しなければならない
        入力されたパスワードが現在のパスワードと一致する (current_password)
        */
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        //ユーザーのセッションを破棄
        Auth::logout();
        //ユーザーを削除
        $user->delete();
        //セッションを無効化
        $request->session()->invalidate();
        //トークンを再生成
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
