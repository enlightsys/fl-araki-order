@extends('layouts.app')

@section('content')
  <div class="container">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
      {{ session('status') }}
    </div>
    @endif
    <div class="card py-5 mt-4">
      <div class="row">
        <aside class="col-sm-5 border-right">
          <article class="gallery-wrap"> 
            <div class="img-big-wrap">
              <div> <a href="#"><img src="https://flower-araki.jp/data/images/{{ $product->image1 }}" alt="{{ $product->name }}" /></a></div>
            </div> <!-- slider-product.// -->
            <div class="img-small-wrap">
              <div class="item-gallery"> <img src="https://flower-araki.jp/data/images/{{ $product->image1 }}" alt="{{ $product->name }}" /> </div>
              @if ($product->image2)
              <div class="item-gallery"> <img src="https://flower-araki.jp/data/images/{{ $product->image2 }}" alt="{{ $product->name }}" /> </div>
              @endif
              @if ($product->image3)
              <div class="item-gallery"> <img src="https://flower-araki.jp/data/images/{{ $product->image3 }}" alt="{{ $product->name }}" /> </div>
              @endif
              @if ($product->image4)
              <div class="item-gallery"> <img src="https://flower-araki.jp/data/images/{{ $product->image4 }}" alt="{{ $product->name }}" /> </div>
              @endif
            </div> <!-- slider-nav.// -->
          </article> <!-- gallery-wrap .end// -->
        </aside>
        <aside class="col-sm-7">
          <article class="card-body p-5">
            <form class="" action="{{ route('put_in')}}" method="POST">
              @csrf
              <input type="hidden" name="id" value="{{ $product->id }}" />
              <h3 class="title mb-3">{{ $product->name }}</h3>

              <p class="price-detail-wrap"> 
                <span class="price h3 text-price"> 
                  <span class="num">{{ number_format($product->price) }}</span><span class="currency">円</span>
                </span> 
              </p> <!-- price-detail-wrap .// -->
              @if ($product->note)
              <dl class="item-property">
                <dt>この商品について</dt>
                <dd><p>{!! nl2br(e($product->note)) !!}</p></dd>
              </dl>
              @endif
              <dl class="param param-feature">
                <dt>商品コード</dt>
                <dd>{{ sprintf("%08d", $product->id) }}</dd>
              </dl>  <!-- item-property-hor .// -->

              <hr>
              <div class="row">
                <div class="col-sm-5">
                  <dl class="param param-inline">
                    <dt>数量: </dt>
                    <dd>
                      <select class="form-control form-control-sm" style="width:70px;" name="quantity">
                        <option> 1 </option>
                        <option> 2 </option>
                        <option> 3 </option>
                      </select>
                    </dd>
                  </dl>  <!-- item-property .// -->
                </div> <!-- col.// -->
                {{--
                <div class="col-sm-7">
                  <dl class="param param-inline">
                    <dt>Size: </dt>
                    <dd>
                      <label class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                        <span class="form-check-label">SM</span>
                      </label>
                      <label class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                        <span class="form-check-label">MD</span>
                      </label>
                      <label class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                        <span class="form-check-label">XXL</span>
                      </label>
                    </dd>
                  </dl>  <!-- item-property .// -->
                </div> <!-- col.// -->
                --}}
              </div> <!-- row.// -->
              <hr>
              <button class="btn btn-lg btn-info"> <i class="fas fa-shopping-cart"></i> カートに入れる </button>
            </article> <!-- card-body.// -->
          </form>
        </aside> <!-- col.// -->
      </div> <!-- row.// -->
    </div> <!-- card.// -->
  </div>

@endsection


@section('css')
<style type="text/css">  
.gallery-wrap .img-big-wrap img {
  height: 450px;
  width: 100%;
  display: inline-block;
  cursor: zoom-in;
  object-fit: contain;
}

.gallery-wrap .img-small-wrap .item-gallery {
  width: 60px;
  height: 60px;
  border: 1px solid #ddd;
  margin: 7px 2px;
  display: inline-block;
  overflow: hidden;
}

.gallery-wrap .img-small-wrap {
  text-align: center;
}
.gallery-wrap .img-small-wrap img {
  max-width: 100%;
  max-height: 100%;
  object-fit: cover;
  border-radius: 4px;
  cursor: zoom-in;
}
.text-price {
  color: #007bff;
}
</style>
@stop

@section('js')
<script type="text/javascript">
</script>
@stop
