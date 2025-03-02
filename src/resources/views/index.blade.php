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
                <button class="header-nav__login__button" type="submit">ログイン</button>
            </form>
        </li>
    @endguest
    <form class="header-nav__mypage" action="{{ route('profile') }}" method="get">
        <button class="header-nav__mypage__button" type="submit">マイページ</button>
    </form>
    <form class="header-nav__sell" action="{{ route('sell') }}" method="get">
        <button class="header-nav__sell__button" type="submit">出品</button>
    </form>
@endsection

@section('content')
    <div class="flea-market__container">
        <div class="flea-market__container-tabs">
            <a class="tab" href="{{ url('/') }}">おすすめ</a>
            <a class="tab" href="{{ request()->fullUrlWithQuery(['tab' => 'mylist']) }}">マイリスト</a>
        </div>
    </div>
    {{-- 以下 mylistタブ表示 --}}
    @if ($tab === 'mylist')
        <div class="flea-market__tab-content">
            @foreach ($items as $item)
                <div>
                    <img class="item-image" id="item-image" src="{{ asset('storage/items/' . $item->image) }}"
                        alt="商品画像">
                    <label class="item-label" for="item-image">
                        {{ $item->name }}
                    </label>
                </div>
            @endforeach

            {{-- 以下 recommendタブ表示 --}}
        @else
        </div>
        <div class="flea-market__tab-content" id="recommend">
            @foreach ($items as $item)
                <div>
                    <img class="item-image" id="item-image" src="{{ asset('storage/items/' . $item->image) }}"
                        alt="商品画像">
                    <label class="item-label" for="item-image">
                        {{ $item->name }}
                    </label>
                </div>
            @endforeach
        </div>
    @endif
@endsection
