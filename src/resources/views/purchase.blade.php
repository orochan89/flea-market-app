@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@include('components.nav')

@section('content')
    <div class="purchase-container">
        <div class="item-container">
            <div class="item-detail">
                <img class="item-image" src="{{ asset('storage/' . $item->image) }}" alt="item_image">
                <h2 class="item-name">{{ $item->name }}</h2>
                <h3 class="item-price">{{ number_format($item->price) }}</h3>
            </div>
            <div class="item-payment">
                <h3 class="item-payment--title">支払い方法</h3>
                <select class="item-payment--select" name="payment" id="payment">
                    <option class="item-payment--option" value="">選択してください</option>
                    @foreach ($payments as $payment)
                        <option class="item-payment--option" value="1">コンビニ払い</option>
                        <option class="item-payment--option" value="2">カード払い</option>
                    @endforeach
                </select>
            </div>
            <div class="shipping-address">
                <h3 class="shipping-address--title">配送先</h3>
                <button class="shipping-address--edit">変更する</button>
            </div>
            <div class="shipping-address-content">
                <input class="postcode-input" type="hidden" name="postcode" readonly>
                <p class="postcode">{{ $profile->postcode }}</p>
                <input class="address-input" type="hidden" name="address" readonly>
                <p class="address">{{ $profile->address }}</p>
                <input class="building-input" type="hidden" name="building" readonly>
                <p class="building">{{ $profile->building }}</p>
            </div>
        </div>
        <div class="payment-container">
            <table class="payment-table">
                <tr class="payment-table--row">
                    <th class="payment-table--th">商品代金</th>
                    <td class="payment-table--td">{{ $item->price }}</td>
                </tr>
                <tr class="payment-table--row">
                    <th class="payment-table--th">支払い方法</th>
                    <td class="payment-table--td">{{ $purchase->payment }}</td>
                </tr>
            </table>
            <button class="purchase__button">購入する</button>
        </div>
    </div>
@endsection
