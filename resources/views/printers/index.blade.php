@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Принтеры (SNMP)</h1>
        <div class="flex gap-3">
            <a href="{{ route('printers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Добавить</a>
            <a href="{{ route('printers.export') }}" class="bg-green-600 text-white px-4 py-2 rounded">Экспорт Excel</a>
            <button onclick="refreshPrinters()" class="bg-purple-600 text-white px-4 py-2 rounded">
                Обновить тонер
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b">
                <tr>
                    <th class="p-4 text-left font-semibold text-gray-700">Название</th>
                    <th class="p-4 text-left font-semibold text-gray-700">IP</th>
                    <th class="p-4 text-left font-semibold text-gray-700">Модель</th>
                    <th class="p-4 text-left font-semibold text-gray-700">Тонер</th>
                    <th class="p-4 text-left font-semibold text-gray-700">Счётчик</th>
                    <th class="p-4 text-left font-semibold text-gray-700">Статус</th>
                    <th class="p-4 text-left font-semibold text-gray-700">Локация</th>
                    <th class="p-4 text-left font-semibold text-gray-700">Действия</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($printers as $p)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 font-medium text-gray-900">{{ $p->name }}</td>
                        <td class="p-4 font-mono text-sm text-gray-600">{{ $p->ip }}</td>
                        <td class="p-4 text-gray-700">{{ $p->model ?? '—' }}</td>
                     <td class="p-4">
    @if($p->toner_level !== null)
        <div class="flex items-center gap-2">
            <div class="w-32 bg-gray-200 rounded-full h-4 overflow-hidden shadow-inner">
                <div class="h-full rounded-full transition-all duration-700
                    {{ $p->toner_level >= 70 ? 'bg-gradient-to-r from-green-500 to-green-600' : 
                       ($p->toner_level >= 30 ? 'bg-gradient-to-r from-yellow-500 to-orange-500' : 
                       'bg-gradient-to-r from-red-600 to-red-700') }}"
                     style="width: {{ $p->toner_level }}%">
                </div>
            </div>
            <span class="text-sm font-bold
                {{ $p->toner_level >= 70 ? 'text-green-600' : 
                   ($p->toner_level >= 30 ? 'text-orange-600' : 'text-red-600') }}">
                {{ $p->toner_level }}%
            </span>
        </div>
    @else
        <span class="text-gray-400 text-sm">Нет данных</span>
    @endif
</td>


                        <td class="p-4 font-mono text-sm text-gray-700">{{ $p->pages_count ?? '—' }}</td>
                        <td class="p-4">
                            <span class="inline-flex items-center gap-1 text-green-600 font-medium">Онлайн</span>
                        </td>
                        <td class="p-4 text-gray-600">{{ $p->location ?? '—' }}</td>
                        <td class="p-4 text-sm">
                            <div class="flex gap-2">
                                <a href="{{ route('printers.edit', $p->id) }}" class="text-blue-600 hover:text-blue-800 underline">Редактировать</a>
                                <form action="{{ route('printers.destroy', $p->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 underline"
                                            onclick="return confirm('Удалить принтер?')">Удалить</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-gray-500">
                            Принтеры не добавлены. <a href="{{ route('printers.create') }}" class="text-blue-600 underline">Добавить первый</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

  <script>
function refreshPrinters() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/printers/refresh';
    form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';

    document.body.appendChild(form);
    form.submit();
}
</script>


</div>
@endsection