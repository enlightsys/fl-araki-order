@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center mt-4">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">会員情報の確認</div>

        <div class="card-body">
          <h5>会員メールアドレス</h5>
          <div class="row mb-3">
            <label for="name" class="col-md-4 col-form-label text-md-end">メールアドレス</label>

            <div class="col-md-8">
              <p class="form-control-plaintext">{{ $member['email'] }}</p>
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-md-6 offset-md-4">
              <a class="btn btn-info" href="/profile_email">
                メールアドレスの変更
              </a>
            </div>
          </div>

          <h5>パスワード</h5>
          <div class="row mb-4">
            <div class="col-md-6 offset-md-4">
              <a class="btn btn-info" href="/profile_password">
                パスワードの変更
              </a>
            </div>
          </div>
          <h5>会員情報詳細</h5>
          <div class="row mb-0">
            <label for="name" class="col-md-4 col-form-label text-md-end">お名前</label>

            <div class="col-md-8">
              <p class="form-control-plaintext">{{ $member['name'] }}</p>
            </div>
          </div>
          <div class="row mb-3">
            <label for="name" class="col-md-4 col-form-label text-md-end">ご担当者名</label>

            <div class="col-md-8">
              <p class="form-control-plaintext">{{ $member['contact_name'] }}</p>
            </div>
          </div>

          <div class="row mb-0">
            <div class="col-md-6 offset-md-4">
              <a class="btn btn-info" href="/profile_edit">
                会員情報の変更
              </a>
            </div>
          </div>
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
