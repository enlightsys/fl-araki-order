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
              <div class="row mb-1">
                <div class="col-sm-3 col-xs-3">
                  <img class="img-preview" src="https://flower-araki.jp/data/images/{{ $products[$id]->image1 }}" />
                </div>
                <div class="col-sm-6 col-xs-6">
                  <p>{{ $products[$id]->name }}</p>
                  <p><small>数量:<span>{{ $quantity }}</span></small></p>
                </div>
                <div class="col-sm-3 col-xs-3 text-right">
                  <h6>{{ number_format($products[$id]->price * $quantity) }} <span>円</span></h6>
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

        <input type="hidden" name="name" value="{{ $member->name }}" />
        <input type="hidden" name="contact_name" value="{{ $member->contact_name }}" />

        <div class="col-md-7 order-md-first">
          <!--SHIPPING METHOD-->
          <div class="card">
            <div class="card-header">ご注文者様の情報</div>
            <div class="card-body">
              <div class="form-group row">
                <div class="col-md-3"><strong>お名前</strong></div>
                <div class="col-md-9"><p class="mb-0">{{ $member->name }} {{ $member->contact_name }} 様</p></div>
              </div>
              <div class="form-group row">
                <div class="col-md-3"><strong>メールアドレス</strong></div>
                <div class="col-md-9"><p class="mb-0">{{ $member->email }}</p><input type="hidden" name="email" value="{{ $member->email }}" /></div>
              </div>
            </div>
          </div>
          <div class="card mt-3">
            <div class="card-header">お届け先の情報</div>
            <div class="card-body">
              <div class="form-group row">
                <div class="col-md-3 mt-1"><strong>お届け希望日時</strong></div>
                <div class="col-md-9">
                  <input type="text" class="form-control form-delivery-date datetimepicker datetimepicker-input" name="ship_date" id="datePicker" value="{{ old('ship_date') }}" data-toggle="datetimepicker" data-target="#datePicker" />
                  @foreach (config('const.ship_time_id') as $id => $val)
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="ship_time_id" id="inlineRadio{{ $id }}" value="{{ $id }}" @if (old('ship_time_id') == $id) checked="checked" @endif />
                    <label class="form-check-label" for="inlineRadio{{ $id }}">{{ $val }}</label>
                  </div>
                  @endforeach
                  @error('ship_date')<p class="text-danger">{{ $message }}</p>@enderror
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-9 ml-auto">
                  <input type="text" class="form-control" name="ship_request" value="{{ old('ship_request') }}" placeholder="例）17時頃までに贈りたい" />
                  <p class="text-caution">※その他の詳細のご希望があれば記入してください。</p>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-3 mt-1"><strong>お名前</strong></div>
                <div class="col-md-9">
                  <input type="text" class="form-control" name="ship_name" value="{{ old('ship_name') }}" />
                  @error('ship_name')<p class="text-danger">{{ $message }}</p>@enderror
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-3 mt-1"><strong>発送先住所</strong></div>
                <div class="col-md-9">
                  〒 <input type="text" class="form-control form-zip" name="ship_zip" value="{{ old('ship_zip') }}" placeholder="0001111" onkeyup="AjaxZip3.zip2addr(this, '', 'ship_pref_id', 'ship_city');" />
                  <select name="ship_pref_id" id="inputPref" class="form-control form-pref">
                    <option value="">-- 都道府県 --</option>
                    @foreach (config('const.pref') as $id => $val)
                    <option value="{{ $id }}" @if (old('ship_pref_id') == $id) selected="selected" @endif>{{ $val }}</option>
                    @endforeach
                  </select>
                  @error('ship_zip')<p class="text-danger">{{ $message }}</p>@enderror
                  @error('ship_pref_id')<p class="text-danger">{{ $message }}</p>@enderror
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-9 ml-auto">
                  <input type="text" class="form-control" name="ship_city" value="{{ old('ship_city') }}" placeholder="市区町村" />
                  @error('ship_city')<p class="text-danger">{{ $message }}</p>@enderror
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-9 ml-auto">
                  <input type="text" class="form-control" name="ship_address1" value="{{ old('ship_address1') }}" placeholder="住所" />
                  @error('ship_address1')<p class="text-danger">{{ $message }}</p>@enderror
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-9 ml-auto">
                  <input type="text" class="form-control" name="ship_address2" value="{{ old('ship_address2') }}" placeholder="建物・部屋番号など" />
                  @error('ship_address2')<p class="text-danger">{{ $message }}</p>@enderror
                  <p class="text-caution">※番地、マンション名、部屋番号、会場名まで忘れずご記入ください。</p>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-3 mt-1"><strong>電話番号</strong></div>
                <div class="col-md-9">
                  <input type="text" class="form-control" name="ship_tel" value="{{ old('ship_tel') }}" />
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
              @if ($member->bill_enabled)
              <div class="form-group row mb-0">
                <div class="col-md-12"><p>月締請求でのお支払いになります。</p></div>
              </div>
              @else
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
              @endif
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-md-12">
              @if ($member->bill_enabled)
              <button type="submit" class="btn btn-primary cmd-checkout">ご注文を完了する</button>
              @else
              <button type="submit" class="btn btn-primary cmd-checkout">今すぐ支払う</button>
              @endif
            </div>
          </div>
          <!--CREDIT CART PAYMENT END-->
        </div>
      </div>
    </form>
  </div>

@endsection


@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />
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
  .form-delivery-date {
    display: inline-block;
    width: 150px;
    margin-right: 10px;
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
  .text-caution {
    font-size: 0.85em;
  }
  .text-danger {
    color: #f00;
    font-weight: bold;
    font-size: 0.9em;
    margin-bottom: 0.5rem;
  }
</style>
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment-with-locales.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
<script type="text/javascript">
  $(document).ready(function () {
    const fee = JSON.parse('{!! json_encode(config('const.fee')) !!}');

    $('#datePicker').datetimepicker({
      locale: 'ja',
      format: 'YYYY/MM/DD',
      dayViewHeaderFormat: 'YYYY年 MMM',
      date: $('#datePicker').val() ? new Date($('#datePicker').val()).toISOString() : null
    });

    $('#inputPref').on('change', function () {
      calc_total();
    });

    function calc_total() {
      let val = $('#inputPref option:selected').val();
      if (val.length) {
        let i = val * 1;
        const formatter = new Intl.NumberFormat('ja-JP');

        $("#text-fee").text(formatter.format(fee[i]));
        $("#inputFee").val(fee[i]);

        let sum = $("#inputSum").val() * 1;
        $("#text-total").text(formatter.format(sum + fee[i]));
        $("#inputTotal").val(sum + fee[i]);
      } else {
        $("#text-fee").text("-");
        $("#inputFee").val(0);
        $("#text-total").text("-");
        $("#inputTotal").val(0);
      }
    }

    AjaxZip3.onSuccess = function() {
      calc_total();
    }
  });
</script>
@stop
