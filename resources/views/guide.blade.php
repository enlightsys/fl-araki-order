@extends('layouts.app')

@section('content')
  <div class="container py-4">
    <h3>ご利用ガイド</h3>
    <div class="row">
      <div class="col-sm-6">
        <video id="media" class="mb-5 border rounded" webkit-playsinline playsinline autoplay muted controls>
          <source src="/assets/movie/guide.mp4" type="video/mp4">
          <p>ご使用のブラウザでは動画再生に対応していません</p>
        </video>
      </div>
    </div>
  </div>

@endsection

@section('css')
<style type="text/css">
  #media {
    width: 100%;
  }
</style>
@stop

@section('js')
<script>
$(function(){
});
</script>
@stop
