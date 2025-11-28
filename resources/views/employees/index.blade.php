
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Сотрудники</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <a href="{{ route('employees.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">+ Добавить сотрудника</a>
   <a href="{{ route('employees.export') }}" class="bg-green-600 text-white px-4 py-2 rounded">Экспорт Excel</a>
    <div class="mb-4">
        <input type="text" id="search-input" placeholder="Поиск по ФИО, должности, отделу..." class="border p-2 rounded w-full">
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
    <tr>
        <th class="p-3 text-left">ФИО</th>
        <th class="p-3 text-left">Должность</th>
        <th class="p-3 text-left">Отдел</th>
        <th class="p-3 text-left">Кабинет</th>
        <th class="p-3 text-left">Email Zimbra</th>
        <th class="p-3 text-left">Email провайдер</th>
        <th class="p-3 text-left">Телефоны</th>
        <th class="p-3 text-left">Примечание</th>
        <th class="p-3 text-left">Действия</th>
    </tr>
</thead>
<tbody id="search-results">
    @foreach($employees as $e)
        <tr class="search-row" 
            data-search="{{ $e->full_name }} {{ $e->position }} {{ $e->department }} {{ $e->room }} {{ $e->email_zimbra }} {{ $e->email_provider }} {{ $e->notes }}">
            <td class="p-3 font-medium">{{ $e->full_name }}</td>
            <td class="p-3">{{ $e->position ?? '—' }}</td>
            <td class="p-3">{{ $e->department ?? '—' }}</td>
            <td class="p-3">{{ $e->room ?? '—' }}</td>
            <td class="p-3">
                @if($e->email_zimbra)
                    <a href="mailto:{{ $e->email_zimbra }}" class="text-blue-600 hover:underline">{{ $e->email_zimbra }}</a>
                @else —
                @endif
            </td>
            <td class="p-3">
                @if($e->email_provider)
                    <a href="mailto:{{ $e->email_provider }}" class="text-blue-600 hover:underline">{{ $e->email_provider }}</a>
                @else —
                @endif
            </td>
            <td class="p-3">
                <div class="space-y-1">
                    @foreach($e->phones as $p)
                        <div class="flex items-center gap-2 font-mono text-sm">
                            <span>{{ $p->phone_number }}</span>
                            @if($p->description)<span class="text-xs text-gray-500">({{ $p->description }})</span>@endif
                            <button onclick="copyToClipboard('{{ $p->phone_number }}')" class="text-green-600 hover:text-green-800 text-xs" title="Копировать">Copy</button>
                            <a href="tel:{{ $p->phone_number }}" class="text-indigo-600 hover:text-indigo-800 text-xs" title="Позвонить">Call</a>
                        </div>
                    @endforeach
                    @if($e->phones->isEmpty()) <span class="text-gray-400">—</span> @endif
                </div>
            </td>
            <td class="p-3 text-sm text-gray-600 max-w-xs truncate" title="{{ $e->notes }}">
                {{ $e->notes ? Str::limit($e->notes, 50) : '—' }}
            </td>
            <td class="p-3">
                <a href="{{ route('employees.edit', $e->id) }}" class="text-blue-600 underline mr-3">Редактировать</a>
                <form action="{{ route('employees.destroy', $e->id) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-600 underline" onclick="return confirm('Удалить?')">Удалить</button>
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
    $('#search-input').on('keyup', function() {
        const query = $(this).val().toLowerCase().trim();
        $('.search-row').each(function() {
            const text = $(this).attr('data-search').toLowerCase();
            $(this).toggle(text.includes(query));
        });
    });

$('#search-input').on('keyup', function() {
        const query = $(this).val().toLowerCase().trim();
        $('.search-row').each(function() {
            const text = $(this).attr('data-search') || '';
            $(this).toggle(text.toLowerCase().includes(query));
        });
    });

    // Копирование в буфер
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Номер скопирован: ' + text);
        }).catch(() => {
            alert('Не удалось скопировать');
        });
    }


</script>
@endpush
@endsection
