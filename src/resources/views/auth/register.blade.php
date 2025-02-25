@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection


@section('nav')
@endsection

@section('content')
    <div class="register__container">
        <div class="register__container-title">
            会員登録
        </div>
        <form class="register-form" action="" method="post">
            @csrf
            <div class="register-form-input">
                <label for="name">ユーザー名</label>
                <input type="text" id="name">
            </div>
            <div class="register-form-input">
                <label for="email">メールアドレス</label>
                <input type="email" id="email">
            </div>
            <div class="register-form-input">
                <label for="password">パスワード</label>
                <input type="password" id="password">
            </div>
            <div class="register-form-input">
                <label for="confirm-password">確認用パスワード</label>
                <input type="password" id="confirm-password">
            </div>
            <div class="register-form-input__button">
                <button class="register-form-input__button--submit" type="submit">登録する</button>
                <a class="register-form-input--register" href="/login">ログインはこちら</a>
            </div>
        </form>
    </div>
@endsection
