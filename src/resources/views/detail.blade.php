@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@include('components.nav')

@section('content')
    <div class="detail-container">
        <div class="item-image-area">
            <img class="item-image" src="" alt="">
        </div>
        <div class="item-detail-area">
            <div class="item-detail-container">
                <h2 class="item-detail-name">{{ $item->name }}</h2>
                <p class="item-detail-brand">{{ $item->brand }}</p>
                <h3 class="item-detail-price">{{ $item->price }}</h3>
            </div>
            <div>
                <form class="likes-form" action="">
                    {{-- <button onclick="like({{}})"></button> --}}
                </form>
                <form class="comment-form" action="">
                    {{-- <button onclick="comment({{}})"></button> --}}
                </form>
            </div>
            <div class="purchase__button">
                <form class="purchase-form" action="">
                    <button class="purchase-form__button--submit" type="submit">購入手続きへ</button>
                </form>
            </div>
            <div class="item-detail">
                <h3 class="item-detail--title">商品説明</h3>
                <p class="item-detail--content">{{ $item->detail }}</p>
            </div>
            <div class="item-info">
                <p class="item-info--category">
                    カテゴリー
                    @foreach ($item->categories as $category)
                        <li>
                            {{ $item->category }}
                        </li>
                    @endforeach
                </p>
                <p class="item-info--condition">
                    商品の状態 {{ $item->condition }}
                </p>
            </div>
            <div class="item-comment-container">
                <div class="item-comment--title">
                    コメント({{ $item->comment->count() }})
                </div>
                <div class="item-comment--content">
                    @foreach ($item->comments as $comment)
                        <div class="item-comment--user">{{ $comment->user->name }}
                        </div>
                        <div class="item-comment--talk">
                            {{ $comment->comment }}
                        </div>
                    @endforeach
                    <form class="item-comment-form" action="">
                        @csrf
                        <h3 class="item-comment-form--title">商品へのコメント</h3>
                        <textarea class="item-comment-form--content" name="comment" id="comment" cols="30" rows="50"></textarea>
                        <button class="item-comment-form__button" type="submit">コメントを送信する</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
