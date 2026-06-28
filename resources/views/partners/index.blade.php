@extends('layouts.app')

@section('title', '取引先マスタ')

@section('content')
    <div class="mb-4">
        <a href="{{ route('partners.create') }}"
           class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            ＋ 取引先を登録
        </a>
    </div>

    @if ($partners->isEmpty())
        <p class="text-gray-500">まだ取引先がありません。「取引先を登録」から追加してください。</p>
    @else
        <table class="w-full bg-white rounded shadow text-sm">
            <thead>
                <tr class="border-b text-left text-gray-500">
                    <th class="px-4 py-2">取引先名</th>
                    <th class="px-4 py-2">区分</th>
                    <th class="px-4 py-2 text-right">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($partners as $partner)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $partner->name }}</td>
                        <td class="px-4 py-2">{{ $partner->typeLabel() }}</td>
                        <td class="px-4 py-2 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('partners.edit', $partner) }}"
                                   class="text-blue-600 hover:underline">編集</a>
                                <form action="{{ route('partners.destroy', $partner) }}" method="POST"
                                      onsubmit="return confirm('削除しますか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">削除</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
