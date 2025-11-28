<td class="p-3">{{ $cred->service_name }}</td>
<td class="p-3">{{ $cred->login }}</td>
<td class="p-3">
    @if(Session::has("unlocked_{$cred->id}"))
        <span class="text-green-700 font-bold">
            {{ Crypt::decryptString($cred->password_encrypted) }}
        </span>
        <button type="button" onclick="hidePassword({{ $cred->id }})" class="text-xs text-red-600 ml-2 underline">
            Скрыть
        </button>
    @else
        ******
        <span id="password-cell-{{ $cred->id }}">
            <input type="password" 
                   id="master-input-{{ $cred->id }}"
                   placeholder="мастер-пароль" 
                   class="text-xs w-24 px-1 border rounded">
            <button type="button" 
                    onclick="showPassword({{ $cred->id }})" 
                    class="text-xs text-indigo-600 ml-1 underline">
                Показать
            </button>
        </span>
    @endif
</td>
<td class="p-3">{{ $cred->connection_method }}</td>
<td class="p-3">
    <button type="button" 
            onclick="editCredential({{ $cred->id }})" 
            class="text-blue-600 mr-2 underline">
        Редактировать
    </button>

    <form action="{{ route('credentials.destroy', $cred->id) }}" method="POST" class="inline">
        @csrf @method('DELETE')
        <button type="submit" class="text-red-600 underline" onclick="return confirm('Удалить?')">
            Удалить
        </button>
    </form>
</td>