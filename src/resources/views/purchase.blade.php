@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@include('components.nav')

@section('content')
    <form action="{{ route('purchase', ['item' => $item->id]) }}" method="post">
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
                @php
                    $payment = old('payment');
                @endphp
                <div class="item-payment">
                    <h3 class="item-payment--title">支払い方法</h3>
                    <select class="item-payment--select" name="payment" id="payment">
                        <option value="">選択してください</option>
                        <option value="0" {{ old('payment') === '0' ? 'selected' : '' }}>コンビニ払い</option>
                        <option value="1" {{ old('payment') === '1' ? 'selected' : '' }}>カード払い</option>
                    </select>
                    @error('payment')
                        <div class="alert">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mailing-address">
                    <h3 class="mailing-address--title">配送先</h3>
                    <a class="mailing-address--edit" href="{{ route('viewAddress', ['item' => $item->id]) }}">変更する</a>
                </div>
                <div class="mailing-address-content">
                    <input class="postcode-input" type="hidden" name="postcode" value="{{ $postcode }}" readonly>
                    <p class="postcode">〒{{ $postcode }}</p>
                    <input class="address-input" type="hidden" name="address" value="{{ $address }}" readonly>
                    <p class="address">{{ $address }}</p>
                    <input class="building-input" type="hidden" name="building" value="{{ $building }}" readonly>
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
                            <span id="selected-payment-method">
                                @switch($payment)
                                    @case('0')
                                        コンビニ払い
                                    @break

                                    @case('1')
                                        カード払い
                                    @break

                                    @default
                                        未選択
                                @endswitch
                            </span>
                        </td>
                    </tr>
                </table>
                @if ($item->is_sold || $item->user_id === auth()->id())
                    <button class="sold__button" disabled>
                        {{ $item->is_sold ? 'SOLD' : '出品者の為、購入できません' }}
                    </button>
                @else
                    <button class="purchase__button" type="submit">購入
                        する</button>
                @endif
            </div>
        </div>
    </form>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentSelect = document.getElementById('payment');
        const displaySpan = document.getElementById('selected-payment-method');

        const paymentLabels = {
            '0': 'コンビニ払い',
            '1': 'カード払い',
            '': '未選択'
        };

        paymentSelect.addEventListener('change', function() {
            const selectedValue = this.value;
            displaySpan.textContent = paymentLabels[selectedValue] || '不明な支払い方法';
        });
    });
</script>
