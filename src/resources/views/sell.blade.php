@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

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
    <div class="sell-container">
        <h1 class="sell__container-title">
            商品の出品
        </h1>
        <form class="sell-form" action="" method="post">
            @csrf
            <div class="sel;-form-image">
                <label for="image">商品画像</label>
                <input class="item-form-image-input" type="file" id="image">
            </div>
            <div class="item-detail">
                <h3 class="item-detail-title">商品の詳細</h3>
            </div>
            <div class="item-detail-category">
                <p class="item-detail-category-title">カテゴリー</p>
                @foreach ($categories as $category)
                    <label class="category-label">
                        <input type="checkbox" name="categories" value="{{ $category }}">
                        {{ $category }}
                    </label>
                @endforeach
            </div>
            <div class="item-condition">
                <p class="item-condition-title">
                    商品の状態
                </p>
                <select class="item-description-select" name="condition">
                    <option class="item-description-option" value="best">良好</option>
                    <option class="item-description-option" value="better">目立った傷や汚れなし</option>
                    <option class="item-description-option" value="worse">やや傷や汚れあり</option>
                    <option class="item-description-option" value="worst">状態が悪い</option>
                </select>
            </div>
            <h3 class="item-description">商品名と説明</h3>
            <div class="item-description__wrap">
                <label class="item-description-label" for="item-name">商品名</label>
                <input class="item-description-input" type="text" id="item-name" name="name"
                    value="
                {{ old('name') }}">
            </div>
            <div class="item-description__wrap">
                <label class="item-description-label" for="item-name">ブランド名</label>
                <input class="item-description-input" type="text" id="item-name" name="brand"
                    value="{{ old('brand') }}">
            </div>
            <div class="item-description__wrap">
                <label class="item-description-label" for="item-detail">商品の説明</label>
                <textarea class="item-description-input" type="text" id="item-detail" name="detail">{{ old('detail') }}</textarea>
            </div>
            <div class="item-description__wrap">
                <label class="item-description-label" for="item-price">販売価格</label>
                <input class="item-description-input" type="integer" id="item-price" name="price"
                    value="{{ old('price') }}">
            </div>
        </form>
    </div>
@endsection
