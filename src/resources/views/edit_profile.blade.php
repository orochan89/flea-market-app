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
        <form class="change-profile-form" action="" method="post">
            @csrf
            <div class="change-profile-image">
                <img class="profile-icon" src="" alt="profile-icon">
                <button class="profile-icon-edit" type="submit">画像を選択する</button>
            </div>
            <div class="change-profile-form-input">
                <label class="change-profile-form-input-label" for="name">ユーザー名</label>
                <input class="change-profile-form-input-text" type="text" id="name">
            </div>
            <div class="change-profile-form-input">
                <label class="change-profile-form-input-label" for="post-code">郵便番号</label>
                <input class="change-profile-form-input-text" type="text" id="post-code">
            </div>
            <div class="change-profile-form-input">
                <label class="change-profile-form-input-label" for="address">住所</label>
                <input class="change-profile-form-input-text" type="text" id="address">
            </div>
            <div class="change-profile-form-input">
                <label class="change-profile-form-input-label" for="building">建物名</label>
                <input class="change-profile-form-input-text" type="text" id="building">
            </div>
            <div class="change-profile-form-input__button">
                <button class="change-profile-form-input__button--submit" type="submit">登録する</button>
            </div>
        </form>
    </div>
@endsection
