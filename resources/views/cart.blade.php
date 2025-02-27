@extends('layouts.app')

@section('content')
  <div class="container py-5">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
      {{ session('status') }}
    </div>
    @endif

    <div class="card">
      <div class="card-header">
        カート確認
      </div>
      <div class="card-body">
        @if ($cart)
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
            @foreach ($cart as $id => $quantity)
            <tr>
              <td>
                <figure class="media">
                  <div class="img-wrap"><img src="https://flower-araki.jp/data/images/{{ $products[$id]->image1 }}" class="img-thumbnail img-sm"></div>
                  <figcaption class="media-body">
                    <h6 class="title text-truncate">{{ $products[$id]->name }}</h6>
                    {{--
                    <dl class="param param-inline small">
                      <dt>Size: </dt>
                      <dd>XXL</dd>
                    </dl>
                    <dl class="param param-inline small">
                      <dt>Color: </dt>
                      <dd>Orange color</dd>
                    </dl>
                    --}}
                  </figcaption>
                </figure> 
              </td>
              <td> 
                <select class="form-control">
                  @for ($i = 1;$i < 11;$i++)
                  <option value="{{ $i }}" @if ($i == $quantity) selected="selected" @endif>{{ $i }}</option>
                  @endfor
                </select> 
              </td>
              <td> 
                <div class="price-wrap"> 
                  <var class="price">{{ number_format($products[$id]->price) }} 円</var>
                </div> <!-- price-wrap .// -->
              </td>
              <td class="text-right"> 
                <a href="" class="btn btn-outline-danger btn-round"> × 削除</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
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
        <h5>配送料金について</h5>
        <ul class="small">
          <li>北海道 1,530円（税込）</li>
          <li>北東北（青森県、岩手県、秋田県）1,790円（税込）</li>
          <li>南東北（宮城県、山形県、福島県）1,920円（税込）</li>
          <li>関東/信越（新潟県/長野県） 1,740円（税込）</li>
          <li>北陸/中部 2,200円（税込）</li>
          <li>関西 2,510円（税込）</li>
          <li>中国/四国 2,670円（税込）</li>
          <li>九州　2,930円（税込）</li>
          <li>沖縄 3,590円（税込）</li>
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
</script>
@stop
