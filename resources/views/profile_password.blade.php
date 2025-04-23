@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center mt-4">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">パスワードの変更</div>

        <div class="card-body">
          <form method="POST" action="{{ route('profile_password_update') }}">
            @csrf
            <div class="row mb-3">
              <label for="currentPassword" class="col-md-4 col-form-label text-md-end">現在のパスワード</label>
              <div class="col-md-8">
                <input id="currentPassword" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" value="" />
                @error('current_password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>

            <div class="row mb-3">
              <label for="newPassword" class="col-md-4 col-form-label text-md-end">新しく設定するパスワード</label>
              <div class="col-md-8">
                <input id="newPassword" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="" />
                @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label for="newPasswordConfirm" class="col-md-4 col-form-label text-md-end">新しく設定するパスワード（再入力）</label>
              <div class="col-md-8">
                <input id="newPasswordConfirm" type="password" class="form-control @error('password-confirm') is-invalid @enderror" name="password_confirmation" value="" />
                <p class="text-anno">※確認のためもう一度入力してください。</p>
                @error('password-confirm')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>

            <div class="row mb-0">
              <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-info">
                  変更
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
</style>
@stop
