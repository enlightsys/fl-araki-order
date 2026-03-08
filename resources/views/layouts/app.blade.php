<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', '荒木生花店') }}</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" />
  
  <link rel="stylesheet" href="/assets/style.css" />
  
  @yield('css')

</head>
<body>

  <header>
    <div class="header-top-utility d-none d-md-block">
      <div class="container text-right">
        <a href="{{ route('guide') }}"><i class="fas fa-book-open"></i> ご利用ガイド</a>
        <a href="{{ route('faq') }}"><i class="fas fa-question-circle"></i> よくある質問</a>
        <a href="{{ route('contact') }}"><i class="fas fa-envelope"></i> お問い合わせ</a>
      </div>
    </div>

    <div class="header-main">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-5 col-12 text-center text-md-left mb-3 mb-md-0">
            <a href="{{ url('/') }}" class="brand-title">
              {{ config('app.name', '荒木生花店') }} <span class="d-block d-sm-inline" style="font-size:0.6em; font-weight:normal; color:#666;">ONLINE SHOP</span>
            </a>
            <a href="https://flower-araki.jp" class="btn-official-site" target="_blank">
              <i class="fas fa-external-link-alt"></i> 荒木生花店公式サイトへ
            </a>
          </div>
          
          <div class="col-md-7 col-12 text-center text-md-right header-actions d-flex justify-content-center justify-content-md-end align-items-center">
            
            <form class="form-inline mr-3 d-none d-lg-flex search-wrap" action="{{ route('list') }}" method="get">
                <i class="fas fa-search search-icon"></i>
                <input class="form-control form-control-sm search-input-padding" type="text" placeholder="商品検索" name="freeword" value="{{ $freeword ?? '' }}" style="width: 150px;" />
            </form>

            @guest
                @if (Route::has('login'))
                <a href="{{ route('login') }}" class="btn btn-outline-custom btn-sm px-4">ログイン</a>
                @endif
                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn btn-custom btn-sm px-4">会員登録</a>
                @endif
            @else
                <a href="{{ route('logout') }}" 
                   class="btn btn-outline-custom btn-sm px-4"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                   ログアウト
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="post" class="d-none">
                    @csrf
                </form>

                <a href="{{ route('mypage') }}" class="btn btn-custom btn-sm px-4">マイページ</a>
            @endguest

            <a href="{{ route('cart') }}" class="cart-link" style="position: relative;">
              <i class="fas fa-shopping-cart cart-icon"></i>
              <span class="d-none d-md-inline ml-1" style="font-size:0.85rem; color:#444;">カート</span>
              
              @if(session()->has('cart') && count(session('cart')) > 0)
                <span class="cart-badge">{{ count(session('cart')) }}</span>
              @endif
            </a>
          </div>
        </div>
      </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark custom-navbar">
      <div class="container">
        <button class="navbar-toggler w-100 border-0" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span> MENU
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarNavDropdown">
          <ul class="navbar-nav">
            @foreach ($categories as $category)
            <li class="nav-item @if ($category->sub_category->count() > 0) dropdown @endif">
              {{-- 親カテゴリリンク --}}
              <a class="nav-link @if ($category->sub_category->count() > 0) dropdown-toggle @endif" 
                 href="{{ route('list', ['category_id' => $category->id]) }}" 
                 @if ($category->sub_category->count() > 0) data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" @endif>
                 {{ $category->category }}
              </a>

              {{-- 子カテゴリがある場合のみドロップダウン表示 --}}
              @if ($category->sub_category->count() > 0)
              <div class="dropdown-menu">
                @foreach ($category->sub_category->sortBy('rank') as $sub_category)
                <a class="dropdown-item" href="{{ route('list', ['sub_category_id' => $sub_category->id]) }}">
                    {{ $sub_category->sub_category }}
                </a>
                @endforeach
              </div>
              @endif
            </li>
            @endforeach
          </ul>
          
          <div class="d-lg-none p-3">
              <form class="form-inline search-wrap" action="{{ route('list') }}" method="get">
              <i class="fas fa-search search-icon"></i>
              <input class="form-control w-100 search-input-padding" type="text" placeholder="商品検索" name="freeword" value="{{ $freeword ?? '' }}" />
              </form>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <main role="main">
    @yield('content')
  </main>

  @if (!request()->is('/'))
  <section class="guide-nav-section">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <a href="{{ route('guide') }}" class="guide-box">
              <div class="guide-icon-circle"><i class="fas fa-book"></i></div>
              <div class="guide-text">
                <h5>ご利用ガイド</h5>
                <span>ご注文方法についてはこちら</span>
              </div>
            </a>
          </div>
          <div class="col-md-4">
            <a href="{{ route('faq') }}" class="guide-box">
              <div class="guide-icon-circle"><i class="fas fa-question"></i></div>
              <div class="guide-text">
                <h5>よくある質問</h5>
                <span>ご不明な点はこちら</span>
              </div>
            </a>
          </div>
          <div class="col-md-4">
            <a href="{{ route('contact') }}" class="guide-box">
              <div class="guide-icon-circle"><i class="fas fa-envelope"></i></div>
              <div class="guide-text">
                <h5>お問い合わせ</h5>
                <span>お気軽にご連絡ください</span>
              </div>
            </a>
          </div>
        </div>
      </div>
  </section>
  @endif

  <footer class="footer-custom">
    <div class="container">
      <div class="row">
        
        <div class="col-md-4">
          <h6>商品カテゴリ</h6>
          <ul>
            @foreach ($categories as $category)
            <li><a href="{{ route('list', ['category_id' => $category->id]) }}">{{ $category->category }}</a></li>
            @endforeach
          </ul>
        </div>

        <div class="col-md-4">
          <h6>ご利用案内</h6>
          <ul>
            <li><a href="{{ route('guide') }}">ご利用ガイド</a></li>
            <li><a href="{{ route('term') }}">特定商取引法に基づく表記</a></li>
            <li><a href="{{ route('cancel_policy') }}">キャンセルポリシー</a></li>
            <li><a href="{{ route('privacy') }}">プライバシーポリシー</a></li>
            <li><a href="{{ route('faq') }}">よくある質問</a></li>
            <li><a href="{{ route('contact') }}">お問い合わせ</a></li>
          </ul>
        </div>
        <div class="col-md-4">
          <h6>荒木生花店公式サイト</h6>
          <ul>
            <li><a href="https://flower-araki.jp/" target="_blank">公式サイトはこちら</a></li>
            <li><a href="https://liff.line.me/1645278921-kWRPP32q/?accountId=flower-araki1936" target="_blank">公式LINE</a></li>
            <li><a href="https://flower-araki.jp/company/" target="_blank">会社概要</a></li>
          </ul>
          <div class="mt-3">
             <p class="text-muted small">
               TEL.050-1720-3272（自動音声案内）
             </p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 text-center copyright">
          &copy; 2025 {{ config('app.name', '荒木生花店') }} ONLINE SHOP
        </div>
      </div>
    </div>
  </footer>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
@yield('js')
</body>
</html>