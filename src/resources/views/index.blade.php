@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('nav')
    <div class="search-nav">
        <form class="search-form" action="search" method="get">
            <input class="search-form-input" type="text" placeholder="なにをお探しですか？" name="search">
        </form>
    </div>
    <div class="header-nav-container">
        @auth
            <form class="header-nav__logout" action="{{ route('logout') }}" method="post">
                @csrf
                <button class="header-nav__logout__button" type="submit">ログアウト</button>
            </form>
        @endauth
        @guest
            <form class="header-nav__login" action="{{ route('login') }}" method="get">
                <button class="header-nav__login__button" type="submit">ログイン</button>
            </form>
        @endguest
        <form class="header-nav__mypage" action="{{ route('profile') }}" method="get">
            <button class="header-nav__mypage__button" type="submit">マイページ</button>
        </form>
        <form class="header-nav__sell" action="{{ route('sell') }}" method="get">
            <button class="header-nav__sell__button" type="submit">出品</button>
        </form>
    </div>
@endsection

@section('content')
    <div class="flea-market__container">
        <div class="flea-market__container-tabs">
            <a class="tab" href="{{ url('/') }}">おすすめ</a>
            <a class="tab" href="{{ request()->fullUrlWithQuery(['tab' => 'mylist']) }}">マイリスト</a>
        </div>
        {{-- 以下 mylistタブ表示 --}}
        @if ($tab === 'mylist')
            <div class="flea-market__tab-content">
                <div class="flea-market__flex-box">
                    @foreach ($items as $item)
                        <div class="item-preview">
                            <a class="item-link" href="/item/{{ $item->id }}">
                                <img class="item-image" id="item-image" src="{{ asset('storage/' . $item->image) }}"
                                    alt="商品画像">
                                <p class="item-name">{{ $item->name }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 以下 recommendタブ表示 --}}
        @else
            <div class="flea-market__tab-content">
                <div class="flea-market__flex-box">
                    @foreach ($items as $item)
                        <div class="item-preview">
                            <a class="item-link" href="/item/{{ $item->id }}">
                                <img class="item-image" id="item-image" src="{{ asset('storage/' . $item->image) }}"
                                    alt="商品画像">
                                <p class="item-name">{{ $item->name }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
