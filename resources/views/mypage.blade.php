@extends('layouts.app')

@section('content')
  <div class="container py-4">
    <h3>{{ Auth::user()->name }} 様の会員ページ</h3>
    <div class="row mt-4">
      <div class="col-md-6 mb-4">
        <h5>ご注文履歴</h5>
        <p><small>ご注文内容の確認ができます。</small></p>
        <a class="btn btn-info" href="/history">ご注文履歴を確認する</a>
        @if ($bills->count())
        <h5 class="mt-4">発行済みご請求書</h5>
          @foreach ($bills as $bill)
            {{ $bill->year }}年{{ $bill->month }}月 <a href="/bills/{{ $bill->year }}/{{ $bill->month }}">ダウンロード</a>
          @endforeach
        @endif
      </div>
      <div class="col-md-6">
        <h5>アカウント設定</h5>
        <p class="mt-2 mb-0"><a class="btn btn-link" href="/profile">ご登録情報を確認・変更する</a></p>
        <!-- <p class="pt-0"><button type="button" class="btn btn-link">発送先情報を確認・変更する</button></p> -->
      </div>
    </div>
  </div>
@endsection

@section('css')
<style type="text/css">
  .table-term tbody th {
    background: rgba(0,0,0,.05);
    white-space: nowrap;
  }
</style>
@stop
