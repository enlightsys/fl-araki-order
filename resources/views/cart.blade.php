@extends('layouts.app')

@section('content')
  <div class="container pt-3 pb-5">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
      {{ session('status') }}
    </div>
    @endif
    @if (session('warning'))
    <div class="alert alert-warning" role="alert">
      {{ session('warning') }}
    </div>
    @endif

    <div class="row justify-content-md-center">
      <div class="col-sm-12 col-md-10">
        <div class="card">
          <div class="card-header">
            カート確認
          </div>
          <div class="card-body">
            @if ($cart)
            <form class="" action="{{ route('cart_quantity') }}" method="post" id="form-cart">
              @csrf
              <input type="hidden" name="mode" id="form-mode" value="" />
              <table class="table table-hover shopping-cart-wrap">
                <thead class="text-muted">
                  <tr>
                    <th scope="col">商品</th>
                    <th scope="col" class="text-nowrap">　数量&nbsp;</th>
                    <th scope="col">単価</th>
                    <th scope="col">操作</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($cart as $id => $value)
                  <tr>
                    <td>
                      <div class="img-wrap d-none d-md-block d-lg-block float-left pr-2"><img src="/products/image?p={{ $products[$value['product_id']]->image1 }}&w=100" class="img-thumbnail img-sm"></div>
                      <span class="title">{{ $products[$value['product_id']]->name }}</span>
                      @if ($value['nameplate'])
                      <dl class="param param-inline small">
                        <dt class="align-top">名札</dt>
                        <dd>{!! nl2br(e($value['nameplate'])) !!}</dd>
                      </dl>
                      @endif
                    </td>
                    <td class="text-center">
                      <div class="quantity-area">
                        <input type="number" class="form-control quantity" id="" name="quantity[{{ $id }}]" value="{{ $value['quantity'] }}" />
                        <a class="btn btn-outline-secondary btn-sm cart-quantity" href="#">更新</a>
                      </div>
                    </td>
                    <td class="text-right text-nowrap"> 
                      <p class="">{{ number_format($products[$value['product_id']]->price * $value['quantity']) }} 円</p>
                    </td>
                    <td class="text-right">
                      <input type="hidden" id="removeId{{ $id }}" name="remove[{{ $id }}]" value="0" />
                      <a href="#" class="btn btn-outline-danger btn-sm btn-round btn-delete" data-id="{{ $id }}">削除</a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </form>
            @else
              <p class="px-2 py-2">ただいまカートには何も入っていません。</p>
            @endif
          </div>
          <div class="card-footer text-center mt-4">
            <a class="btn btn-secondary mr-2" href="/">お買い物を続ける</a>
            @if ($cart)
            <button type="button" class="btn btn-info ml-2" id="cmd-payment">お支払い手続きに進む</button>
            @endif
          </div>
        </div>
      </div>
    </div>

    <hr class="mt-4" />
    <div class="row">
      <div class="ml-auto mr-auto col-md-6">
        <h6>花キューピット以外の配送料金について（税込）</h6>
        <ul class="small mb-2">
          <li>北海道札幌市中央区 770円</li>
          <li>北海道札幌市中央区以外 880円</li>
          <li>北海道北広島市、石狩市 1,100円</li>
        </ul>
        <p class="feefree">11,000円以上で送料が無料になります。</p>
      </div>
    </div>
  </div>

@endsection


@section('css')
<style type="text/css">  
.param {
  margin-bottom: 7px;
  line-height: 1.4;
}
.param-inline dt {
  display: inline-block;
}
.param dt {
  margin: 0;
  margin-right: 7px;
  font-weight: 600;
}
.param-inline dd {
  vertical-align: baseline;
  display: inline-block;
}

.param dd {
  margin: 0;
  vertical-align: baseline;
}

.card-body {
  padding: 0;
}

.shopping-cart-wrap .price {
  color: #007bff;
  font-size: 18px;
  font-weight: bold;
  margin-right: 5px;
  display: block;
}
var {
  font-style: normal;
}

.media img {
  margin-right: 1rem;
}
.img-sm {
  width: 76px;
  max-height: 60px;
  object-fit: cover;
}
.shopping-cart-wrap thead th {
  text-align: center;
}
.feefree {
  padding: 0 10px;
  font-size: 0.85em;
}
.quantity-area {
  margin-left: auto;
  margin-right: auto;
}
.quantity {
  width: 100px;
  display: inline-block;
}
.cart-quantity {
  width: 60px;
  padding: 0;
}
</style>
@stop

@section('js')
<script type="text/javascript">
  $(function(){
    $(".cart-quantity").click(function(event) {
      event.preventDefault();
      $("#form-mode").val("");
      $("#form-cart").submit();
    });
    $("#cmd-payment").click(function(event) {
      $("#form-mode").val("checkout");
      $("#form-cart").submit();
    });
    $(".quantity").change(function(event) {
      $(this).next('.cart-quantity').text("更新");
    });
    $(".btn-delete").click(function() {
      $("#removeId" + $(this).data('id')).val(1);
      $("#form-cart").submit();
    });
  });
</script>
@stop
