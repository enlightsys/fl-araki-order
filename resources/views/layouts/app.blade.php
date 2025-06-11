<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="/assets/style.css" />
  @yield('css')
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="/">{{ config('app.name', 'Laravel') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      
      <ul class="navbar-nav mr-auto">
        @foreach ($categories as $id => $category)
        <li class="nav-item @if ($category->sub_category) dropdown @endif">
          <a class="nav-link @if ($category->sub_category) dropdown-toggle @endif" href="list?category_id={{ $id }}" @if ($category->sub_category) data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" @endif>{{ $category->category }}</a>

          @if ($category->sub_category)
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            @foreach ($category->sub_category as $sub_category)
            <a class="dropdown-item" href="/list?sub_category_id={{ $sub_category->id }}">{{ $sub_category->sub_category }}</a>
            @endforeach
          </div>
          @endif
        </li>
        @endforeach
      </ul>

      <ul class="navbar-nav">

        <form class="form-inline my-2 my-md-0 mr-1" action="/list" method="get">
          <input class="form-control searchbox" type="text" placeholder="検索" name="freeword" value="{{ $freeword ?? '' }}" />
        </form>
        <!-- Authentication Links -->
        @guest
        @if (Route::has('login'))
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="{{ route('login') }}">ログイン</a>
        </li>
        @endif
        @if (Route::has('register'))
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="{{ route('register') }}">会員登録</a>
        </li>
        @endif
        @else
        <li class="nav-item dropdown text-nowrap">
          <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{ Auth::user()->name }} 様
          </a>

          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ route('mypage') }}">会員ページ</a>
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>
            <form id="logout-form" action="{{ route('logout') }}" method="post" class="d-none">
              @csrf
            </form>
          </div>
        </li>
        @endguest
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="{{ route('cart') }}">カート</a>
        </li>
      </ul>
    </div>
  </nav>

<main role="main">
  @yield('content')
</main>

<footer class="footer bg-dark">
  <div class="container text-center text-muted">
    <ul class="list-inline">
      <li class="list-inline-item"><a href="/guide">ご利用ガイド</a></li>
      <li class="list-inline-item"><a href="/term">特定商取引法に基づく表記</a></li>
      <li class="list-inline-item"><a href="/cancel_policy">キャンセルポリシー</a></li>
      <li class="list-inline-item"><a href="/privacy">プライバシーポリシー</a></li>
      <li class="list-inline-item"><a href="/contact">お問い合わせ</a></li>
    </ul>
    <p class="">© 2025 荒木生花店</p>
  </div>
</footer>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
@yield('js')
</body>
</html>
