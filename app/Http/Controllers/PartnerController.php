<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PartnerController extends Controller
{
    /**
     * 一覧（GET /partners）。
     */
    public function index()
    {
        $partners = Partner::orderBy('id')->get();

        return view('partners.index', ['partners' => $partners]);
    }

    /**
     * 作成フォーム（GET /partners/create）。
     */
    public function create()
    {
        return view('partners.create');
    }

    /**
     * 保存（POST /partners）。
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            // type は「supplier か customer のどちらか」しか受け付けない。
            'type' => ['required', Rule::in(array_keys(Partner::TYPES))],
        ]);

        Partner::create($validated);

        return redirect()->route('partners.index')->with('message', '取引先を登録しました');
    }

    /**
     * 編集フォーム（GET /partners/{partner}/edit）。
     */
    public function edit(Partner $partner)
    {
        return view('partners.edit', ['partner' => $partner]);
    }

    /**
     * 更新（PUT /partners/{partner}）。
     */
    public function update(Request $request, Partner $partner)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'type' => ['required', Rule::in(array_keys(Partner::TYPES))],
        ]);

        $partner->update($validated);

        return redirect()->route('partners.index')->with('message', '取引先を更新しました');
    }

    /**
     * 削除（DELETE /partners/{partner}）。
     */
    public function destroy(Partner $partner)
    {
        $partner->delete();

        return redirect()->route('partners.index')->with('message', '取引先を削除しました');
    }
}
