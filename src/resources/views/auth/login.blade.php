@extends('layouts.app')

@section('title', 'Вход')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="h4 mb-3">Вход</h1>
            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf
                <div class="mb-3">
                    <label for="login" class="form-label">Логин</label>
                    <input id="login" name="login" value="{{ old('login') }}"
                           class="form-control @error('login') is-invalid @enderror" autofocus>
                    @error('login')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Пароль</label>
                    <input id="password" type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button class="btn btn-primary w-100">Войти</button>
            </form>
        </div>
    </div>
@endsection
