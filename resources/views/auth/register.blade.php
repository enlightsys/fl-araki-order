@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center mt-4">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">新規会員の登録</div>

        <div class="card-body">
          <p>お客様情報を入力してください。<span class="text-red">（※）</span>は必須項目です。</p>
          <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="row mb-3">
              <label for="name" class="col-md-4 col-form-label text-md-end">お名前 <span class="text-red">（※）</span></label>

              <div class="col-md-8">
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" autofocus>
                <p class="text-anno">↑法人の場合は会社名などをこちらにご記入ください。</p>
                @error('name')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label for="name" class="col-md-4 col-form-label text-md-end">ご担当者名</label>

              <div class="col-md-8">
                <input id="contact_name" type="text" class="form-control @error('contact_name') is-invalid @enderror" name="contact_name" value="{{ old('contact_name') }}" autofocus>
                <p class="text-anno">↑ご担当者様がいらっしゃる場合はお名前をご記入ください。</p>
                @error('contact_name')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label for="zip" class="col-md-4 col-form-label text-md-end">ご住所 <span class="text-red">（※）</span></label>

              <div class="col-md-8">
                〒 <input type="text" class="form-control form-zip @error('zip') is-invalid @enderror" name="zip" value="{{ old('zip') }}" placeholder="0001111" onkeyup="AjaxZip3.zip2addr(this, '', 'pref_id', 'city');" />
                <select name="pref_id" id="inputPref" class="form-control form-pref @error('pref_id') is-invalid @enderror">
                  <option value="">-- 都道府県 --</option>
                  @foreach (config('const.pref') as $id => $val)
                  <option value="{{ $id }}" @if (old('pref_id') == $id) selected="selected" @endif>{{ $val }}</option>
                  @endforeach
                </select>
                @error('zip')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
                @error('pref_id')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label for="name" class="col-md-4 col-form-label text-md-end">&nbsp;</label>

              <div class="col-md-8">
                <input id="city" type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ old('city') }}" placeholder="市区町村" />
                @error('city')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label for="name" class="col-md-4 col-form-label text-md-end">&nbsp;</label>

              <div class="col-md-8">
                <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" placeholder="番地 建物名" />
                @error('address')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label for="name" class="col-md-4 col-form-label text-md-end">電話番号 <span class="text-red">（※）</span></label>

              <div class="col-md-8">
                <input id="tel" type="text" class="form-control @error('tel') is-invalid @enderror" name="tel" value="{{ old('tel') }}" autofocus>
                @error('tel')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>

            <div class="row mb-3">
              <label for="email" class="col-md-4 col-form-label text-md-end">メールアドレス <span class="text-red">（※）</span></label>

              <div class="col-md-8">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">
                <p class="text-anno">※弊社からのメールが届かない可能性がある為、「@icloud.com / @me.com / @mac.com」のメールアドレス以外を推奨いたします。</p>

                @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>

            <div class="row mb-3">
              <label for="password" class="col-md-4 col-form-label text-md-end">パスワード <span class="text-red">（※）</span></label>

              <div class="col-md-8">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>

            <div class="row mb-3">
              <label for="password-confirm" class="col-md-4 col-form-label text-md-end">パスワード（再入力） <span class="text-red">（※）</span></label>

              <div class="col-md-8">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                <p class="text-anno">※確認のためもう一度入力してください。</p>
              </div>
            </div>

            <div class="row mb-0">
              <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-info">
                  登録
                </button>
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
  .text-red {
    color: #f00;
    font-size: 0.9em;
  }
  .text-anno {
    color: #888;
    font-size: 0.9em;
    margin-bottom: 0;
  }
  .form-zip {
    display: inline-block;
    width: 200px;
  }
  .form-pref {
    display: inline-block;
    width: 200px;
  }
</style>
@stop


@section('js')
<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
@stop
