@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">
        Wi-Fi точки доступа <span class="badge bg-primary">{{ $points->count() }}</span>
    </h2>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Название</th>
                    <th>MAC</th>
                    <th>IP</th>
                    <th>Статус</th>
                    <th>Модель</th>
                    <th>Прошивка</th>
                    <th>Uptime</th>
                    <th>Последний опрос</th>
                </tr>
            </thead>
            <tbody>
                @foreach($points as $ap)
                <tr class="{{ $ap->isOnline() ? '' : 'table-danger' }}">
                    <td><strong>{{ $ap->name }}</strong></td>
                    <td><code>{{ $ap->mac_address }}</code></td>
                    <td>{{ $ap->ip }}</td>
                    <td>
                        @if($ap->isOnline())
                            <span class="badge bg-success">CONNECTED</span>
                        @else
                            <span class="badge bg-danger">DISCONNECTED</span>
                        @endif
                    </td>
                    <td>{{ $ap->model ?? '—' }}</td>
                    <td>{{ $ap->firmware ?? '—' }}</td>
                    <td>{{ $ap->uptime }}</td>
                    <td>{{ $ap->last_check?->diffForHumans() ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection