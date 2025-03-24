@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection


@include('components.nav')

@section('content')
    <div class="login__container">
        <h1 class="login__container-title">
            ログイン
        </h1>
        <form class="login-form" action="" method="post">
            @csrf
            <div class="login-form-input">
                <label class="login-form-input-label" for="email">メールアドレス</label>
                <input class="login-form-input-text" type="email" id="email" name="email">
            </div>
            <div class="login-form-input">
                <label class="login-form-input-label" for="password">パスワード</label>
                <input class="login-form-input-text" type="password" id="password" name="password">
            </div>
            <div class="login-form-input__button">
                <button class="login-form-input__button--submit" type="submit">ログインする</button>
                <a class="login-form-input--register" href="{{ route('register') }}">会員登録はこちら</a>
            </div>
        </form>
    </div>
@endsection
