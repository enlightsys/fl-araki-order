@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center mt-4">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">会員情報の変更</div>

        <div class="card-body">
          <p>お客様情報を入力してください。<span class="text-red">（※）</span>は必須項目です。</p>
          <form method="POST" action="{{ route('profile_update') }}">
            @csrf
            <div class="row mb-3">
              <label for="name" class="col-md-4 col-form-label text-md-end">お名前 <span class="text-red">（※）</span></label>

              <div class="col-md-8">
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $member['name']) }}" autocomplete="name" autofocus>
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
                <input id="contact_name" type="text" class="form-control @error('contact_name') is-invalid @enderror" name="contact_name" value="{{ old('contact_name', $member['contact_name']) }}" autofocus>
                <p class="text-anno">↑ご担当者様がいらっしゃる場合はお名前をご記入ください。</p>
                @error('contact_name')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
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
</style>
@stop
