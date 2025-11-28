@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-7xl">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Мониторинг ПК</h1>
        <div class="flex gap-3">
            <a href="{{ route('computers.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium shadow transition">
                Добавить ПК
            </a>
            <button onclick="pingNow()" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium shadow transition">
                Пинг сейчас
            </button>
        </div>
    </div>

    <!-- ИМПОРТ CSV -->
    <div class="mb-8 p-6 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl shadow-md border border-indigo-100">
        <h3 class="text-lg font-bold text-indigo-800 mb-3">Импорт ПК из CSV</h3>
        <form action="{{ route('computers.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
            @csrf
            <input type="file" name="file" accept=".csv" required 
                   class="border border-indigo-300 p-2.5 rounded-lg bg-white text-sm focus:ring-2 focus:ring-indigo-500">
            <button type="submit" 
                    class="bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white px-6 py-2.5 rounded-lg font-medium shadow-lg transition">
                Импорт CSV
            </button>
        </form>
        @if(session('success'))
            <div class="mt-3 p-3 bg-green-100 text-green-800 rounded-lg text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <!-- ТАБЛИЦА ПК -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b">
                    <tr>
                        <th class="p-4 text-left font-semibold text-gray-700">Название</th>
                        <th class="p-4 text-left font-semibold text-gray-700">IP</th>
                        <th class="p-4 text-left font-semibold text-gray-700">Сотрудник</th>
                        <th class="p-4 text-left font-semibold text-gray-700">Статус</th>
                        <th class="p-4 text-center font-semibold text-gray-700">Пинг (мс)</th>
                        <th class="p-4 text-left font-semibold text-gray-700">Локация</th>
                        <th class="p-4 text-left font-semibold text-gray-700">Действия</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($computers as $c)
                        <tr class="{{ $c->status == 'offline' ? 'bg-red-50' : 'bg-green-50 hover:bg-green-100' }} transition">
                            <td class="p-4 font-medium text-gray-800">{{ $c->name }}</td>
                            <td class="p-4 font-mono text-sm text-gray-600">{{ $c->ip }}</td>
                            <td class="p-4 text-gray-700">{{ $c->employee_name ?? '—' }}</td>
                            <td class="p-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                                    {{ $c->status == 'offline' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $c->status == 'offline' ? 'Оффлайн' : 'Онлайн' }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                @if($c->ping_time !== null)
                                    <span class="inline-block px-3 py-1 rounded-full text-white text-xs font-bold
                                        {{ $c->ping_time <= 1 ? 'bg-green-600' : ($c->ping_time <= 10 ? 'bg-yellow-500' : 'bg-red-600') }}">
                                        {{ $c->ping_time }} мс
                                    </span>
                                @else
                                    <span class="text-red-600 font-bold">—</span>
                                @endif
                            </td>
                            <td class="p-4 text-gray-700">{{ $c->location ?? '—' }}</td>
                            <td class="p-4 flex gap-3">
                                <a href="{{ route('computers.edit', $c->id) }}" class="text-blue-600 hover:text-blue-800 font-medium underline">
                                    Редактировать
                                </a>
                                <form action="{{ route('computers.destroy', $c->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium underline"
                                            onclick="return confirm('Удалить ПК?')">
                                        Удалить
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-gray-500">
                                <p class="text-lg">ПК не добавлены.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function pingNow() {
        fetch('/computers/ping-all', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(() => {
            alert('Пинг запущен! Обновится через 3 сек...');
            setTimeout(() => location.reload(), 3000);
        });
    }

    setInterval(() => {
        if (document.visibilityState === 'visible') {
            location.reload();
        }
    }, 300000); // 5 минут
    </script>
</div>
@endsection