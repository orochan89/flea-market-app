@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/') }}">
@endsection

@extends('nav')

@section('nav')
    @auth
    <form class="search-form" action="search" method="get">
        <input class="search-form-input" type="text" placeholder="なにをお探しですか？" name="search">
    </form>
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
    <form class="profile-form" action="" method="">
        @csrf
        <div class="profile">
            <img class="profile-image" src="" alt="user-icon">
            <h2 class="profile-user-name">{{}}</h2>
            <input class="profile-user-name" type="hidden" value="{{}}">
            <button class="profile-edit-button" type="submit">プロフィールを編集</button>
        </div>
    </form>
    <div class="item__container">
        <div class="item__container-tabs">
            <a class="tab" href="#recommend">出品した商品</a>
            <a class="tab" href="#mylist">購入した商品</a>
        </div>
    </div>
    {{-- 以下 recommendタブ表示 --}}
    <div class="item__tab-content" id="recommend">
        @foreach ($collection as $item)
            <div>
                <img class="item-image" src="{{}}" alt="商品画像">
                <label class="item-label" for="{{}}">
                    {{}}
                </label>
            </div>
        @endforeach
    </div>
    {{-- 以下 mylistタブ表示 --}}
    <div class="item__tab-content" id="mylist">
        @foreach ($collection as $item)
            <div>
                <img class="item-image" src="{{}}" alt="商品画像">
                <label class="item-label" for="{{}}">
                    {{}}
                </label>
            </div>
        @endforeach
    </div>
@endsection
