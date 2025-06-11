@extends('layouts.app')

@section('content')
  @php
    $pref = config('const.pref');
    $ship_time_id = config('const.ship_time_id');
  @endphp
  <div class="container py-5">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
      {{ session('status') }}
    </div>
    @endif

    <div class="card">
      <div class="card-header">
        購入履歴
      </div>
      <div class="card-body py-0">
        <div class="row">
          <aside class="col-sm-4 border-right pt-2" id="leftcol">
            @if ($order)
            <dl>
              <dt>注文ID</dt><dd>{{ $order->id + 10000 }}</dd>
              <dt>注文日</dt><dd>{{ $order->created_at->format('Y/n/j H:i') }}</dd>
              <dt>お届け希望日</dt><dd>{{ date("Y/n/j", strtotime($order->ship_date)) }} {{ $ship_time_id[$order->ship_time_id] ?? '' }} {{ $order->ship_request }}</dd>
              <dt>お届け先</dt><dd>{{ $pref[$order->ship_pref_id] ?? '' }}{{ $order->ship_city }}{{ $order->ship_address }} {{ $order->ship_name }} 様</dd>
              <dt>合計金額</dt><dd>{{ number_format($order->total) }} 円（税込）</dd>
            </dl>
            @endif
          </aside>
          <aside class="col-sm-8 px-0">
            <table class="table table-hover shopping-cart-wrap">
              <thead class="text-muted">
                <tr>
                  <th scope="col">商品</th>
                  <th scope="col">数量</th>
                  <th scope="col">単価</th>
                  <th scope="col">小計</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($order_details as $detail)
                <tr>
                  <td>
                    <figure class="media">
                      @if ($detail->product != null)
                      <a href="/detail/{{ $detail->product->id }}">
                        <div class="img-wrap"><img src="/products/image?p={{ $detail->product->image1 ?? ''}}" class="img-thumbnail img-sm"></div>
                      </a>
                      @else
                      <div class="img-wrap"><img src="/assets/images/no_image.png" class="img-thumbnail img-sm"></div>
                      @endif
                      <p class="title text-truncate">{{ $detail->name }}<br /><small>{{ $detail->nameplate }}</small></p>
                    </figure>
                  </td>
                  <td>{{ $detail->quantity }}</td>
                  <td class="text-nowrap">{{ number_format($detail->price) }} 円</td>
                  <td class="text-nowrap">{{ number_format($detail->price * $detail->quantity) }} 円</td>
                </tr>
                @endforeach
              </tbody>
            </table>
            <p class="text-right mt-3 mr-2">小計：{{ number_format($order->sum) }}円</p>
            <p class="text-right mr-2">送料：{{ number_format($order->fee) }}円</p>
            <p class="text-right mr-2">手数料：{{ number_format($order->in_fee) }}円</p>
            <p class="est"><a  class="btn btn-sm btn-info" href="/estimate/{{ $order->id }}" target="_blank">見積書ダウンロード</a></p>
            <p class="text-right mr-2">合計：{{ number_format($order->total) }}円</p>
          </aside>
        </div>
      </div>
      <div class="card-footer text-center">
        <form action="/reorder/{{ $order->id }}" method="post">
          @csrf
          <input type="hidden" name="id" value="{{ $order->id }}" />
          <a class="btn btn-secondary" href="/history">戻る</a>
          <button class="btn btn-primary">再注文</button>
        </form>
      </div>
    </div> <!-- card.// -->
  </div>

@endsection


@section('css')
<style type="text/css">
  .img-thumbnail {
    width: 100px;
    margin-right: 10px;
  }
  .est {
    width: 200px;
    float: left;
    margin-left: 10px;
  }
</style>
@stop

@section('js')
<script type="text/javascript">
$(function(){
  if (navigator.userAgent.match(/iPhone|Android.+Mobile/)) {
    $("#leftcol").removeClass('border-right');
  }
});
</script>
@stop
