@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">
        {{ isset($employee) ? 'Редактировать сотрудника' : 'Добавить сотрудника' }}
    </h1>

    <form action="{{ isset($employee) ? route('employees.update', $employee->id) : route('employees.store') }}" method="POST">
        @csrf
        @if(isset($employee)) @method('PUT') @endif

        <div class="bg-white p-6 rounded shadow">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-2">ФИО *</label>
                    <input name="full_name" value="{{ old('full_name', $employee->full_name ?? '') }}"
                           class="border p-3 rounded w-full" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Должность</label>
                    <input name="position" value="{{ old('position', $employee->position ?? '') }}"
                           class="border p-3 rounded w-full" placeholder="Директор, Бухгалтер, Системный администратор...">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Отдел</label>
                    <input name="department" value="{{ old('department', $employee->department ?? '') }}"
                           class="border p-3 rounded w-full" placeholder="Административный, IT, Бухгалтерия...">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Кабинет</label>
                    <input name="room" value="{{ old('room', $employee->room ?? '') }}"
                           class="border p-3 rounded w-full" placeholder="101, 205, Open Space">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Email Zimbra</label>
                    <input name="email_zimbra" value="{{ old('email_zimbra', $employee->email_zimbra ?? '') }}"
                           type="email" class="border p-3 rounded w-full" placeholder="user@company.local">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Email провайдера</label>
                    <input name="email_provider" value="{{ old('email_provider', $employee->email_provider ?? '') }}"
                           type="email" class="border p-3 rounded w-full" placeholder="user@gmail.com">
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium mb-2">Примечание</label>
                <textarea name="notes" rows="4" class="border p-3 rounded w-full"
                          placeholder="Дополнительная информация, дни рождения, аллергии, привычки...">{{ old('notes', $employee->notes ?? '') }}</textarea>
            </div>

            <div class="mt-6 flex gap-4">
                <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded font-medium">
                    {{ isset($employee) ? 'Сохранить' : 'Добавить' }}
                </button>
                <a href="{{ route('employees.index') }}" class="text-gray-600 underline">Отмена</a>
            </div>
        </div>
    </form>
</div>
@endsection