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
  <div class="container">
    <a class="navbar-brand " href="/">{{ config('app.name', 'Laravel') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      
      <ul class="navbar-nav mr-auto">
        @foreach ($categories as $id => $value)
        <li class="nav-item">
          <a class="nav-link" href="list?category_id={{ $id }}">{{ $value }}</a>
        </li>
        @endforeach
      </ul>

      <ul class="navbar-nav">
        <!-- Authentication Links -->
        @guest
        @if (Route::has('login'))
        <li class="nav-item">
          <a class="nav-link" href="{{ route('login') }}">ログイン</a>
        </li>
        @endif
        @if (Route::has('register'))
        <li class="nav-item">
          <a class="nav-link" href="{{ route('register') }}">会員登録</a>
        </li>
        @endif
        @else
        <li class="nav-item dropdown">
          <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
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
        <li class="nav-item">
          <a class="nav-link" href="{{ route('cart') }}">カート</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<main role="main">
  @yield('content')
</main>
<footer class="footer">
  <div class="container text-center text-muted">
    <p class="float-left">© 2025 荒木生花店</p>
    <ul class="list-inline">
      <li class="list-inline-item"><a href="/term">特定商取引法に基づく表記</a></li>
      <li class="list-inline-item"><a href="/cancel_policy">キャンセルポリシー</a></li>
      <li class="list-inline-item"><a href="/privacy">プライバシーポリシー</a></li>
      <li class="list-inline-item"><a href="/contact">お問い合わせ</a></li>
    </ul>
  </div>
</footer>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
@yield('js')
</body>
</html>
