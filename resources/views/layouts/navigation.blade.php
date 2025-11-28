<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

          <!-- Desktop Menu -->
<div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
    <a href="{{ route('credentials.index') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Учетки</a>
    <a href="{{ route('employees.index') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Сотрудники</a>
    <a href="{{ route('phones.index') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Телефоны</a>
    <a href="{{ route('printers.index') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Принтеры</a>
    <a href="{{ route('computers.index') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Компьютеры</a>
{{-- Wi-Fi точки доступа (Omada SDN Controller) --}}
<a href="https://192.168.35.11:8043/#devices/ap" 
   target="_blank" 
   rel="noopener noreferrer"
   class="text-sm font-medium text-gray-700 hover:text-gray-900 flex items-center gap-1.5">
    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
        <path d="M3.2 5.4a8.5 8.5 0 0113.6 0l-1.4 1.4a6.5 6.5 0 00-10.4 0L3.2 5.4z"/>
        <path d="M5.8 8a5.5 5.5 0 018.4 0l-1.4 1.4a3.5 3.5 0 00-5.6 0L5.8 8z"/>
        <path d="M8.5 10.5a2.5 2.5 0 013 0l-1.5 1.5a.5.5 0 00-.7 0L8.5 10.5z"/>
        <circle cx="10" cy="15" r="2"/>
    </svg>
    Wi-Fi точки
    <span class="text-xs text-gray-500">(новое окно)</span>
</a>   
<!-- Поиск по ДРФО — мгновенно из базы DRFO -->
<div class="relative ml-4">
    <input type="text"
           id="drfo-search-input"
           placeholder="ДРФО (10 цифр) → Enter"
           class="w-64 px-4 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
           maxlength="10">

    <!-- Модалка -->
    <div id="drfo-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full mx-4 p-6 relative">
            <button type="button" onclick="document.getElementById('drfo-modal').classList.add('hidden')"
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-3xl font-light">&times;</button>
            <div id="drfo-result" class="text-center"></div>
        </div>
    </div>
</div>

<script>
// Ждём полной загрузки DOM — это главное!
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('drfo-search-input');
    const modal = document.getElementById('drfo-modal');
    const resultDiv = document.getElementById('drfo-result');

    if (!input) {
        console.error('Поле drfo-search-input не найдено!');
        return;
    }

    input.addEventListener('keypress', async function (e) {
        if (e.key !== 'Enter') return;

        const code = this.value.trim();
        if (!/^\d{10}$/.test(code)) {
            alert('Введите ровно 10 цифр');
            return;
        }

        resultDiv.innerHTML = '<p class="text-blue-600">Ищем в базе ДРФО...</p>';
        modal.classList.remove('hidden');

        try {
            const response = await fetch('http://192.168.13.111/api/drfo-search?code=' + code, {
                method: 'GET',
                cache: 'no-cache',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) throw new Error('HTTP ' + response.status);

            const data = await response.json();

            if (!data.found) {
                resultDiv.innerHTML = '<p class="text-red-600 text-xl">Не найдено</p>';
                return;
            }

            const p = data.person;
            const addr = data.residence[0] || {};

            resultDiv.innerHTML = `
                <h3 class="text-2xl font-bold text-green-600 mb-4">${p.full_name}</h3>
                <div class="text-left space-y-2 text-sm">
                    <div><strong>Код:</strong> ${p.code}</div>
                    <div><strong>Дата рождения:</strong> ${p.birth_date || '—'}</div>
                    <div><strong>Адрес:</strong><br>
                        ${addr.locality || ''}, ${addr.street || ''} ${addr.house || ''}, кв. ${addr.apartment || ''}
                        ${addr.phone ? '<br><em>Тел: ' + addr.phone + '</em>' : ''}
                    </div>
                    ${data.workplaces?.length ? `
                    <div><strong>Работа (ЕДРПОУ):</strong><br>
                        ${data.workplaces.map(w => 
                            `<span class="inline-block bg-gray-200 px-3 py-1 rounded mr-2 mb-1 text-xs">${w}</span>`
                        ).join('')}
                    </div>` : ''}
                </div>
            `;
        } catch (err) {
            console.error('Ошибка:', err);
            resultDiv.innerHTML = `<p class="text-red-600">Ошибка связи: ${err.message}</p>`;
        }
    });
});
</script>


</div>

            <!-- Mobile -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>



    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
    <div class="pt-2 pb-3 space-y-1">
        <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
        </x-responsive-nav-link>

        <!-- Учетки -->
        <x-responsive-nav-link :href="route('credentials.index')" :active="request()->routeIs('credentials.*')">
            {{ __('Учетки') }}
        </x-responsive-nav-link>

        <!-- Сотрудники -->
        <x-responsive-nav-link :href="route('employees.index')" :active="request()->routeIs('employees.*')">
            {{ __('Сотрудники') }}
        </x-responsive-nav-link>

        <!-- Телефоны -->
        <x-responsive-nav-link :href="route('phones.index')" :active="request()->routeIs('phones.*')">
            {{ __('Телефоны') }}
        </x-responsive-nav-link>
    </div>
</div>
</nav>