@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection


@include('components.nav')

@section('content')
    <div class="register__container">
        <h1 class="register__container-title">
            会員登録
        </h1>
        <form class="register-form" action="" method="post" novalidate>
            @csrf
            <div class="register-form-input">
                <label class="register-form-input-label" for="name">ユーザー名</label>
                <input class="register-form-input-text" type="text" id="name" name="name"
                    value="{{ old('name') }}">
            </div>
            @error('name')
                <div class="alert">{{ $message }}</div>
            @enderror
            <div class="register-form-input">
                <label class="register-form-input-label" for="email">メールアドレス</label>
                <input class="register-form-input-text" type="email" id="email" name="email"
                    value="{{ old('email') }}">
            </div>
            @error('email')
                <div class="alert">{{ $message }}</div>
            @enderror
            <div class="register-form-input">
                <label class="register-form-input-label" for="password">パスワード</label>
                <input class="register-form-input-text" type="password" id="password" name="password"
                    value="{{ old('password') }}">
            </div>
            @error('password')
                <div class="alert">{{ $message }}</div>
            @enderror
            <div class="register-form-input">
                <label class="register-form-input-label" for="confirm-password">確認用パスワード</label>
                <input class="register-form-input-text" type="password" id="confirm-password" name="password_confirmation">
            </div>
            @error('password_confirmation')
                <div class="alert">{{ $message }}</div>
            @enderror
            <div class="register-form-input__button">
                <button class="register-form-input__button--submit" type="submit">登録する</button>
                <a class="register-form-input--login" href="{{ route('login') }}">ログインはこちら</a>
            </div>
        </form>
    </div>
@endsection
