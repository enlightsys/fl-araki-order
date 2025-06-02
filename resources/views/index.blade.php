@extends('layouts.app')

@section('content')
      <section class="jumbotron text-center">
        <div class="container">
          <h3>荒木生花店ご注文サイト</h3>
        </div>
      </section>
      <div class="album py-5 bg-light">
        <div class="container">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif

          @foreach ($categories as $category)
          <div class="row">
            <div class="col-md-12">
              <h4>{{ $category->category }}</h4>
            </div>
          </div>

            @foreach ($category->sub_category as $sub_category)
              @if ($sub_category->products->count() > 0)
          <div class="row">
            <div class="col-md-12">
              <h5>{{ $sub_category->sub_category }}</h5>
            </div>
          </div>
          <div class="row">
              @php $cnt = 0; @endphp
              @foreach ($sub_category->products as $product)
                @if ($cnt < 4)
            <div class="col-md-3">
              <div class="card mb-4 shadow-sm">
                <a href="{{ route('detail', $product->id)}}">
                  <img src="/products/image?p={{ $product->image1 }}" alt="{{ $product->name }}" class="card-img-top" />
                  <div class="card-body">
                    <p class="card-text mb-0">{{ $product->name }}</p>
                    <small class="text-muted">{{ number_format($product->price) }} 円</small>
                  </div>
                </a>
              </div>
            </div>
                @endif
                @php $cnt++; @endphp
              @endforeach
              @if ($cnt > 4)
            <div class="col-md-12">
              <p class="text-right"><a href="/list?sub_category_id={{ $sub_category->id }}">&gt;&gt; もっと見る</a></p>
            </div>
              @endif
          </div>
            @endif
            @endforeach
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
    padding: 2rem;
    background: url('/assets/images/bg_title.png');
    color: #fff;
    text-shadow: 0 0 30px #000;
  }
</style>
@stop