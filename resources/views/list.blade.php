@extends('layouts.app')

@section('content')
    <div class="container py-4">
      @if (session('status'))
      <div class="alert alert-success" role="alert">
        {{ session('status') }}
      </div>
      @endif
      @php $cat = config('const.category'); @endphp
      <h4 class="mb-4">{{ $cat[$category_id] ?? '' }}の商品</h4>
      <div class="row">
      @if (count($products))
      @foreach ($products as $product)
        <div class="col-md-4">
          <div class="card mb-4 shadow-sm">
            <a href="{{ route('detail', $product->id)}}">
              <img src="https://flower-araki.jp/data/images/{{ $product->image1 }}" alt="{{ $product->name }}" class="card-img-top" />
              <div class="card-body">
                <p class="card-text">{{ $product->name }}</p>
                <small class="text-muted">{{ number_format($product->price) }} 円</small>
              </div>
            </a>
          </div>
        </div>
      @endforeach
      @else
        <div class="col-md-4">
          現在、商品は登録されていません。
        </div>
      @endif

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