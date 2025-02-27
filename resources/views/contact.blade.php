@extends('layouts.app')

@section('content')
<!-- Contact us area start -->
  <div class="container py-4">
    <h3>お問い合わせ</h3>
    <div class="row justify-content-center mt-4">
      <div class="col-md-10">
        <div class="card">
          <div class="card-header">商品のご注文やお問い合わせは下記フォームからご相談ください。（＊）は必須項目です。</div>
          <div class="card-body">
            <form class="contact-form-box mb-5" id="contact-form" action="{{ route('contact_confirm') }}" method="post">
              @csrf
              <div class="form-group row">
                <div class="col-sm-3">お問い合わせの種類（＊）</div>
                <div class="col-sm-9">
                  <div class="form-check">
                    <input class="form-check-input" name="kind1" value="1" type="checkbox" id="gridCheck1" @if (old('kind1')) checked="checked" @endif  />
                    <label class="form-check-label" for="gridCheck1">
                      ご注文について
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" name="kind2" value="1" type="checkbox" id="gridCheck2" @if (old('kind2')) checked="checked" @endif  />
                    <label class="form-check-label" for="gridCheck2">
                      その他のご相談
                    </label>
                  </div>
                  @error('kind1')<p class="text-danger mt-2">{{ $message }}</p>@enderror
                </div>
              </div>
              <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-form-label">お名前（＊）</label>
                <div class="col-sm-9">
                  <input type="text" name="name" class="form-control" id="inputName" placeholder="お名前（フルネーム）" value="{{ old('name') }}" />
                  @error('name')<p class="text-danger">{{ $message }}</p>@enderror
                </div>
              </div>

              <div class="form-group row">
                <label for="inputZip" class="col-sm-3 col-form-label">郵便番号</label>
                <div class="col-sm-3">
                  <input type="text" name="zip" class="form-control" id="inputZip" placeholder="123-4567" value="{{ old('zip') }}" />
                  @error('zip')<p class="text-danger">{{ $message }}</p>@enderror
                </div>
              </div>
              <div class="form-group row">
                <label for="inputPrefId" class="col-sm-3 col-form-label">都道府県</label>
                <div class="col-sm-3">
                  <select class="form-control" name="pref_id" id="inputPrefId">
                    <option value="">----</option>
                    @foreach (config('const.pref') as $pref_id => $pref)
                    <option value="{{ $pref_id }}" @if (old('pref_id') == $pref_id) selected="selected" @endif>{{ $pref }}</option>
                    @endforeach
                  </select>
                  @error('pref_id')<p class="text-danger">{{ $message }}</p>@enderror
                </div>
              </div>

              <div class="form-group row">
                <label for="inputCity" class="col-sm-3 col-form-label">市区町村</label>
                <div class="col-sm-9">
                  <input type="text" name="city" class="form-control" id="inputCity" placeholder="○○○市○○○区" value="{{ old('city') }}" />
                  @error('city')<p class="text-danger">{{ $message }}</p>@enderror
                </div>
              </div>
              <div class="form-group row">
                <label for="inputAddress" class="col-sm-3 col-form-label">丁目番地</label>
                <div class="col-sm-9">
                  <input type="text" name="address" class="form-control" id="inputAddress" placeholder="○○○1-2-3" value="{{ old('address') }}" />
                  @error('address')<p class="text-danger">{{ $message }}</p>@enderror
                </div>
              </div>

              <div class="form-group row">
                <label for="inputEmail3" class="col-sm-3 col-form-label">メールアドレス（＊）</label>
                <div class="col-sm-9">
                  <input type="email" name="email" class="form-control" id="inputEmail3" placeholder="user@example.com" value="{{ old('email') }}" />
                  @error('email')<p class="text-danger">{{ $message }}</p>@enderror
                </div>
              </div>
              <div class="form-group row">
                <label for="inputTel" class="col-sm-3 col-form-label">電話番号</label>
                <div class="col-sm-4">
                  <input type="text" name="tel" class="form-control" id="inputTel" placeholder="011-222-3333" value="{{ old('tel') }}" />
                  @error('tel')<p class="text-danger">{{ $message }}</p>@enderror
                </div>
              </div>
              <div class="form-group row">
                <label for="inputMessage" class="col-sm-3 col-form-label">お問い合わせ内容</label>
                <div class="col-sm-9">
                  <textarea name="message" class="form-control" id="inputMessage" rows="4" placeholder="こちらに詳しくご記入ください">{{ old('message') }}</textarea>
                  @error('message')<p class="text-danger">{{ $message }}</p>@enderror
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-12 text-center">
                  <button type="submit" class="btn btn-info">入力内容を確認する</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
<!-- Contact us area end -->

@endsection

@section('css')
<style type="text/css">
</style>
@stop
