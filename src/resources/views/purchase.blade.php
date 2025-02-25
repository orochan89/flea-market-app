@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
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
    <div class="purchase-container">
        <div class="item-container">
            <div class="item-detail">
                <img class="item-image" src="" alt="">
                <h2 class="item-name">{{}}</h2>
                <h3 class="item-price">{{}}</h3>
            </div>
            <div class="item-payment">
                <h3 class="item-payment--title">支払い方法</h3>
                <select class="item-payment--select" name="payment" id="payment">
                    <option class="item-payment--option" value="">選択してください</option>
                    @foreach ($collection as $item)
                        <option class="item-payment--option" value="{{}}">コンビニ払い</option>
                        <option class="item-payment--option" value="{{}}">カード払い</option>
                    @endforeach
                </select>
            </div>
            <div class="shipping-address">
                <h3 class="shipping-address--title">配送先</h3>
                <button class="shipping-address--edit">変更する</button>
            </div>
            <div class="shipping-address-content">
                <input class="postcode-input" type="hidden" name="" readonly>
                <p class="postcode">{{}}</p>
                <input class="address-input" type="hidden" name="" readonly>
                <p class="address">{{}}</p>
            </div>
        </div>
        <div class="payment-container">
            <table class="payment-table">
                <tr class="payment-table--row">
                    <th class="payment-table--th">商品代金</th>
                    <td class="payment-table--td">{{}}</td>
                </tr>
                <tr class="payment-table--row">
                    <th class="payment-table--th">支払い方法</th>
                    <td class="payment-table--td">{{}}</td>
                </tr>
            </table>
            <button class="purchase__button">購入する</button>
        </div>
    </div>
@endsection
