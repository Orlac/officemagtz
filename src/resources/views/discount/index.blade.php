@extends('layouts.app')

@section('title', 'Получить скидку')

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="h5 mb-3">Получить скидку</h2>

            @if (session('issued'))
                <div class="alert alert-success">
                    Ваша скидка: <strong>{{ session('issued')['percent'] }}%</strong><br>
                    Код: <code class="fs-5">{{ session('issued')['code'] }}</code>
                </div>
            @endif

            <form method="POST" action="{{ route('discount.issue') }}">
                @csrf
                <button class="btn btn-primary">Получить скидку</button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="h5 mb-3">Проверить скидку</h2>

            @if (session()->has('check'))
                @if (session('check')['percent'] === null)
                    <div class="alert alert-danger">Скидка недоступна</div>
                @else
                    <div class="alert alert-success">Ваша скидка: <strong>{{ session('check')['percent'] }}%</strong></div>
                @endif
            @endif

            <form method="POST" action="{{ route('discount.check') }}" class="row g-2">
                @csrf
                <div class="col">
                    <input name="code" value="{{ old('code') }}" placeholder="Код скидки"
                           class="form-control @error('code') is-invalid @enderror">
                    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-primary">Проверить скидку</button>
                </div>
            </form>
        </div>
    </div>
@endsection
