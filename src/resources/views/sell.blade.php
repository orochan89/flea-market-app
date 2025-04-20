@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@include('components.nav')

@section('content')
    <div class="sell__container">
        <h1 class="sell__container-title">
            商品の出品
        </h1>
        <form class="sell-form" action="" method="post" enctype="multipart/form-data">
            @csrf
            <h2 class="sell-form-image-title">商品画像</h2>
            <div class="sell-form-image">
                <img class="image-preview" id="image-preview" src="" alt="プレビュー画像"
                    style="display: none; margin-top: 10px; max-width: 300px;">
                <label class="sell-form-image-label" for="image">画像を選択する</label>
                <input class="sell-form-image-input" type="file" id="image" name="image"
                    value="{{ old('image') }}" hidden>
            </div>
            @error('image')
                <div class="alert">{{ $message }}</div>
            @enderror
            <div class="item-detail">
                <h2 class="item-detail-title">商品の詳細</h2>
            </div>
            <p class="item-detail-category-title">カテゴリー</p>
            <div class="item-detail-category">
                @foreach ($categories as $category)
                    <input type="checkbox" name="categories[]" id="{{ $category->category }}" value="{{ $category->id }}"
                        hidden>
                    <label class="category-label" for="{{ $category->category }}">
                        {{ $category->category }}
                    </label>
                @endforeach
            </div>
            @error('categories')
                <div class="alert">{{ $message }}</div>
            @enderror
            <div class="item-condition">
                <p class="item-condition-title">
                    商品の状態
                </p>
                <select class="item-description-select" name="condition">
                    <option class="item-description-option" value="0" {{ old('condition') == 0 ? 'selected' : '' }}>良好
                    </option>
                    <option class="item-description-option" value="1" {{ old('condition') == 1 ? 'selected' : '' }}>
                        目立った傷や汚れなし</option>
                    <option class="item-description-option" value="2" {{ old('condition') == 2 ? 'selected' : '' }}>
                        やや傷や汚れあり</option>
                    <option class="item-description-option" value="3" {{ old('condition') == 3 ? 'selected' : '' }}>
                        状態が悪い</option>
                </select>
            </div>
            @error('condition')
                <div class="alert">{{ $message }}</div>
            @enderror
            <h2 class="item-description">商品名と説明</h2>
            <div class="item-description__wrap">
                <label class="item-description-label" for="item-name">商品名</label>
                <input class="item-description-input" type="text" id="item-name" name="name"
                    value="{{ old('name') }}">
            </div>
            @error('name')
                <div class="alert">{{ $message }}</div>
            @enderror
            <div class="item-description__wrap">
                <label class="item-description-label" for="item-name">ブランド名</label>
                <input class="item-description-input" type="text" id="item-name" name="brand"
                    value="{{ old('brand') }}">
            </div>
            <div class="item-description__wrap">
                <label class="item-description-label" for="item-detail">商品の説明</label>
                <textarea class="item-description-textarea" type="text" id="item-detail" name="detail">{{ old('detail') }}</textarea>
            </div>
            @error('detail')
                <div class="alert">{{ $message }}</div>
            @enderror
            <div class="item-description__wrap">
                <label class="item-description-label" for="item-price">販売価格</label>
                <input class="item-description-input-price" type="integer" id="item-price" name="price"
                    value="{{ old('price') }}" placeholder="¥">
            </div>
            @error('price')
                <div class="alert">{{ $message }}</div>
            @enderror
            <button class="sell-form__button--submit" type="submit">
                出品する
            </button>
        </form>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('image');
        const preview = document.getElementById('image-preview');

        if (!input || !preview) return;

        input.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
