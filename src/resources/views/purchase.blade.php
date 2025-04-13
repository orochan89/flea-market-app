@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@include('components.nav')

@section('content')
    <form action="" method="post">
        @csrf
        <div class="purchase-container">
            <div class="item-container">
                <div class="item-detail">
                    <img class="item-image" src="{{ asset('storage/' . $item->image) }}" alt="item_image">
                    <div class="item-detail-text">
                        <h2 class="item-name">{{ $item->name }}</h2>
                        <h3 class="item-price">{{ number_format($item->price) }}</h3>
                    </div>
                </div>
                <div class="item-payment">
                    <h3 class="item-payment--title">支払い方法</h3>
                    <select class="item-payment--select" name="payment" id="payment">
                        <option class="item-payment--option" value="">選択してください</option>
                        <option class="item-payment--option" value="0">コンビニ払い</option>
                        <option class="item-payment--option" value="1">カード払い</option>
                    </select>
                </div>
                <div class="mailing-address">
                    <h3 class="mailing-address--title">配送先</h3>
                    <a class="mailing-address--edit" href="{{ route('viewAddress', ['item' => $item->id]) }}">変更する</a>
                </div>
                <div class="mailing-address-content">
                    <input class="postcode-input" type="hidden" name="postcode" readonly>
                    <p class="postcode">〒{{ $postcode }}</p>
                    <input class="address-input" type="hidden" name="address" readonly>
                    <p class="address">{{ $address }}</p>
                    <input class="building-input" type="hidden" name="building" readonly>
                    <p class="building">{{ $building }}</p>
                </div>
            </div>
            <div class="payment-container">
                <table class="payment-table">
                    <tr class="payment-table--row">
                        <th class="payment-table--th">商品代金</th>
                        <td class="payment-table--td">¥ {{ number_format($item->price) }}</td>
                    </tr>
                    <tr class="payment-table--row">
                        <th class="payment-table--th">支払い方法</th>
                        <td class="payment-table--td">
                            @if (!empty($purchase->payment))
                                {{ $purchase->payment }}
                            @endif
                        </td>
                    </tr>
                </table>
                @if ($item->is_sold)
                    <button class="sold__button" disabled>SOLD</button>
                @else
                    <button class="purchase__button" type="submit">購入
                        する</button>
                @endif
            </div>
        </div>
    </form>
@endsection
