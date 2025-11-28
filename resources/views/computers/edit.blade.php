@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-2xl">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Редактировать ПК</h1>

        <form action="{{ route('computers.update', $computer->id) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Название</label>
                <input type="text" name="name" value="{{ $computer->name }}" 
                       class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">IP-адрес</label>
                <input type="text" name="ip" value="{{ $computer->ip }}" 
                       class="w-full border border-gray-300 rounded-lg p-3 font-mono" required>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Сотрудник</label>
                <input type="text" name="employee_name" value="{{ $computer->employee_name }}" 
                       class="w-full border border-gray-300 rounded-lg p-3" placeholder="ФИО">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Локация</label>
                <input type="text" name="location" value="{{ $computer->location }}" 
                       class="w-full border border-gray-300 rounded-lg p-3" placeholder="Кабинет">
            </div>

            <div class="flex gap-3">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium shadow transition">
                    Сохранить
                </button>
                <a href="{{ route('computers.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium shadow transition">
                    Отмена
                </a>
            </div>
        </form>
    </div>
</div>
@endsection