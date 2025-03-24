<header class="header">
    <div class="header__inner">
        <div class="header-utilities">
            <a class="header__logo" href="/">
                <img class="header__logo__image" src="{{ asset('materials/logo.svg') }}" alt="COACHTECH">
            </a>
            <div class="search-nav">
                <form class="search-form" action="search" method="get">
                    <input class="search-form-input" type="text" placeholder="なにをお探しですか？" name="search">
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
                    <button class="header-nav__mypage__button" type="submit">マイページ</button>
                </form>
                <form class="header-nav__sell" action="{{ route('sell') }}" method="get">
                    <button class="header-nav__sell__button" type="submit">出品</button>
                </form>
            </div>
        </div>
    </div>
</header>
