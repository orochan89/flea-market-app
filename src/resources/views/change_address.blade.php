@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/change_address.css') }}">
@endsection


@section('nav')
    @auth
        <li class="header-nav">
            <form class="header-nav__logout" action="{{ route('logout') }}" method="post">
                @csrf
                <button class="header-nav__logout__button" type="submit">ログアウト</button>
            </form>
        </li>
    @endauth
    @guest
        <li class="header-nav">
            <form class="header-nav__login" action="{{ route('login') }}" method="get">
                @csrf
                <button class="header-nav__login__button" type="submit">ログイン</button>
            </form>
        </li>
    @endguest
    <form class="header-nav__mypage" action="{{ route('mypage') }}" method="post">
        <button class="header-nav__mypage__button" type="submit">マイページ</button>
    </form>
    <form class="header-nav__sell" action="{{ route('sell') }}" method="post">
        <button class="header-nav__sell__button" type="submit">出品</button>
    </form>
@endsection


@section('content')
    <div class="change-address__container">
        <div class="change-address--title">
            住所の変更
        </div>
        <form class="change-address-form" action="" method="post">
            <div class="change-address">
                <label class="form-title" for="post-code">郵便番号</label>
                <input type="text" name="post-code" id="post-code">
            </div>
            <div class="change-address">
                <label class="form-title" for="address">住所</label>
                <input type="text" name="address" id="address">
            </div>
            <div class="change-address">
                <label class="form-title" for="building">建物名</label>
                <input type="text" name="building" id="building">
            </div>
            <div class="change-address-form-input__button">
                <button class="login-form-input__button--submit" type="submit">更新する</button>
            </div>
        </form>
    </div>
@endsection
