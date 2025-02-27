@extends('layouts.app')

@section('content')
<!-- Contact us area start -->
  <div class="container py-4">
    <h3>お問い合わせ</h3>
    <div class="row justify-content-center mt-4">
      <div class="col-md-10">
        <div class="card">
          <div class="card-header">下記内容でお問い合わせを送信します。よろしいですか？</div>
          <div class="card-body">
            <form class="contact-form-box mb-5" id="contact-form" action="{{ route('contact_store') }}" method="post">
              @csrf
              <div class="form-group row">
                <div class="col-sm-3">お問い合わせの種類</div>
                <div class="col-sm-9">
                @if ($input['kind1'] ?? false) ご注文について @endif
                @if ($input['kind2'] ?? false) その他のご相談 @endif
                <input type="hidden" name="kind1" value="{{ $input['kind1'] ?? '0' }}" />
                <input type="hidden" name="kind2" value="{{ $input['kind2'] ?? '0' }}" />
                </div>
              </div>
              <div class="form-group row">
                <label for="inputName" class="col-sm-3">お名前</label>
                <div class="col-sm-9">
                  <input type="hidden" name="name" value="{{ $input['name'] }}" />
                  {{ $input['name'] }}
                </div>
              </div>

              <div class="form-group row">
                <label for="inputZip" class="col-sm-3">ご住所</label>
                <div class="col-sm-3">
                <p>
                <input type="hidden" name="zip" value="{{ $input['zip'] }}" />
                  {{ $input['zip'] }}
                <input type="hidden" name="pref_id" value="{{ $input['pref_id'] }}" />
                  @foreach (config('const.pref') as $pref_id => $pref)
                    @if ($input['pref_id'] == $pref_id) {{ $pref }} @endif
                  @endforeach
                <input type="hidden" name="city" value="{{ $input['city'] }}" />
                {{ $input['city'] }}
                <input type="hidden" name="address" value="{{ $input['address'] }}" />
                {{ $input['address'] }}</p>
                </div>
              </div>

              <div class="form-group row">
                <label for="inputEmail3" class="col-sm-3">メールアドレス（＊）</label>
                <div class="col-sm-9">
                  <input type="hidden" name="email" value="{{ $input['email'] }}" />
                  <p>{{ $input['email'] }}</p>
                </div>
              </div>
              <div class="form-group row">
                <label for="inputTel" class="col-sm-3">電話番号</label>
                <div class="col-sm-4">
                  <input type="hidden" name="tel" value="{{ $input['tel'] }}" />
                  <p>{{ $input['tel'] }}</p>
                </div>
              </div>
              <div class="form-group row">
                <label for="inputMessage" class="col-sm-3">お問い合わせ内容</label>
                <div class="col-sm-9">
                  <input type="hidden" name="message" value="{{ $input['message'] }}" />
                  <p>{!! nl2br(e($input['message'])) !!}</p>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-12 text-center">
                  <button type="submit" class="btn btn-secondary" name="back">戻る</button>
                  <button type="submit" class="btn btn-info" name="send">送信する</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('css')
<style type="text/css">
</style>
@stop
