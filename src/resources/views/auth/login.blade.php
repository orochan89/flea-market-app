@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection


@section('nav')
@endsection

@section('content')
    <div class="login__container">
        <div class="login__container-title">
            ログイン
        </div>
        <form class="login-form" action="" method="post">
            @csrf
            <div class="login-form-input">
                <label for="email">メールアドレス</label>
                <input type="email" id="email">
            </div>
            <div class="login-form-input">
                <label for="password">パスワード</label>
                <input type="password" id="password">
            </div>
            <button class="login-form-input__button--submit" type="submit">登録する</button>
            <a class="login-form-input--register" href="/login">会員登録はこちら</a>
        </form>
    </div>
@endsection
