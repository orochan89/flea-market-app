@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@include('components.nav')

@section('content')
    <div class="profile__container">
        <form class="profile-form" action="{{ route('changeProfile') }}" method="get">
            @csrf
            <div class="profile">
                <img class="profile-image"
                    src="{{ $profile->image ? asset('storage/' . $profile->image) : asset('images/default-profile.png') }}"
                    alt="user-icon">
                <h1 class="profile-user-name">{{ $user->name }}</h1>
                <input class="profile-user-name" type="hidden" value="{{ $user->name }}" name="name" readonly>
                <button class="profile-edit-button" type="submit">プロフィールを編集</button>
            </div>
        </form>
        <div class="item__container">
            <div class="item__container-tabs">
                <a class="tab {{ request('page') === 'sell' ? 'active' : '' }}"
                    href="{{ url()->current() . '?' . http_build_query(['page' => 'sell', 'search' => request('search')]) }}">
                    出品した商品
                </a>
                <a class="tab {{ request('page') === 'buy' ? 'active' : '' }}"
                    href="{{ url()->current() . '?' . http_build_query(['page' => 'buy', 'search' => request('search')]) }}">
                    購入した商品
                </a>
            </div>
        </div>
        @if ($page === 'buy')
            <div class="flea-market__tab-content">
                <div class="flea-market__flex-box">
                    @foreach ($items as $item)
                        <div class="item-preview">
                            <a class="item-link" href="/item/{{ $item->id }}">
                                <div class="item-image-container">
                                    <img class="item-image" id="item-image" src="{{ asset('storage/' . $item->image) }}"
                                        alt="商品画像">
                                    @if ($item->is_sold == 1)
                                        <div class="sold-label">SOLD</div>
                                    @endif
                                </div>
                                <p class="item-name">{{ $item->name }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif ($page === 'sell')
            <div class="flea-market__tab-content">
                <div class="flea-market__flex-box">
                    @foreach ($items as $item)
                        <div class="item-preview">
                            <a class="item-link" href="/item/{{ $item->id }}">
                                <div class="item-image-container">
                                    <img class="item-image" id="item-image" src="{{ asset('storage/' . $item->image) }}"
                                        alt="商品画像">
                                    @if ($item->is_sold == 1)
                                        <div class="sold-label">SOLD</div>
                                    @endif
                                </div>
                                <p class="item-name">{{ $item->name }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
