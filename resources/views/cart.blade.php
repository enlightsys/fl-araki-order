@extends('layouts.app')

@section('content')
  <div class="container py-5">
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

    <div class="card">
      <div class="card-header">
        カート確認
      </div>
      <div class="card-body">
        @if ($cart)
        <form class="" action="{{ route('cart_quantity') }}" method="post" id="formCart">
          @csrf
          <table class="table table-hover shopping-cart-wrap">
            <thead class="text-muted">
              <tr>
                <th scope="col">商品</th>
                <th scope="col" width="120">数量</th>
                <th scope="col" width="120">単価</th>
                <th scope="col" width="200" class="text-right">操作</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($cart as $id => $value)
              <tr>
                <td>
                  <figure class="media">
                    <div class="img-wrap"><img src="/products/image?p={{ $products[$value['product_id']]->image1 }}&w=150" class="img-thumbnail img-sm"></div>
                    <figcaption class="media-body">
                      <h6 class="title text-truncate">{{ $products[$value['product_id']]->name }}</h6>
                      @if ($value['nameplate'])
                      <dl class="param param-inline small">
                        <dt class="align-top">名札</dt>
                        <dd>{!! nl2br(e($value['nameplate'])) !!}</dd>
                      </dl>
                      @endif
                    </figcaption>
                  </figure> 
                </td>
                <td>
                  <select class="form-control cart-quantity" id="" name="quantity[{{ $id }}]">
                    @for ($i = 1;$i < 11;$i++)
                    <option value="{{ $i }}" @if ($i == $value['quantity']) selected="selected" @endif>{{ $i }}</option>
                    @endfor
                  </select>
                </td>
                <td> 
                  <div class="price-wrap text-right"> 
                    <p class="price">{{ number_format($products[$value['product_id']]->price * $value['quantity']) }} 円</p>
                  </div> <!-- price-wrap .// -->
                </td>
                <td class="text-right"> 
                  <input type="hidden" id="removeId{{ $id }}" name="remove[{{ $id }}]" value="0" />
                  <a href="#" class="btn btn-outline-danger btn-round btn-delete" data-id="{{ $id }}"> × 削除</a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </form>
        @else
          <p>ただいまカートには何も入っていません。</p>
        @endif
      </div>
      <div class="card-footer text-center mt-4">
        <a class="btn btn-secondary" href="/">お買い物を続ける</a>
        @if ($cart)
        <a class="btn btn-info" id="cmd-payment" href="{{ route('checkout') }}">お支払い手続きに進む</a>
      @endif
      </div>
    </div> <!-- card.// -->

    <hr class="mt-4" />
    <div class="row">
      <div class="ml-auto mr-auto col-md-6">
        <h5>花キューピット以外の配送料金について（税込）</h5>
        <ul class="small">
          <li>北海道札幌市中央区 770円</li>
          <li>北海道札幌市中央区以外 880円</li>
          <li>北海道北広島市、石狩市 1,100円</li>
        </ul>
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
  width: 90px;
  max-height: 75px;
  object-fit: cover;
}
</style>
@stop

@section('js')
<script type="text/javascript">
  $(function(){
    $(".cart-quantity").change(function() {
      $("#formCart").submit();
    });
    $(".btn-delete").click(function() {
      $("#removeId" + $(this).data('id')).val(1);
      $("#formCart").submit();
    });
  });
</script>
@stop
