@extends('layouts.app')

@section('content')
  <div class="container">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
      {{ session('status') }}
    </div>
    @endif
    <div class="card pb-4 mb-4 mt-4">
      <div class="row mb-2">
        <aside class="col-sm-5 border-right mb-2">
          <article class="gallery-wrap"> 
            <div class="img-big-wrap">
              <div> <a href="#" id="open-image"><img src="/products/image?p={{ $product->image1 }}&w=600" data-src="https://flower-araki.jp/data/images/{{ $product->image1 }}" alt="{{ $product->name }}" id="main-image" /></a></div>
            </div> <!-- slider-product.// -->
            <div class="img-small-wrap">
              <div class="item-gallery"> <img src="/products/image?p={{ $product->image1 }}&w=600" data-src="https://flower-araki.jp/data/images/{{ $product->image1 }}" alt="{{ $product->name }}" class="gallery-img" /> </div>
              @if ($product->image2)
              <div class="item-gallery"> <img src="/products/image?p={{ $product->image2 }}&w=600" data-src="https://flower-araki.jp/data/images/{{ $product->image2 }}" alt="{{ $product->name }}" class="gallery-img" /> </div>
              @endif
              @if ($product->image3)
              <div class="item-gallery"> <img src="/products/image?p={{ $product->image3 }}&w=600" data-src="https://flower-araki.jp/data/images/{{ $product->image3 }}" alt="{{ $product->name }}" class="gallery-img" /> </div>
              @endif
              @if ($product->image4)
              <div class="item-gallery"> <img src="/products/image?p={{ $product->image4 }}&w=600" data-src="https://flower-araki.jp/data/images/{{ $product->image4 }}" alt="{{ $product->name }}" class="gallery-img" /> </div>
              @endif
            </div> <!-- slider-nav.// -->
          </article> <!-- gallery-wrap .end// -->
        </aside>
        <aside class="col-sm-7">
          <article class="card-body py-0">
            <form class="" action="{{ route('put_in')}}" method="POST">
              @csrf
              <input type="hidden" name="id" value="{{ $product->id }}" />
              <h3 class="title mb-3 mt-3">{{ $product->name }}</h3>

              <p class="price-detail-wrap text-right"> 
                <span class="price h3 text-price"> 
                  <span class="num">{{ number_format($product->price) }}</span><span class="currency">円</span>
                </span> 
                <small>（税込）</small>
              </p> <!-- price-detail-wrap .// -->
              <hr>
              @if ($product->note)
              <p>{!! nl2br(e($product->note)) !!}</p>
              @endif
              

              <hr>
              <div class="form-group">
              @if ($product->quantity_input == 0)
                <label for="exampleFormControlTextarea1">名札・ネームプレート</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="4" name="nameplate"placeholder="例）
・供　山田太郎
・献花　子供一同
"></textarea>
                <input type="hidden" name="quantity" value="1" />
              @endif
              </div>
              <div class="row">
                <div class="col-sm-12 text-center">
              @if ($product->quantity_input)
                <label for="exampleFormControlQuantity">数量：</label>
                  <input type="number" class="form-inline form-control form-control-sm mr-4 w100" id="" name="quantity" value="1" />
                <input type="hidden" name="nameplate" value="" />
              @endif
                  <button class="btn btn-lg btn-info"> <i class="fas fa-shopping-cart"></i> カートに入れる </button>
                </div>
                <div class="col-sm-12 mt-2">
              @if ($product->quantity_input == 0)
              <p class="small mt-4 text-secondary">※ 複数個購入されたい場合は、こちらのページから「カートに入れる」を複数回行ってください。</p>
              @endif
                </div>
              </div>
            </article> <!-- card-body.// -->
          </form>
        </aside> <!-- col.// -->
      </div> <!-- row.// -->
    </div> <!-- card.// -->
  </div>

<div class="modal" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <img id="image-modal" src="" />
      </div>
    </div>
  </div>
</div>

@endsection


@section('css')
<style type="text/css">  
.gallery-wrap .img-big-wrap img {
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
.modal-body {
  padding: 0;
}
#image-modal {
  width: 100%;
}
.card {
  margin-bottom: 160px !important;
}
.form-inline {
  display: inline-block;
}
.w100 {
  width: 100px;
}
</style>
@stop

@section('js')
<script type="text/javascript">
  $(function(){
    $('.gallery-img').click(function () {
      $("#main-image").attr('src', $(this).attr('src'));
      $("#main-image").data('src', $(this).data('src'));
    });
    $("#open-image").click(function () {
      $("#image-modal").attr('src', $(this).parent().find('img').data('src'));
      $('#imageModal').modal('show');
    });
  });
</script>
@stop
