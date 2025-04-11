@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@include('components.nav')

@section('content')
    <div class="flea-market__container">
        <div class="flea-market__container-tabs">
            <a class="tab {{ request('page') !== 'mylist' ? 'active' : '' }}" href="{{ url('/') }}">おすすめ</a>
            <a class="tab {{ request('page') === 'mylist' ? 'active' : '' }}"
                href="{{ request()->fullUrlWithQuery(['page' => 'mylist']) }}">マイリスト</a>
        </div>
        {{-- 以下 mylistタブ表示 --}}
        @if ($page === 'mylist')
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
