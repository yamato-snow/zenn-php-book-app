<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * 新規登録フォーム（GET /register）。
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * ユーザーを登録する（POST /register）。
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            // confirmed = password_confirmation という確認用の項目と一致すること
            'password' => 'required|min:8|confirmed',
        ]);

        // User モデルの 'password' => 'hashed' キャストにより、保存時に自動でハッシュ化される。
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $validated['password'],
        ]);

        // 登録できたら、そのままログイン状態にする。
        Auth::login($user);

        return redirect()->route('stocks.index')->with('message', 'ようこそ、' . $user->name . ' さん');
    }

    /**
     * ログインフォーム（GET /login）。
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * ログインする（POST /login）。
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // メールとパスワードが一致すればログイン成功。
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // セッションを作り直す（なりすまし対策）。
            $request->session()->regenerate();

            return redirect()->intended(route('stocks.index'))->with('message', 'ログインしました');
        }

        // 失敗したら、入力に戻してエラーを出す。
        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'メールアドレスまたはパスワードが違います']);
    }

    /**
     * ログアウトする（POST /logout）。
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // セッションを破棄して作り直す。
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('message', 'ログアウトしました');
    }
}
