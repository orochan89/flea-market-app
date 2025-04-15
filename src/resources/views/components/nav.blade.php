<header class="header">
    <div class="header__inner">
        <div class="header-utilities">
            <a class="header__logo"
                href="{{ url('/') . '?' . http_build_query(['tab' => request('tab'), 'search' => request('search')]) }}">
                <img class="header__logo__image" src="{{ asset('materials/logo.svg') }}" alt="COACHTECH">
            </a>
            <div class="search-nav">
                <form class="search-form" action="{{ url()->current() }}" method="get">
                    <input type="hidden" name="tab" value="{{ request('tab') }}">
                    <input class="search-form-input" type="text" placeholder="なにをお探しですか？" name="search"
                        value="{{ old('search', request('search')) }}">
                </form>
            </div>
            <div class="header-nav-container">
                @auth
                    <form class="header-nav__logout" action="{{ route('logout') }}" method="post">
                        @csrf
                        <button class="header-nav__logout__button" type="submit">ログアウト</button>
                    </form>
                @endauth
                @guest
                    <form class="header-nav__login" action="{{ route('login') }}" method="get">
                        <button class="header-nav__login__button" type="submit">ログイン</button>
                    </form>
                @endguest
                <form class="header-nav__mypage" action="{{ route('mypage') }}" method="get">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="tab" value="sell">
                    <button class="header-nav__mypage__button" type="submit">マイページ</button>
                </form>
                <form class="header-nav__sell" action="{{ route('sell') }}" method="get">
                    <button class="header-nav__sell__button" type="submit">出品</button>
                </form>
            </div>
        </div>
    </div>
</header>
