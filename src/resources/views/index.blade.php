@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
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
    <div class="flea-market__container">
        <div class="flea-market__container-tabs">
            <a class="tab" href="#recommend">おすすめ</a>
            <a class="tab" href="#mylist">マイリスト</a>
        </div>
    </div>
    {{-- 以下 recommendタブ表示 --}}
    <div class="flea-market__tab-content" id="recommend">
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
    <div class="flea-market__tab-content" id="mylist">
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
