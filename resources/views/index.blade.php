@extends('layouts.app')

@section('content')

  {{-- ▼▼▼ 1. タイトルエリア (Jumbotron) ▼▼▼ --}}
  <section class="jumbotron">
    <div class="container">
      <h3>荒木生花店ご注文サイト</h3>
    </div>
  </section>

  {{-- ▼▼▼ 2. 3つのナビゲーション (トップページのみ上部に表示) ▼▼▼ --}}
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

  {{-- ▼▼▼ 3. バナーエリア ▼▼▼（こちら手動で書き換え・コメントアウト等してください） --}}
  {{-- 現在は2025年12月29日 00:00 を過ぎたら自動で非表示するようになってます --}}
  @if (now()->lt('2025-12-29 00:00:00'))
  <section class="banner-section">
    <div class="container">
      <a href="{{ route('list', ['sub_category_id' => 37]) }}" class="banner-wrapper">
        <img src="https://flower-araki.jp/wp/wp-content/themes/arakiNew/library/img/common/bnr_newyear.png" alt="お正月商品" class="banner-img">
      </a>
    </div>
  </section>
  @endif

  {{-- ▼▼▼ 4. 商品一覧ループエリア ▼▼▼ --}}
  <div class="album py-5">
    <div class="container">
      
      {{-- ステータスメッセージ --}}
      @if (session('status'))
      <div class="alert alert-success" role="alert">
        {{ session('status') }}
      </div>
      @endif

      {{-- 大カテゴリのループ --}}
      @foreach ($categories as $category)
        
        {{-- カテゴリ見出し --}}
        <div class="row">
          <div class="col-md-12">
            <h4>{{ $category->category }}</h4>
          </div>
        </div>

        {{-- サブカテゴリのループ --}}
        @foreach ($category->sub_category->sortBy('rank') as $sub_category)
        
          {{-- 商品が1つ以上ある場合のみ表示 --}}
          @if ($sub_category->products->count() > 0)
            
            <div class="row">
              <div class="col-md-12">
                <h5>{{ $sub_category->sub_category }}</h5>
              </div>
            </div>

            <div class="row">
              @php $cnt = 0; @endphp
              {{-- 商品ループ（最大4件まで） --}}
              @foreach ($sub_category->products as $product)
                @if ($cnt < 4)
                  <div class="col-md-3">
                    <div class="card mb-4 shadow-sm border-0 h-100">
                      <a href="{{ route('detail', $product->id)}}" class="text-dark text-decoration-none d-flex flex-column h-100">
                        <img src="/products/image?p={{ $product->image1 }}" alt="{{ $product->name }}" class="card-img-top" />
                        <div class="card-body flex-grow-1">
                          <p class="card-text mb-1 font-weight-bold">{{ $product->name }}</p>
                          <small class="text-muted">{{ number_format($product->price) }} 円</small>
                        </div>
                      </a>
                    </div>
                  </div>
                @endif
                @php $cnt++; @endphp
              @endforeach

              {{-- 4件以上ある場合の「もっと見る」ボタン --}}
              @if ($cnt > 4)
                <div class="col-12 text-right mt-4 mb-5">
                  <a href="{{ route('list', ['sub_category_id' => $sub_category->id]) }}" class="btn-more">
                    もっと見る <i class="fas fa-chevron-right ml-1"></i>
                  </a>
                </div>
              @else
                {{-- 商品数が少ない場合用調整 --}}
                <div class="col-12 mb-5"></div>
              @endif
            </div>

          @endif
        @endforeach
      @endforeach

    </div>
  </div>

@endsection