@extends('layouts.admin')
@section('title', 'JavaScrip練習場')
@section('content')

<div class="container">
<button type="button" id="start" onClick='start()' class="btn btn-orange">スタート</button>
<div id='idDisplay'></div>
<button onclick="clickBtn1()">ボタン１</button>
<div class="row">改行用</div>
<button type="button" class="btn btn-primary" onclick="clickBtn2()">ボタン２”Change"</button>
    <br>
        <h1 id="target"> clickで、Changed! に変更したい文字列</h1>
    <br>
onclick=このボタンを押したときに動く関数を指定

<button type="button" class="btn btn-primary" onclick="clickBtn3()">ボタン３</button>
    <p id="target3" class="btn btn-primary" onclick="clickBtn3">こんにちは、こんばんは</p>
<br>
    <h1 id="display">00:00:00</h1>
    <div class="buttons">
        <div><button id="startStop">START</button></div>
        <div><button id="reset">RESET</button></div>
    </div>
    
<script>
    var i = 3; {{--カウント初期値--}}
    Display(i);
    var count = function(){ {{--カウント表示--}}
        Display(i--);
    }
    function start(){ {{--スタートボタン押したら起動--}}
        startButton = document.getElementById('start');
        var timer = setInterval(function(){
            count();
            if(i < 0){
                clearInterval(timer);
                Display('TIME UP');
            }
        },500); {{--動作確認を迅速にするため今だけ０．５秒カウント--}}
    }
    function Display(s){
         let oDisplay = document.getElementById('idDisplay');
         oDisplay.innerText = s;
    }
    
    let display = document.getElementById("display");
    let startStop = document.getElementById("startStop");
    let reset = document.getElementById("reset");
    
    let hours = 0;
    let minutes = 0;
    let seconds = 0;
    
    let zeroAndHours = 0;
    let zeroAndMinutes = 0;
    let zeroAndSeconds = 0;
    
    let status = "stop";
    let interval;
    
    function stopWatch(){
        seconds++;
        if(seconds / 60 == 1){
            minutes++;
            seconds = 0;
            if(minutes / 60 == 1){
                hours++;
                minutes = 0;
            }
        }
        
        if(seconds < 10){
            zeroAndSeconds = "0" + seconds;
        }else{
            zeroAndSeconds = seconds;
        }
        if(minutes < 10){
            zeroAndMinutes = "0" + minutes;
        }else{
            zeroAndMinutes = minutes;
        }
        if(hours < 10){
            zeroAndHours = "0" + hours;
        }else{
            zeroAndHours = hours;
        }
        display.innerHTML = zeroAndHours + ":" + zeroAndMinutes + ":" + zeroAndSeconds;
    };
    
    startStop.addEventListener("click", function(){
        if(status == "stop"){
            interval = setInterval(stopWatch, 10); {{-- 0.01秒ごとに、stopWatch()を実行する--}}
            startStop.innerHTML = "STOP";
            status = "start";
            
        }else{
            clearInterval(interval);
            startStop.innerHTML = "START";
            status = "stop";
        }
    })
        
    reset.addEventListener("click", function(){
        clearInterval(interval);
        startStop.innerHTML = "START";
        status = "stop";
        display.innerHTML = "00:00:00";
        hours = 0;
        minutes = 0;
        seconds = 0;
        
        
    })
    
    
    console.log('hello');
    
    function clickBtn1(){
        console.log('hello');
        alert('konnnichiha');
    };
    
    
    function clickBtn2(){
        document.getElementById('target').textContent = "Changed!";
    };
    
    function clickBtn3(){
        document.getElementById('target3').style.backgroundColor = "lime";
        document.getElementById('target3').style.color = "green";
        document.getElementById('target3').style.borderColor = "red";
        
    };

</script>
</div>
  
@endsection

{{--    

<h2 id="result"></h2>

    var DAYSTART = new Date('2017/03/28 00:00:00');
    var DAYEND   = new Date('2017/04/01 00:00:00');
    var INTERVAL = 1000;
    var calc = new Date(+DAYEND - DAYSTART + INTERVAL);
    function countdownTimer() {
    var addZero = function(n) { return ('0' + n).slice(-2); }
        if (+new Date(calc) <= INTERVAL) {
            document.getElementById('result').textContent = '終了しました。';
        } else {
            calc = new Date(+new Date(calc) - INTERVAL);
        var date = calc.getUTCDate() - 1 ? calc.getUTCDate() - 1 + '日' : '';
        var hours = calc.getUTCHours() ? calc.getUTCHours() + '時間' : '';
        var minutes = addZero(calc.getUTCMinutes()) + '分';
        var seconds = addZero(calc.getUTCSeconds()) + '秒';
        document.getElementById('result').textContent = date + hours + minutes + seconds;
        }
    }
    countdownTimer();
    setInterval(countdownTimer, INTERVAL);
--}}