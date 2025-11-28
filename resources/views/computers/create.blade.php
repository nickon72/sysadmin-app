@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-2xl">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Добавить ПК</h1>

        <form action="{{ route('computers.store') }}" method="POST">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Название *</label>
                <input type="text" name="name" value="{{ old('name') }}" 
                       class="w-full border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg p-3 focus:ring-2 focus:ring-blue-500" 
                       placeholder="ПК 38 - Новый отдел" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">IP-адрес *</label>
                <input type="text" name="ip" value="{{ old('ip') }}" 
                       class="w-full border @error('ip') border-red-500 @else border-gray-300 @enderror rounded-lg p-3 font-mono" 
                       placeholder="192.168.13.200" required>
                @error('ip')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Сотрудник</label>
                <input type="text" name="employee_name" value="{{ old('employee_name') }}" 
                       class="w-full border border-gray-300 rounded-lg p-3" 
                       placeholder="Иванов И.И.">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Локация</label>
                <input type="text" name="location" value="{{ old('location') }}" 
                       class="w-full border border-gray-300 rounded-lg p-3" 
                       placeholder="Кабинет 12">
            </div>

            <div class="flex gap-3">
                <button type="submit" 
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium shadow transition">
                    Добавить ПК
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