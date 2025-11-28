@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">
        {{ isset($phone) ? 'Редактировать телефон' : 'Добавить телефон' }}
    </h1>

    <form action="{{ isset($phone) ? route('phones.update', $phone->id) : route('phones.store') }}" method="POST">
        @csrf
        @if(isset($phone)) @method('PUT') @endif

        <div class="bg-white p-6 rounded shadow">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-2">Номер телефона</label>
                    <input name="phone_number" value="{{ old('phone_number', $phone->phone_number ?? '') }}"
                           placeholder="+7 (999) 123-45-67" class="border p-3 rounded w-full font-mono text-lg" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Сотрудник</label>
                    <select name="employee_id" class \`border p-3 rounded w-full\'>
                        <option value="">— Без сотрудника —</option>
                        @foreach($employees as $id => $name)
                            <option value="{{ $id }}" {{ old('employee_id', $phone->employee_id ?? '') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium mb-2">Описание</label>
                <textarea name="description" placeholder="Рабочий, мобильный, домашний..." 
                          class="border p-3 rounded w-full">{{ old('description', $phone->description ?? '') }}</textarea>
            </div>

            <div class="mt-6 flex gap-4">
                <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded font-medium">
                    {{ isset($phone) ? 'Сохранить изменения' : 'Добавить телефон' }}
                </button>
                <a href="{{ route('phones.index') }}" class="text-gray-600 underline">Отмена</a>
            </div>
        </div>
    </form>
</div>
@endsection