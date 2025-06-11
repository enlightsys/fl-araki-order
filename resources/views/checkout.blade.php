@extends('layouts.app')

@section('content')
  @php
    $pref = config('const.pref');
  @endphp
  <div class="container py-4">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
      {{ session('status') }}
    </div>
    @endif
    <form class="" action="{{ route('purchase')}}" id="checkout-form" method="post">
      <div class="row">
        @csrf
        <div class="col-md-5 order-md-last mb-4">
          <!--REVIEW ORDER-->
          <div class="card">
            <div class="card-header">ご注文商品</div>
            <div class="card-body">
            @php
              $sum = 0;
            @endphp
            @foreach ($cart as $value)
              <div class="row mb-3">
                <div class="col-sm-3 col-xs-3 d-none d-md-block d-lg-block d-xl-block">
                  <img class="img-preview" src="/products/image?p={{ $products[$value['product_id']]->image1 }}&w=150" />
                </div>
                <div class="col-sm-6 col-xs-6 pl-1 pr-0">
                  <p class="mb-1">{{ $products[$value['product_id']]->name }}</p>
                  <p class="mb-0"><small>数量:<span>{{ $value['quantity'] }}</span></small></p>
                  <p class="mb-0"><small>名札・ネームプレート:<span>@if ($value['nameplate']) <br />{!! nl2br(e($value['nameplate'])) !!} @else （無し） @endif</span></small></p>
                </div>
                <div class="col-sm-3 col-xs-3 text-right">
                  <h6>{{ number_format($products[$value['product_id']]->price * $value['quantity']) }} <span>円</span></h6>
                </div>
              </div>
              @php
                $sum += $products[$value['product_id']]->price * $value['quantity'];
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
                  <strong>合計<small>（税込）</small></strong>
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
        <input type="hidden" name="zip" value="{{ $member->zip }}" />
        <input type="hidden" name="pref_id" value="{{ $member->pref_id }}" />
        <input type="hidden" name="city" value="{{ $member->city }}" />
        <input type="hidden" name="address" value="{{ $member->address }}" />
        <input type="hidden" name="tel" value="{{ $member->tel }}" />

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
                <div class="col-md-3"><strong>ご住所</strong></div>
                <div class="col-md-9"><p class="mb-0">〒 {{ $member->zip }} {{ $pref[$member->pref_id] ?? '' }} {{ $member->city }} {{ $member->address }}</p></div>
              </div>
              <div class="form-group row">
                <div class="col-md-3"><strong>電話番号</strong></div>
                <div class="col-md-9"><p class="mb-0">{{ $member->tel }}</p></div>
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
                  <div class="form-check form-check-inline form-check-ship">
                    <input class="form-check-input" type="radio" name="ship_time_id" id="inlineRadio{{ $id }}" value="{{ $id }}" @if (old('ship_time_id') == $id) checked="checked" @endif />
                    <label class="form-check-label" for="inlineRadio{{ $id }}">{{ $val }}</label>
                  </div>
                  @endforeach
                  <p class="text-danger error-checkout" id="error-ship_date">@error('ship_date') {{ $message }} @enderror</p>
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
                  <p class="text-danger error-checkout" id="error-ship_name">@error('ship_name') {{ $message }} @enderror</p>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-3 mt-1"><strong>発送先住所</strong></div>
                <div class="col-md-9">
                  〒 <input type="text" class="form-control form-zip" name="ship_zip" value="{{ old('ship_zip') }}" maxlength="8" placeholder="0001111" onkeyup="AjaxZip3.zip2addr(this, '', 'ship_pref_id', 'ship_city');" />
                  <select name="ship_pref_id" id="inputPref" class="form-control form-pref">
                    <option value="">-- 都道府県 --</option>
                    @foreach (config('const.pref') as $id => $val)
                    <option value="{{ $id }}" @if (old('ship_pref_id') == $id) selected="selected" @endif>{{ $val }}</option>
                    @endforeach
                  </select>
                  <p class="text-danger error-checkout" id="error-ship_zip">@error('ship_zip') {{ $message }} @enderror</p>
                  <p class="text-danger error-checkout" id="error-ship_pref_id">@error('ship_pref_id') {{ $message }} @enderror</p>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-9 ml-auto">
                  <input type="text" class="form-control" name="ship_city" value="{{ old('ship_city') }}" placeholder="市区町村" />
                  <p class="text-danger error-checkout" id="error-ship_city">@error('ship_city') {{ $message }} @enderror</p>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-9 ml-auto">
                  <input type="text" class="form-control" name="ship_address" value="{{ old('ship_address') }}" placeholder="番地・建物名" />
                  <p class="text-danger error-checkout" id="error-ship_address">@error('ship_address') {{ $message }} @enderror</p>
                  <p class="text-caution">※番地、マンション名、部屋番号、会場名まで忘れずご記入ください。</p>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-3 mt-1"><strong>電話番号</strong></div>
                <div class="col-md-9">
                  <input type="text" class="form-control" name="ship_tel" value="{{ old('ship_tel') }}" maxlength="15" />
                  <p class="text-danger error-checkout" id="error-ship_tel">@error('ship_tel') {{ $message }} @enderror</p>
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
              <div class="form-group row mb-4">
                <div class="col-md-3 mt-1"><strong>お支払い方法</strong></div>
                <div class="col-md-9">
                  @foreach (config('const.payment_id') as $id => $val)
                  <div class="form-check form-check-inline">
                    <input class="form-check-input payment-input" type="radio" name="payment_id" id="inlineRadioPayment{{ $id }}" value="{{ $id }}" @if (old('payment_id', 1) == $id) checked="checked" @endif />
                    <label class="form-check-label" for="inlineRadioPayment{{ $id }}">{{ $val }}</label>
                </div>
                  @endforeach
                </div>
              </div>
              @elseif (!$card_disabled)
                <input type="hidden" name="payment_id" value="1" />
              @endif

              <div class="form-group row mb-0 mt-3 payment-bill">
                <div class="col-md-12"><p>月締請求でのお支払いになります。</p></div>
              </div>
              @if (!$card_disabled)
              <input type="radio" id="zeus_token_action_type_new" name="zeus_card_option" value="new" checked="checked" class="d-none" />
              <input type="radio" id="zeus_token_action_type_quick" name="zeus_card_option" value="new" class="d-none" />
              <input type="tel" id="zeus_token_card_cvv_for_registerd_card" name="zeus_token_card_cvv_for_registerd_card" value="" class="d-none" />
              <input type="hidden" value="" id="zeus_token_value" name="zeus_token_value" />
              <input type="hidden" value="" id="zeus_token_masked_card_no" name="zeus_token_masked_card_no" />
              <input type="hidden" value="" id="zeus_token_return_card_expires_month" name="zeus_token_return_card_expires_month" />
              <input type="hidden" value="" id="zeus_token_return_card_expires_year" name="zeus_token_return_card_expires_year" />
              <input type="hidden" value="" id="zeus_token_return_card_name" name="zeus_token_return_card_name" />
              <input type="hidden" value="" id="zeus_registerd_card_area" name="zeus_registerd_card_area" />
              <input type="hidden" value="" id="zeus_new_card_area" name="zeus_new_card_area" />
              <input type="hidden" value="" id="zeus_xid" name="zeus_xid" />

              <div class="form-group row payment-credit">
                <div class="col-md-3"><strong>カード番号</strong></div>
                <div class="col-md-9"><input type="text" class="form-control" id="zeus_token_card_number" name="zeus_token_card_number" value="" /><small>※数字のみ</small></div>
              </div>
              <div class="form-group row payment-credit">
                <div class="col-md-3">
                  <strong>有効期限</strong>
                </div>
                <div class="col-md-9">
                  <select id="zeus_token_card_expires_month" name="zeus_token_card_expires_month" class="form-control expire1">
                    @for ($i = 1;$i <= 12; $i++)
                    <option value="{{ sprintf("%02d", $i) }}">{{ sprintf("%02d", $i) }}</option>
                    @endfor
                  </select> /
                  <select id="zeus_token_card_expires_year" name="zeus_token_card_expires_year" class="form-control expire2">
                    @for ($i = date("Y");$i <= date("Y") + 6; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                  </select>
                </div>
              </div>
              <div class="form-group row payment-credit">
                <div class="col-md-3"><strong>カード名義</strong></div>
                <div class="col-md-9"><input type="text" class="form-control" id="zeus_token_card_name" name="zeus_token_card_name" value="" />
                  <small>※カードに印字されているご名義人をそのままご入力下さい。</small></div>
              </div>
              @else
              <div class="form-group row mb-0 mt-3 payment-credit">
                <div class="col-md-12"><p>現在、クレジットカードでのお支払いはできません。</p></div>
              </div>
              @endif
              {{--
              <div class="form-group row payment-credit">
                <div class="col-md-3"><strong>セキュリティ<br />コード</strong></div>
                <div class="col-md-9"><input type="text" class="form-control secno" id="zeus_token_card_cvv" name="zeus_token_card_cvv" value="" />
                  <small>※カード裏面のサインパネル右上印字の7桁の数字のうち下3桁。</small></div>
              </div>
              --}}
            </div>
          </div>
          <div class="card mt-3">
            <div class="card-header">その他</div>
            <div class="card-body">
                <div class="form-check">
                  <input type="hidden" name="estimate" value="0" />
                  <input class="form-check-input" type="checkbox" name="estimate" id="checkEstimate" value="1" @if (old('estimate', 1) == $id) checked="checked" @endif />
                  <label class="form-check-label" for="checkEstimate">見積書を希望する。</label>
                </div>
                <textarea class="form-control mt-4" name="remark" rows="4" placeholder="その他ご希望などございましたら記入ください。">{{ old('remark') }}</textarea>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-md-12">
              <button type="button" class="btn btn-primary cmd-checkout payment-credit" id="cmd-credit" @if ($card_disabled) disabled="disabled" @endif>今すぐ支払う</button>
              <button type="button" class="btn btn-primary cmd-checkout payment-bill" id="cmd-bill">ご注文を完了する</button>
            </div>
          </div>
          <!--CREDIT CART PAYMENT END-->
        </div>
      </div>
    </form>
  </div>

  <!-- Modal -->
  <div class="modal" id="paymentModalCenter" tabindex="-1" role="dialog" aria-labelledby="paymentModalCenterTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body modal-3ds">
          <div id="challenge_wait" class="text-center">しばらくお待ち下さい...</div>
          <div id="3dscontainer" class="area-3ds"></div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />
<link rel="stylesheet" href="https://linkpt.cardservice.co.jp/api/token/1.0/zeus_token.css">
<!-- <link rel="stylesheet" href="https://secure2-sandbox.cardservice.co.jp/api/token/1.0/zeus_token.css"> -->
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
    width: 120px;
    margin-right: 6px;
  }
  .form-zip {
    display: inline-block;
    width: 120px;
  }
  .form-pref {
    display: inline-block;
    width: 160px;
  }
  .expire1 {
    display: inline-block;
    width: 60px !important;
  }
  .expire2 {
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
  .modal-3ds {
    height: 400px;
  }
  .area-3ds {
    height: 90%;
  }
  .text-danger {
    margin-bottom: 0;
  }
  .col-md-3 {
    margin-bottom: 4px;
  }
  .form-check-label {
    font-size: 0.9em;
  }
  .form-check-ship {
    margin-right: 3px;
  }
</style>
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment-with-locales.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
<!-- <script src="https://secure2-sandbox.cardservice.co.jp/api/token/2.0/zeus_token2.js"></script> -->
<script src="https://linkpt.cardservice.co.jp/api/token/2.0/zeus_token2.js"></script>
<script type="text/javascript">
  const zeusTokenIpcode = "2012018157";
  $(document).ready(function () {
    let date = new Date();
    date.setDate(date.getDate());
    let datestr = date.toLocaleDateString('sv-SE');
    const free = {{ $free }};

    $('#datePicker').datetimepicker({
      locale: 'ja',
      format: 'YYYY/MM/DD',
      dayViewHeaderFormat: 'YYYY年 MMM',
      // date: $('#datePicker').val() ? new Date($('#datePicker').val()).toISOString() : null,
      minDate: datestr
    });

    $('input[name="ship_city"]').on('change', function () {
      calc_total();
    });

    function calc_total() {
      let val = $('input[name="ship_city"').val();
      if (val.length) {
        let sum = $("#inputSum").val() * 1;
        let fee = 550;

        if (sum >= 11000) {
          fee = 0;
        } else {
          if (free == 1) {
            fee = 0;
          } else {
            if (val.substr(0, 6) == "札幌市中央区") {
              fee = 770;
            } else if (val.substr(0, 3) == "札幌市") {
              fee = 880;
            } else if (val.substr(0, 4) == "北広島市" || val.substr(0, 3) == "石狩市") {
              fee = 1100;
            }
          }
        }
        const formatter = new Intl.NumberFormat('ja-JP');

        $("#text-fee").text(formatter.format(fee));
        $("#inputFee").val(fee);

        $("#text-total").text(formatter.format(sum + fee));
        $("#inputTotal").val(sum + fee);
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

    $('.payment-input').on('click', function () {
      set_payment();
    });

    function set_payment() {
      let payment_id = $('input[name="payment_id"]:checked').val();
      if (payment_id == null) {
        payment_id = $('input[name="payment_id"]').val();
      }

      $(".payment-credit").hide();
      $(".payment-bill").hide();

      if (payment_id == "1") {
        $(".payment-credit").show();
      } else if (payment_id == "2") {
        $(".payment-bill").show();
      }
    }

    set_payment();
    calc_total();

    function beforeSubmit() {
      zeusToken.getToken(function(zeus_token_response_data) {
        if (!zeus_token_response_data['result']) {
          alert(zeusToken.getErrorMessage(zeus_token_response_data['error_code']));
        } else {
          if (confirm('クレジットカード決済を実行します。\nよろしいですか？')) {
            let token_key = zeus_token_response_data['token_key'];
            let amount = $("#inputTotal").val();
            $('#paymentModalCenter').modal('show');

            $.post(
              '{{ route('zeus_enroll') }}',
              { "_token" : $('meta[name="csrf-token"]').attr('content'), token: token_key, amount: amount },
              function (data) {
                const xid = data.data.xid;
                const url = data.data.url;

                if (data.status == "success") {
                  setPareqParams(xid, "PaReq", '{{ route('zeus_term') }}', 2, url);
                } else if (data.status == "outside") {
                  $("#zeus_xid").val(data.data.xid);
                  $('#checkout-form').submit();
                }
              },
              "json"
            ).fail(function(data) {
              console.log(data);
            });
          }
        }
      });
    }

    $('#cmd-credit').on('click', function () {
      $(".error-checkout").html("");
      $.post(
        '{{ route('purchase_check')}}', 
        $('#checkout-form').serialize(),
        function (data) {
          if (($("#inputTotal").val() * 1) > 0) {
            beforeSubmit();
          }
        },
        "json"
      ).fail(function(data) {
        let res = data.responseJSON;

        for (let col in res.errors) {
          $("#error-" + col).show();
          for (let i in res.errors[col]) {
            $("#error-" + col).text(res.errors[col][i]);
          }
        }
        window.scroll({ top: 0 });
      });
    });

    $('#cmd-bill').on('click', function () {
      $("#checkout-form").submit();
    });
  });

  function _onPaResSuccess(data) {
    console.log(data);
    // location.href = '{{ route('complete') }}?md=' + data.md;
  }
  function _onAuthResult(data) {
    console.log(data);
    if (data.status == "success" && data.transStatus == "Y") {
      $("#zeus_xid").val(data.xid);
      $('#checkout-form').submit();
    } else {
      $('#paymentModalCenter').modal('hide');
      $("#challenge_wait").show();

      alert("エラーが発生しました。");
    }
  }
  function _onError(error) {
    console.log(error);
  }
  function loadedChallenge() {
    $("#challenge_wait").hide();
  }
</script>
@stop
