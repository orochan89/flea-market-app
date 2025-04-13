@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/change_address.css') }}">
@endsection


@include('components.nav')

@section('content')
    <div class="change-address__container">
        <h1 class="change-address--title">
            住所の変更
        </h1>
        <form class="change-address-form" action="" method="post">
            @csrf
            <div class="change-address">
                <label class="form-title" for="postcode">郵便番号</label>
                <input class="form-input" type="text" name="postcode" id="postcode">
            </div>
            @error('postcode')
                <div class="alert">{{ $message }}</div>
            @enderror
            <div class="change-address">
                <label class="form-title" for="address">住所</label>
                <input class="form-input" type="text" name="address" id="address">
            </div>
            @error('address')
                <div class="alert">{{ $message }}</div>
            @enderror
            <div class="change-address">
                <label class="form-title" for="building">建物名</label>
                <input class="form-input" type="text" name="building" id="building">
            </div>
            @error('building')
                <div class="alert">{{ $message }}</div>
            @enderror
            <div class="change-address-form-input__button">
                <button class="change-address-form-input__button--submit" type="submit">更新する</button>
            </div>
        </form>
    </div>
@endsection
