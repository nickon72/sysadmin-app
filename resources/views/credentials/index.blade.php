@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Учетные данные</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <!-- ФОРМА ДОБАВЛЕНИЯ -->
    <div class="bg-white p-6 rounded shadow mb-6">
        <form action="{{ route('credentials.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <input name="service_name" placeholder="Сервис" class="border p-2 rounded" required>
                <input name="login" placeholder="Логин" class="border p-2 rounded" required>
                <input name="password" placeholder="Пароль" class="border p-2 rounded" required>
                <input name="connection_method" placeholder="Метод" class="border p-2 rounded">
            </div>
            <textarea name="description" placeholder="Описание" class="border p-2 rounded w-full mt-4"></textarea>
            <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Добавить</button>
        </form>
    </div>

    <!-- ПОИСК + ЭКСПОРТ -->
    <div class="mb-4 flex gap-4">
        <input type="text" 
               id="search-input" 
               placeholder="Поиск по сервису, логину, методу..." 
               class="border p-2 rounded flex-1">
        <a href="{{ route('credentials.export') }}" 
           class="bg-green-600 text-white px-4 py-2 rounded whitespace-nowrap">
            Экспорт Excel
        </a>
    </div>

    <!-- ТАБЛИЦА -->
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Сервис</th>
                    <th class="p-3 text-left">Логин</th>
                    <th class="p-3 text-left">Пароль</th>
                    <th class="p-3 text-left">Метод</th>
                    <th class="p-3 text-left">Действия</th>
                </tr>
            </thead>
            <tbody id="search-results">
                @foreach($credentials as $c)
                    <tr class="search-row" 
                        data-search="{{ $c->service_name }} {{ $c->login }} {{ $c->connection_method }} {{ $c->description }}">
                        @include('credentials.row', ['cred' => $c])
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- ФОРМА РЕДАКТИРОВАНИЯ -->
    <div id="edit-form" class="mt-6"></div>
</div>

@push('scripts')
<script>
   console.log('СКРИПТ ЗАГРУЖЕН — ПОИСК АКТИВЕН!');

    // ПОИСК
    $('#search-input').on('keyup', function() {
        const query = $(this).val().toLowerCase().trim();
        console.log('Поиск по:', query);

        $('.search-row').each(function() {
            const rowText = $(this).attr('data-search') || '';
            const lowerText = rowText.toLowerCase();
            console.log('Строка:', lowerText);

            const matches = lowerText.includes(query);
            $(this).toggle(matches);
        });
    });


    // ПОКАЗАТЬ ПАРОЛЬ
    window.showPassword = function(id) {
        const master = $(`#master-input-${id}`).val();
        const token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: `/credentials/${id}/show`,
            method: 'POST',
            data: { master_password: master, _token: token },
            success: function(html) { $(`#password-cell-${id}`).html(html); },
            error: function() { alert('Неверный мастер-пароль!'); }
        });
    };

    // СКРЫТЬ ПАРОЛЬ
    window.hidePassword = function(id) {
        const token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: `/credentials/${id}/hide`,
            method: 'POST',
            data: { _token: token },
            success: function(html) { $(`#password-cell-${id}`).html(html); }
        });
    };

    // РЕДАКТИРОВАНИЕ
    window.editCredential = function(id) {
        $.ajax({
            url: `/credentials/${id}/edit`,
            method: 'GET',
            success: function(html) { $('#edit-form').html(html); }
        });
    };
</script>
@endpush
@endsection