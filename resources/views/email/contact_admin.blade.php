お問い合わせがありました。

■お問い合わせの種類
@if ($vars->kind1 ?? false) ご注文について @endif
@if ($vars->kind2 ?? false) その他のご相談 @endif


■お名前
{{ $vars->name }}

■郵便番号
{{ $vars->zip }}

■ご住所
@foreach (config('const.pref') as $pref_id => $pref)
@if ($vars->pref_id == $pref_id) {{ $pref }} @endif
@endforeach
{{ $vars->city }} {{ $vars->address }}

■メールアドレス
{{ $vars->email }}

■電話番号
{{ $vars->tel }}

■お問い合わせ内容
{{ $vars->message }}

