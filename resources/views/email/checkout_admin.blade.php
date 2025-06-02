@php
  $payment_id = config('const.payment_id');
  $ship_time_id = config('const.ship_time_id');
  $pref = config('const.pref');
  $sum = 0;
@endphp

荒木生花店注文サイトより注文がありました。

■お名前
{{ $vars->name }}

■ご住所
〒 {{ $vars->zip }} {{ $pref[$vars->pref_id] ?? ''}} {{ $vars->city }} {{ $vars->address }}

■電話番号
{{ $vars->tel }}

■メールアドレス
{{ $vars->email }}

■お届け希望日時
{{ $vars->ship_date }} {{ $ship_time_id[$vars->ship_time_id] ?? '' }}

■発送先お名前
{{ $vars->ship_name }}

■発送先ご住所
〒 {{ $vars->ship_zip }} {{ $pref[$vars->ship_pref_id] ?? ''}} {{ $vars->ship_city }} {{ $vars->ship_address }}

■発送先電話番号
{{ $vars->ship_tel }}

■お支払い方法
{{ $payment_id[$vars->payment_id] ?? '' }}

■見積書
@if ($vars->estimate) 希望する @endif
 
■その他ご希望など
{{ $vars->remark }}

■注文商品情報
@foreach ($products as $product)
{{ $product->name }} 名札：{{ mb_ereg_replace("\r\n", "", $product->nameplate) }} {{ number_format($product->price) }} × {{ $product->quantity }} = {{ number_format($product->price * $product->quantity) }} 円 @php $sum += $product->price * $product->quantity; @endphp
@endforeach

小計：{{ number_format($sum) }} 円
送料：{{ number_format($vars->fee) }} 円
合計：{{ number_format($vars->total) }} 円
