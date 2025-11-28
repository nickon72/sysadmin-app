<form action="{{ route('credentials.update', $cred->id) }}" method="POST" class="bg-white p-6 rounded shadow mt-6">
    @csrf @method('PUT')
    <div class="grid grid-cols-2 gap-4">
        <input name="service_name" value="{{ $cred->service_name }}" class="border p-2 rounded" required>
        <input name="login" value="{{ $cred->login }}" class="border p-2 rounded" required>
        <input name="password" value="{{ $cred->decrypted_password }}" class="border p-2 rounded" required>
        <input name="connection_method" value="{{ $cred->connection_method }}" class="border p-2 rounded">
    </div>
    <textarea name="description" class="border p-2 rounded w-full mt-4">{{ $cred->description }}</textarea>
    <div class="mt-4">
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Обновить</button>
        <button type="button" onclick="$('#edit-form').empty()" class="bg-gray-600 text-white px-4 py-2 rounded ml-2">
            Отмена
        </button>
    </div>
</form>