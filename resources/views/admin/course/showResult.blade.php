@extends('layouts.admin')
@section('title', 'showResult')
@section('content')
<h1>showResult</h1>

{{$ranking_title}}
<br>xxx問 / 問中
<br>
<br>（メッセージ&画像 例）
<br>（自己ベストです！お見事！）自己ベストの場合
<br>（すごい！満点！）100％
<br>（8割越えですか…なかなかやりますね）80％以上
<br>（平均以上です。その調子！）50%以上
<br>（たまには休憩してね）50%未満
<br>
<br>[もう一度]　[もどる]　[ランキング]
<br>
<br>今回間違えた単語
<br>

@endsection