@extends('layouts.app')

@section('content')
<div class="album py-5 bg-light">
  <div class="container">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
      {{ session('status') }}
    </div>
    @endif
    <div class="card">
      <div class="card-header">
        <p class="mb-0">エラー</p>
      </div>
      <div class="card-body">
        <p>{{ $message ?? ''}}</p>
      </div>
      <div class="card-footer text-center">
        <a class="btn btn-secondary" href="/">TOPページに戻る</a>
      </div>
    </div> <!-- card.// -->
  </div>
</div>

@endsection


@section('css')
<style type="text/css">
</style>
@stop

@section('js')
<script type="text/javascript">
</script>
@stop
