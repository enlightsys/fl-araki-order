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
      <div class="card-body">
        <div class="row">
          <aside class="col-sm-4 border-right">
            @if ($order)
            <dl>
              <dt>注文ID</dt><dd>{{ $order->id + 10000 }}</dd>
              <dt>注文日</dt><dd>{{ $order->created_at->format('Y/n/j H:i') }}</dd>
              <dt>お届け希望日</dt><dd>{{ date("Y/n/j", strtotime($order->ship_date)) }} {{ $ship_time_id[$order->ship_time_id] ?? '' }} {{ $order->ship_request }}</dd>
              <dt>お届け先</dt><dd>{{ $pref[$order->ship_pref_id] ?? '' }}{{ $order->ship_city }}{{ $order->ship_address1 }}{{ $order->ship_address2 }} {{ $order->ship_name }} 様</dd>
              <dt>合計金額</dt><dd>{{ number_format($order->total) }} 円（税込）</dd>
            </dl>
            @endif
          </aside>
          <aside class="col-sm-8">
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
                      <div class="img-wrap"><img src="https://flower-araki.jp/data/images/{{ $detail->product->image1 ?? '' }}" class="img-thumbnail img-sm"></div>
                      @else
                      <div class="img-wrap"><img src="/assets/images/no_image.png" class="img-thumbnail img-sm"></div>
                      @endif
                      <p class="title text-truncate">{{ $detail->name }}</p>
                    </figure>
                  </td>
                  <td>{{ $detail->quantity }}</td>
                  <td>{{ number_format($detail->price) }} 円</td>
                  <td>{{ number_format($detail->price * $detail->quantity) }} 円</td>
                </tr>
                @endforeach
              </tbody>
            </table>
            <p class="text-right mt-3">小計：{{ number_format($order->sum) }}円</p>
            <p class="text-right">送料：{{ number_format($order->fee) }}円</p>
            <p class="text-right">合計：{{ number_format($order->total) }}円</p>
          </aside>
        </div>
      </div>
      <div class="card-footer text-center mt-4">
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
</style>
@stop

@section('js')
<script type="text/javascript">
</script>
@stop
