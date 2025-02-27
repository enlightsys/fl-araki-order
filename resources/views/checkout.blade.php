@extends('layouts.app')

@section('content')
  <div class="container py-4">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
      {{ session('status') }}
    </div>
    @endif
    <form class="" action="{{ route('purchase')}}" method="post">
      <div class="row">
        @csrf
        <div class="col-md-5 order-md-last">
          <!--REVIEW ORDER-->
          <div class="card">
            <div class="card-header">ご注文商品</div>
            <div class="card-body">
            @php
              $sum = 0;
            @endphp
            @foreach ($cart as $id => $quantity)
              <div class="row">
                <div class="col-sm-3 col-xs-3">
                  <img class="img-preview" src="https://flower-araki.jp/data/images/{{ $products[$id]->image1 }}" />
                </div>
                <div class="col-sm-6 col-xs-6">
                  <p>{{ $products[$id]->name }}</p>
                  <p><small>数量:<span>{{ $quantity }}</span></small></p>
                </div>
                <div class="col-sm-3 col-xs-3 text-right">
                  <h6>{{ number_format($products[$id]->price) }} <span>円</span></h6>
                </div>
              </div>
              @php
                $sum += $products[$id]->price * $quantity;
              @endphp
            @endforeach
              <hr />
              <div class="row">
                <div class="col-sm-5 col-xs-5">
                  <strong>小計</strong>
                </div>
                <div class="col-sm-7 col-xs-7 text-right">
                  <span>{{ number_format($sum) }}</span><span>円</span>
                  <input type="hidden" id="inputSum" name="sum" value="{{ $sum }}" />
                </div>
              </div>
              <div class="row">
                <div class="col-sm-5 col-xs-5">
                  <span>送料・手数料</span>
                </div>
                <div class="col-sm-7 col-xs-7 text-right">
                  <span id="text-fee">-</span><span>円</span>
                  <input type="hidden" id="inputFee" name="fee" value="0" />
                </div>
              </div>
              <hr />
              <div class="row">
                <div class="col-sm-5 col-xs-5">
                  <strong>合計</strong>
                </div>
                <div class="col-sm-7 col-xs-7 text-right">
                  <span id="text-total">-</span><span>円</span>
                  <input type="hidden" id="inputTotal" name="total" value="0" />
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-7 order-md-first">
          <!--SHIPPING METHOD-->
          <div class="card">
            <div class="card-header">ご注文者様の情報</div>
            <div class="card-body">
              <div class="form-group row">
                <div class="col-md-3"><strong>お名前</strong></div>
                <div class="col-md-9"><input type="text" class="form-control" name="name" value="{{ $member->name }}" readonly="readonly" /></div>
              </div>
              <div class="form-group row">
                <div class="col-md-3"><strong>メールアドレス</strong></div>
                <div class="col-md-9"><input type="text" name="email" class="form-control" value="{{ $member->email }}" readonly="readonly" /></div>
              </div>
            </div>
          </div>
          <div class="card mt-3">
            <div class="card-header">お届け先の情報</div>
            <div class="card-body">
              <div class="form-group row">
                <div class="col-md-3"><strong>お名前</strong></div>
                <div class="col-md-9">
                  <input type="text" class="form-control" name="ship_name" value="" />
                  @error('ship_name')<p class="text-danger">{{ $message }}</p>@enderror
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-3"><strong>発送先住所</strong></div>
                <div class="col-md-9">
                  〒 <input type="text" class="form-control form-zip" name="ship_zip" value="" placeholder="1234567" />
                  <select name="ship_pref_id" id="inputPref" class="form-control form-pref">
                    <option value="">-- 都道府県 --</option>
                    @foreach (config('const.pref') as $id => $val)
                    <option value="{{ $id }}" @if (old('pref_id') == $id) selected="selected" @endif>{{ $val }}</option>
                    @endforeach
                  </select>
                  @error('ship_zip')<p class="text-danger">{{ $message }}</p>@enderror
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-9 ml-auto">
                  <input type="text" class="form-control" name="ship_city" value="" placeholder="市区町村" />
                  @error('ship_city')<p class="text-danger">{{ $message }}</p>@enderror
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-9 ml-auto">
                  <input type="text" class="form-control" name="ship_address1" value="" placeholder="住所" />
                  @error('ship_address1')<p class="text-danger">{{ $message }}</p>@enderror
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-9 ml-auto">
                  <input type="text" class="form-control" name="ship_address2" value="" placeholder="建物・部屋番号など" />
                  @error('ship_address2')<p class="text-danger">{{ $message }}</p>@enderror
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-3"><strong>電話番号</strong></div>
                <div class="col-md-9">
                  <input type="text" class="form-control" name="ship_tel" value="" />
                  @error('ship_tel')<p class="text-danger">{{ $message }}</p>@enderror
                </div>
              </div>
            </div>
          </div>
          <!--SHIPPING METHOD END-->
          <!--CREDIT CART PAYMENT-->
          <div class="card mt-3">
            <div class="card-header">お支払い</div>
            <div class="card-body">
              <div class="form-group row">
                <div class="col-md-3"><strong>カード番号</strong></div>
                <div class="col-md-9"><input type="text" class="form-control" name="cardno" value="" /><small>※数字のみ</small></div>
              </div>
              <div class="form-group row">
                <div class="col-md-3">
                  <strong>有効期限</strong>
                </div>
                <div class="col-md-9">
                  <input type="text" class="form-control expire" name="expire_month" value="" placeholder="MM" /> /
                  <input type="text" class="form-control expire" name="expire_year" value="" placeholder="YY" />
                  <small>※月2桁/年2桁。(例:04/22)</small>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-3"><strong>カード名義</strong></div>
                <div class="col-md-9"><input type="text" class="form-control" name="cardno" value="" />
                  <small>※カードに印字されているご名義人をそのままご入力下さい。</small></div>
              </div>
              <div class="form-group row">
                <div class="col-md-3"><strong>セキュリティ<br />コード</strong></div>
                <div class="col-md-9"><input type="text" class="form-control secno" name="securitycode" value="" />
                  <small>※カード裏面のサインパネル右上印字の7桁の数字のうち下3桁。</small></div>
              </div>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-md-12">
              <button type="submit" class="btn btn-primary cmd-checkout">今すぐ支払う</button>
            </div>
          </div>
          <!--CREDIT CART PAYMENT END-->
        </div>
      </div>
    </form>
  </div>

@endsection


@section('css')
<style type="text/css">
  .img-preview {
    width: 80px;
    height: 80px;
    object-fit: contain;
  }
  .cmd-checkout {
    width: 100%;
    margin-top: 10px;
    font-size: 1.3em;
  }
  .form-zip {
    display: inline-block;
    width: 200px;
  }
  .form-pref {
    display: inline-block;
    width: 200px;
  }
  .expire {
    display: inline-block;
    width: 80px !important;
  }
  .secno {
    width: 100px !important;
  }
</style>
@stop

@section('js')
<script type="text/javascript">
  const fee = JSON.parse('{!! json_encode(config('const.fee')) !!}');
  $('#inputPref').on('change', function () {
    let val = $('#inputPref option:selected').val();
    if (val.length) {
      let i = val * 1;
      const formatter = new Intl.NumberFormat('ja-JP');

      $("#text-fee").text(formatter.format(fee[i]));
      $("#inputFee").val(fee[i]);

      let sum = $("#inputSum").val() * 1;
      $("#text-total").text(formatter.format(sum + fee[i]));
      $("#inputTotal").val(sum + fee[i]);
    }
  });

</script>
@stop
