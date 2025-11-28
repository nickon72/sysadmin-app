@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Телефоны</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex gap-4">
        <a href="{{ route('phones.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Добавить телефон</a>
        <a href="{{ route('phones.export') }}" class="bg-green-600 text-white px-4 py-2 rounded">Экспорт Excel</a>
    </div>

    <div class="mb-4">
        <input type="text" id="search-input" placeholder="Поиск по номеру, сотруднику, описанию..." class="border p-2 rounded w-full">
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Номер телефона</th>
                    <th class="p-3 text-left">Сотрудник</th>
                    <th class="p-3 text-left">Описание</th>
                    <th class="p-3 text-left">Действия</th>
                </tr>
            </thead>
            <tbody id="search-results">
                @foreach($phones as $p)
                    <tr class="search-row" 
                        data-search="{{ $p->phone_number }} {{ $p->employee?->full_name }} {{ $p->description }}">
                        <td class="p-3 font-mono">{{ $p->phone_number }}</td>
                        <td class="p-3">{{ $p->employee?->full_name ?? '—' }}</td>
                        <td class="p-3">{{ $p->description ?? '—' }}</td>
                        <td class="p-3">
                            <a href="{{ route('phones.edit', $p->id) }}" class="text-blue-600 underline mr-3">Редактировать</a>
                            <form action="{{ route('phones.destroy', $p->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 underline" onclick="return confirm('Удалить телефон?')">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    console.log('Phones: скрипт загружен!');

    $('#search-input').on('keyup', function() {
        const query = $(this).val().toLowerCase().trim();
        $('.search-row').each(function() {
            const text = $(this).attr('data-search') || '';
            $(this).toggle(text.toLowerCase().includes(query));
        });
    });
</script>
@endpush
@endsection
