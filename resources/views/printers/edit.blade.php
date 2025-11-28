@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Редактировать принтер</h1>
        <a href="{{ route('printers.index') }}" class="text-gray-600 hover:text-gray-800 underline">Назад к списку</a>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('printers.update', $printer->id) }}" method="POST" class="bg-white p-8 rounded-xl shadow-lg">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Название -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Название *</label>
                <input name="name" value="{{ old('name', $printer->name) }}" 
                       class="border border-gray-300 p-3 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                       required>
            </div>

            <!-- IP -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">IP-адрес *</label>
                <input name="ip" value="{{ old('ip', $printer->ip) }}" 
                       placeholder="192.168.13.161" 
                       class="border border-gray-300 p-3 rounded-lg w-full font-mono focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                       required>
            </div>

            <!-- Локация -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Локация</label>
                <input name="location" value="{{ old('location', $printer->location) }}" 
                       placeholder="Кабинет 101" 
                       class="border border-gray-300 p-3 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Примечания -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Примечания</label>
                <textarea name="notes" rows="4" 
                          placeholder="Серийный номер, дата установки, ответственный..." 
                          class="border border-gray-300 p-3 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes', $printer->notes) }}</textarea>
            </div>
        </div>

        <div class="mt-8 flex gap-4">
            <button type="submit" 
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium shadow transition">
                Сохранить изменения
            </button>
            <a href="{{ route('printers.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-medium transition">
                Отмена
            </a>
        </div>
    </form>
</div>
@endsection