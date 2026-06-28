@extends('layouts.app')

@section('title', '取引先の編集')

@section('content')
    @if ($errors->any())
        <div class="mb-4 rounded bg-red-100 border border-red-300 text-red-800 px-4 py-2">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('partners.update', $partner) }}" method="POST" class="bg-white rounded shadow p-4">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block mb-1 font-bold">取引先名</label>
            <input type="text" name="name" id="name"
                   value="{{ old('name', $partner->name) }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label for="type" class="block mb-1 font-bold">区分</label>
            <select name="type" id="type" class="w-full border rounded px-3 py-2">
                @foreach (\App\Models\Partner::TYPES as $value => $label)
                    <option value="{{ $value }}" {{ old('type', $partner->type) === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-2">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">更新</button>
            <a href="{{ route('partners.index') }}" class="text-gray-600 hover:underline">一覧に戻る</a>
        </div>
    </form>
@endsection
