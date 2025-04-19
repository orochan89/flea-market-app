@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/change_profile.css') }}">
@endsection


@include('components.nav')

@section('content')
    <div class="change-profile__container">
        <h1 class="change-profile__container-title">
            プロフィール設定
        </h1>
        <form class="change-profile-form" action="{{ route('update') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="change-profile-image">
                <img class="profile-icon" id="profile-preview"
                    src="{{ $profile->image ? asset('storage/' . $profile->image) : asset('images/default-profile.png') }}"
                    alt="profile-icon">
                <label class="change-profile-form-image-label" for="image">画像を選択する</label>
                <input class="change-profile-form-image-input" type="file" id="image" name="image" hidden>
            </div>
            @error('image')
                <div class="alert">{{ $message }}</div>
            @enderror
            <div class="change-profile-form-input">
                <label class="change-profile-form-input-label" for="name">ユーザー名</label>
                <input class="change-profile-form-input-text" type="text" id="name" name="name"
                    value="{{ old('name', $user->name ?? '') }}">
            </div>
            @error('name')
                <div class="alert">{{ $message }}</div>
            @enderror
            <div class="change-profile-form-input">
                <label class="change-profile-form-input-label" for="post-code">郵便番号</label>
                <input class="change-profile-form-input-text" type="text" id="post-code" name="postcode"
                    value="{{ old('postcode', $profile->postcode ?? '') }}">
            </div>
            @error('postcode')
                <div class="alert">{{ $message }}</div>
            @enderror
            <div class="change-profile-form-input">
                <label class="change-profile-form-input-label" for="address">住所</label>
                <input class="change-profile-form-input-text" type="text" id="address" name="address"
                    value="{{ old('address', $profile->address ?? '') }}">
            </div>
            @error('address')
                <div class="alert">{{ $message }}</div>
            @enderror
            <div class="change-profile-form-input">
                <label class="change-profile-form-input-label" for="building">建物名</label>
                <input class="change-profile-form-input-text" type="text" id="building" name="building"
                    value="{{ old('building', $profile->building ?? '') }}">
            </div>
            @error('building')
                <div class="alert">{{ $message }}</div>
            @enderror
            <div class="change-profile-form-input__button">
                <button class="change-profile-form-input__button--submit" type="submit">登録する</button>
            </div>
        </form>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('image');
        const preview = document.getElementById('profile-preview');

        if (!input || !preview) {
            console.warn('imageまたはprofile-previewが見つかりません');
            return;
        }

        input.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                console.warn('画像ファイルが選択されていません');
            }
        });
    });
</script>
