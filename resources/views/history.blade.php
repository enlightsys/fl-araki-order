@extends('layouts.app')

@section('content')
  @php
    $pref = config('const.pref');
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
        @if ($orders)
        <table class="table table-hover shopping-cart-wrap">
          <thead class="text-muted">
            <tr>
              <th scope="col" class="text-center">注文ID</th>
              <th scope="col" class="text-center">注文日</th>
              <th scope="col" class="text-center">お届け先</th>
              <th scope="col" class="text-center">合計金額</th>
              <th scope="col" class="text-center">操作</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($orders as $order)
            <tr>
              <td>{{ $order->id + 10000 }}</td>
              <td>{{ $order->created_at->format('Y/n/j H:i') }}</td>
              <td>{{ $pref[$order->ship_pref_id] ?? '' }}{{ $order->ship_city }}{{ $order->ship_address1 }}{{ $order->ship_address2 }} {{ $order->ship_name }} 様</td>
              <td class="text-right"> {{ number_format($order->total) }} 円（税込）</var></td>
              <td class="text-right"> 
                <a href="/history_detail/{{ $order->id }}" class="btn btn-info">詳細</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @else
          <p>現在購入履歴はありません。</p>
        @endif
      </div>
      <div class="card-footer text-center mt-4">
        <a class="btn btn-secondary" href="/mypage">戻る</a>
      </div>
    </div> <!-- card.// -->

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
