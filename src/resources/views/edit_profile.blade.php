@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/chang_profile.css') }}">
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
    <div class="change-profile__container">
        <div class="change-profile__container-title">
            プロフィール設定
        </div>
        <form class="change-profile-form" action="" method="post">
            @csrf
            <div class="change-profile-image">
                <img class="profile-icon" src="" alt="profile-icon">
                <button class="profile-icon-edit" type="submit">画像を選択する</button>
            </div>
            <div class="change-profile-form-input">
                <label for="name">ユーザー名</label>
                <input type="text" id="name">
            </div>
            <div class="change-profile-form-input">
                <label for="post-code">郵便番号</label>
                <input type="text" id="post-code">
            </div>
            <div class="change-profile-form-input">
                <label for="address">住所</label>
                <input type="text" id="address">
            </div>
            <div class="change-profile-form-input">
                <label for="building">建物名</label>
                <input type="text" id="building">
            </div>
            <div class="change-profile-form-input__button">
                <button class="change-profile-form-input__button--submit" type="submit">登録する</button>
            </div>
        </form>
    </div>
@endsection
