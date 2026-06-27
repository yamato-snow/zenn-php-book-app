<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * 一覧を表示する（GET /todos）。
     */
    public function index()
    {
        // 新しいものが上に来るように、id の降順で全件取得する。
        $todos = Todo::orderBy('id', 'desc')->get();

        // resources/views/todos/index.blade.php に $todos を渡して表示する。
        return view('todos.index', ['todos' => $todos]);
    }

    /**
     * 新規作成フォームを表示する（GET /todos/create）。
     */
    public function create()
    {
        return view('todos.create');
    }

    /**
     * フォームから送られた内容を保存する（POST /todos）。
     */
    public function store(Request $request)
    {
        // 入力チェック（バリデーション）。title は必須・255文字以内。
        $validated = $request->validate([
            'title' => 'required|max:255',
        ]);

        // チェックを通った値だけを使って1件作成する。
        Todo::create($validated);

        // 一覧ページに戻り、完了メッセージを1回だけ表示する。
        return redirect()->route('todos.index')->with('message', '追加しました');
    }

    /**
     * 編集フォームを表示する（GET /todos/{todo}/edit）。
     * 引数の $todo は、URL の id から自動で取ってきた Todo 1件。
     */
    public function edit(Todo $todo)
    {
        return view('todos.edit', ['todo' => $todo]);
    }

    /**
     * 編集内容を保存する（PUT /todos/{todo}）。
     */
    public function update(Request $request, Todo $todo)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
        ]);

        // チェックボックスは、チェックが入っていると 'on'、入っていないと送られてこない。
        // $request->has('done') で「チェックされたか」を true / false に直す。
        $validated['done'] = $request->has('done');

        $todo->update($validated);

        return redirect()->route('todos.index')->with('message', '更新しました');
    }

    /**
     * 1件削除する（DELETE /todos/{todo}）。
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();

        return redirect()->route('todos.index')->with('message', '削除しました');
    }
}
