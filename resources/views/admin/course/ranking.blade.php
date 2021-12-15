@extends('layouts.admin')
@section('title', 'R')
@section('content')

<div class="container py-3">
    <h2>{{$ranking_title}}</h2>
  　<p>　1日1個 自己ベストが残ります</p>
    <div class="row">
        <div class="list-rank col-md-12 mx-auto">
            <div class="row">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr style="vertical-align: middle">
                            <th width="10%" class="text-center">順位</th>
                            <th width="10%" class="text-center"><span class="half_font">PLAYER</span></th>
                            <th width="15%">名前</th>
                            <th width="10%" class="text-center">正解数</th>
                            <th width="10%">タイム</th>
                            <th width="10%">挑戦日</th>
                            <th width="35%">今の目標</th>
                        </tr>
                    </thead>
                    <tbody>
                      @php $i = 0; @endphp
                      @foreach($rankings as $rank)
                        <tr class="Ranking">
                          @if($i == 0)
                            <td class= "d-flex align-items-center justify-content-center"><h1>👑</h1></td>
                            <td class= "text-center" style="vertical-align: middle"><img class="gazou" style="max-height:70px;" src="{{ asset('storage/tango/' . $rank["画像"]) }}"></td>
                            <td style= "vertical-align: middle">{{$rank["name"]}}</td>
                            <td class= "text-center" style="vertical-align: middle">{{$rank["正解回数"]}}</td>
                            <td style= "vertical-align: middle">{{$rank["タイム"]}}<span class="half_font">秒</span></td>
                            <td style= "vertical-align: middle">{{$rank["挑戦日"]}}</td>
                            <td style= "vertical-align: middle">{{ \Str::limit($rank["目標"], 15) }}</td>
                          @else
                            <td class="d-flex align-items-center justify-content-center">
                          　@if($i == 1)
                          　 <span class="per200_font">🥈</span>
                          　@elseif($i == 2)
                          　 <span class="per200_font">🥉</span>
                          　@else
                          　  <spa style="text-align: center">{{$i + 1}}</span>
                            @endif
                          　</td>
                            <td style="vertical-align: middle" class="text-center"><img class="gazou" style="max-height:30px;" src="{{ asset('storage/tango/' . $rank["画像"]) }}"></td>
                            <td style="vertical-align: middle">{{$rank["name"]}}</td>
                            <td style="vertical-align: middle" class="text-center">{{$rank["正解回数"]}}</td>
                            <td style="vertical-align: middle">{{$rank["タイム"]}}<span class="half_font">秒</span></td>
                            <td style="vertical-align: middle">{{$rank["挑戦日"]}}</td>
                            <td style="vertical-align: middle">{{$rank["目標"]}}</td>
                          @endif
                        </tr>
                      @php $i++; @endphp
                      @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
  <p class="margin_bottom_2"></p>
<div class="container py-3">
  <a href="{{action('Admin\CourseController@quiz',['category'=>$category, 'question_quantity'=>$question_quantity])}}" type="button" id="goIndex" class="btn btn-black"><h1>↩</h1><h8>もどる</h8></a>
</div>

@endsection