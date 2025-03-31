@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
@endsection

@include('components.nav')

@section('content')
    <div class="detail-container">
        <div class="item-image-area">
            <img class="item-image" src="{{ asset('storage/' . $item->image) }}" alt="item_image">
        </div>
        <div class="item-detail-area">
            <div class="item-detail-container">
                <h2 class="item-detail-name">{{ $item->name }}</h2>
                <p class="item-detail-brand">{{ $item->brand }}</p>
                <h3 class="item-detail-price">{{ number_format($item->price) }}</h3>
            </div>
            <div>
                <form class="likes-form" action="{{ route('like.toggle', ['item' => $item->id]) }}" method="post">
                    @csrf
                    <button type="submit" class="like-btn" style="font-size: 24px; background: none; border: none;">
                        @if ($userLike)
                            <i class="fas fa-heart" style="color: red;"></i>
                        @else
                            <i class="far fa-heart" style="color: red;"></i>
                        @endif

                        <div>
                            {{ $item->likes->count() }}
                        </div>
                    </button>
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
                            {{ $category->category }}
                        </li>
                    @endforeach
                </p>
                <p class="item-info--condition">
                    商品の状態 {{ $item->condition }}
                </p>
            </div>
            <div class="item-comment-container">
                <div class="item-comment--title">
                    コメント({{ optional($item->comments)->count() ?? 0 }})
                </div>
                <div class="item-comment--content">
                    @foreach ($item->comments as $comment)
                        <div class="item-comment--user">
                            <img class="item-comment--user-image"
                                src="{{ asset('storage/' . $comment->user->profile->image) }}" alt="user-icon">
                            <div class="item-comment--user-name">
                                {{ $comment->user->name }}
                            </div>
                        </div>
                        <div class="item-comment--talk">
                            {{ $comment->comment }}
                        </div>
                    @endforeach
                    <form class="item-comment-form" action="{{ route('comment', ['item' => $item->id]) }}" method="post">
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
