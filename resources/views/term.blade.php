@extends('layouts.app')

@section('content')
  <div class="container py-4">
    <h3>特定商取引法に基づく表記</h3>
    <table class="mt-4 table table-bordered table-term">
      <tbody>
        <tr>
          <th>販売業者名</th>
          <td>荒木生花店<br /><small>販売業者名については「お問い合わせ先メールアドレス」へご請求をいただければ、遅滞なく開示いたします。</small></td>
        </tr>
        <tr>
          <th>販売責任者名</th>
          <td>荒木佳美</td>
        </tr>
        <tr>
          <th>所在地</th>
          <td>北海道札幌市<br /><small>所在地については「お問い合わせ先メールアドレス」へご請求をいただければ、遅滞なく開示いたします。</small></td>
        </tr>
        <tr>
          <th>電話番号</th>
          <td><small>電話番号については「お問い合わせ先メールアドレス」へご請求をいただければ、遅滞なく開示いたします。</small></td>
        </tr>
        <tr>
          <th>メールアドレス</th>
          <td>info@flower-araki.jp</td>
        </tr>
        <tr>
          <th>支払方法</th>
          <td>クレジットカード</td>
        </tr>
        <tr>
          <th>商品引渡し時期</th>
          <td>ご注文受付後、営業日2～7日以内発送 </td>
        </tr>
        <tr>
          <th>商品以外の必要料金</th>
          <td>送料は原則としてお客様負担にてお願いいたします。送料についてはカート画面を参照ください。</td>
        </tr>
        <tr>
          <th>返品・キャンセル・不良品</th>
          <td>ご注文内容と異なる、商品に欠陥がある場合を除き、返品・キャンセルには応じかねます。ご注文内容と異なる、商品に欠陥がある場合は、商品到着後7日以内にメールかお電話にてご連絡ください。</td>
        </tr>
      </tbody>
    </table>
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