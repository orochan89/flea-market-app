@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@include('components.nav', ['action' => route('home')])

@section('content')
    <div class="flea-market__container">
        <div class="flea-market__container-tabs">
            <a class="tab {{ request('tab') !== 'mylist' ? 'active' : '' }}"
                href="{{ url('/?' . http_build_query(['search' => request('search')])) }}">おすすめ</a>
            <a class="tab {{ request('tab') === 'mylist' ? 'active' : '' }}"
                href="{{ url('/?' . http_build_query(['tab' => 'mylist', 'search' => request('search')])) }}">マイリスト</a>
        </div>
        {{-- 以下 mylistタブ表示 --}}
        @if ($tab === 'mylist')
            <div class="flea-market__tab-content">
                <div class="flea-market__flex-box">
                    @foreach ($items as $item)
                        <div class="item-preview">
                            <a class="item-link" href="/item/{{ $item->id }}">
                                <div class="item-image-container">
                                    <img class="item-image" id="item-image" src="{{ asset('storage/' . $item->image) }}"
                                        alt="商品画像">
                                    @if ($item->is_sold)
                                        <div class="sold-label">SOLD</div>
                                    @endif
                                </div>
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
                                <div class="item-image-container">
                                    <img class="item-image" id="item-image" src="{{ asset('storage/' . $item->image) }}"
                                        alt="商品画像">
                                    @if ($item->is_sold)
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
