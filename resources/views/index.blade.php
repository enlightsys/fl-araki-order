@extends('layouts.app')

@section('content')
      <section class="jumbotron text-center">
        <div class="container">
          <p><strong>🌸【お知らせ】🌸</strong></p>
          <h3>🌸 期間限定販売 🌸 「桃のもり花」「桃のお生花」ご予約受付開始</h3>
          <p>春の訪れを感じる桃の花を、ご自宅や贈り物にいかがでしょうか？<br>
          毎年ご好評いただいている <strong>「桃のもり花」</strong> と <strong>「桃のお生花」</strong> の販売を、<strong>2月18日お届け分より開始</strong> いたします。</p>
          <p>🌿 <strong>桃のもり花</strong>（税込 <strong>3,080円</strong>）</p>
          <p>🌸 <strong>桃のお生花</strong>（税込 <strong>3,850円</strong>）</p>
          <p>ひな祭りのお飾りや、お部屋を春らしく彩る一品としてぜひご利用ください。<br>
          数量限定のため、お早めにご予約をおすすめいたします。</p>
          <p>📅 <strong>お届け開始日</strong>：2月18日～<br>
          📍 <strong>ご注文・お問い合わせ</strong>：<a href="https://flower-araki.jp/okeiko/">お稽古花注文サイト</a>よりお願いします。</p>
        </div>
      </section>
      <div class="album py-5 bg-light">
        <div class="container">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif

          <div class="row">
          @foreach ($products as $product)
            <div class="col-md-4">
              <div class="card mb-4 shadow-sm">
                <a href="{{ route('detail', $product->id)}}">
                  <img src="https://flower-araki.jp/data/images/{{ $product->image1 }}" alt="{{ $product->name }}" class="card-img-top" />
                  <div class="card-body">
                    <p class="card-text mb-0">{{ $product->name }}</p>
                    <small class="text-muted">{{ number_format($product->price) }} 円</small>
                  </div>
                </a>
              </div>
            </div>
          @endforeach

          </div>
        </div>
      </div>

@endsection

@section('css')
<style type="text/css">
  .jumbotron {
    background: #dce9d2;
    margin-bottom: 0;
  }
</style>
@stop