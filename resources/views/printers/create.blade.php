@extends('layouts.app')
@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Добавить принтер</h1>
    <form action="{{ route('printers.store') }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf
        <input name="name" placeholder="Название" class="border p-2 rounded w-full mb-3" required>
        <input name="ip" placeholder="IP (192.168.13.161)" class="border p-2 rounded w-full mb-3" required>
        <input name="location" placeholder="Локация" class="border p-2 rounded w-full mb-3">
        <textarea name="notes" placeholder="Примечания" class="border p-2 rounded w-full mb-3"></textarea>
        <button type="submit" class="bg-green-600 text-white px-5 py-2 rounded">Добавить</button>
        <a href="{{ route('printers.index') }}" class="ml-3 text-gray-600">Отмена</a>
    </form>
</div>
@endsection