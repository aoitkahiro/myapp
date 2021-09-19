@extends('layouts.admin')
@section('title', 'Q')
@section('content')

<div class="container">
  <div id="QuizStart" onClick = "hoge()" class="btn btn-black">clickStart() ▶</div>
  <div class="text-center">
    <div>
    <h2 id="display">00:00</h2>
      <h3 id="js-question">
        - - - -
      </h3>
    </div>
    <div id="js-items" class="text">
        <p id="sound">　</p>
      <div class="m-2">
        <div><button type="button" id="js-btn-1" class="btn btn--yellow">{{$courses[0]->back}}</button></div>
      </div>
      <div class="m-2">
        <div><button type="button" id="js-btn-2" class="btn btn--yellow">dummy</button></div>
      </div>
      <div class="m-2">
        <button type="button" id="js-btn-3" class="btn btn--yellow">dummy</button>
      </div>
      <div class="m-2">
        <button type="button" id="js-btn-4" class="btn btn--yellow">dummy</button>
      </div>
    </div>
  <form name="recordtime"  method="post">
  @csrf
    <input type="hidden" name="score" id="score">
    <input type="hidden" name="running_time" id="running_time">
    <input type="hidden" name="course_id" id="course_id">
    <button type="button" id="save_button">記録を送信する</button>
  </form>
  </div>
</div>

  
@endsection
@section('js')
  <script>
    const courses = {!!$courses!!}; {{-- '$courses'を渡す時、' がquotと表示されてしまうのを防ぐため --}} 
    const dummy_courses =  {!!$dummy_courses!!};
    const dummy_answers = @json($dummy_answers);{{--@json とは配列をJavaScriptで扱いやすくしたデータ構造（詳しくしる）--}}
    const correct_and_dummy_answers = @json($correct_and_dummy_answers);
    let quiz = [];
    let counter = 0;
    courses.forEach(function(course){ 
      quiz.push({
        question: course.front,
        answer: course.back,
        selections: dummy_answers[counter],
        correct: course.back
      })
      counter++;
    })
    const $window = window;
    const $doc = document;
    const $question = $doc.getElementById('js-question');
    const $button = $doc.getElementsByTagName('button');
    const buttonLen = $button.length;
    
    const quizLen = quiz.length;
    let quizCount = 0;
    let score = 0;
    
    const setupQuiz = () => {
        $question.textContent = quiz[quizCount].question;
        document.getElementById('sound').textContent = "　";
        
        let new_value = [quiz[quizCount].answer];
        quiz[quizCount].selections.forEach(selection =>
          new_value.push(selection.back)
        )
         {{-- new_valueの中身は['正','誤','誤','誤'] --}} 
         {{-- new_value(配列)の中身をシャッフルする --}} 
        for (let i = new_value.length - 1; i >= 0; i--) {
         const randomNumber = Math.floor(Math.random() * (i + 1));
         [new_value[i], new_value[randomNumber]] = [new_value[randomNumber], new_value[i]];
        }
        console.log(new_value);
        
        let btnIndex = 0;
        
        while(btnIndex < buttonLen){ 
              $button[btnIndex].textContent = new_value[btnIndex];
              btnIndex++;
        }
        for(let i = 1; i <= buttonLen; i++){
        document.getElementById('js-btn-'+ i).className = "btn btn--yellow";
        }
    };
            {{-- ↓クリックされたボタンに基づいて、正誤文を出したり次の問題へ進める処理 --}} 
    const clickHandler = (elm) => { {{--elmとは、「eventの、targetである今clickされたbuttonを取得」--}}
        if(elm.textContent === quiz[quizCount].correct){
          elm.className = "btn btn-orange"
          document.getElementById('sound').textContent = "ピンポン♪";
          score++;
        
        } else {
          elm.className = "btn btn-black"
          document.getElementById('sound').textContent = "ブブー";
          }
        goToNext();
        
    };
    const goToNext = () => {
        quizCount++;
        if(quizCount < quizLen){
         setTimeout(function(){setupQuiz(quizCount)},2000);    
        } else {
          stopTheWatch();
          $window.alert('ピピ～！（笛の音を実装予定）');
          showEnd();
        }
    };
    
    const showEnd = () => {
        $question.textContent = '【成　績】　　' + score + '問 / ' + quizLen + '問中';
        
        const $items = $doc.getElementById('js-items');
        $items.style.visibility = 'hidden';
    };
    
    
    let handlerIndex = 0;
        {{-- let answersLen = quiz[quizCount].answers.length; --}} 
    function hoge(){
        document.getElementById('QuizStart').textContent = 'STARTED!!';
        setupQuiz();
    }
        {{-- ↓のclickによるイベント実行関数は（第1引数 実行条件, 第2引数 実行内容をクロージャーで行う --}}
    while(handlerIndex < buttonLen){
        {{-- ↓handlerIndex番めの<button>が'click'されたときに第2引数の関数が実行されますよ、という処理（何行も書かずにwhile文でrefactoringしている）--}}
        $button[handlerIndex].addEventListener('click', (e) => {  {{-- (e)は処理の中で使う --}} 
            {{-- 'click'した<target>の値を渡しながら処理clickHandler()を実行する（リレーをしている？） --}} 
        clickHandler(e.target);
            {{-- なぜe.targetという値を持っている？clickHandler(elm)なのに --}} 
        });
        handlerIndex++;
    }
    function stopTheWatch(){
          clearInterval(interval);
          startStop.innerHTML = "今回の記録";
          status = "stop";
    }
     {{-- 以下ストップウォッチのコード ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━--}} 
    
    let display = document.getElementById("display");
    let startStop = document.getElementById("QuizStart");
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
      display.innerHTML = zeroAndMinutes + ":" + zeroAndSeconds;
    };
    
    startStop.addEventListener("click", function(){
      if(status == "stop"){
          interval = setInterval(stopWatch, 1000); {{-- 0.01秒ごとに、stopWatch()を実行する--}}
          startStop.innerHTML = "Challenge!!";
          status = "start";
          
      }else{
          clearInterval(interval);
          startStop.innerHTML = "START";
          status = "stop";
      }
    })
     {{-- 
    reset.addEventListener("click", function(){
      clearInterval(interval);
      startStop.innerHTML = "START";
      status = "stop";
      display.innerHTML = "00:00:00";
      hours = 0;
      minutes = 0;
      seconds = 0;
      
      
    })
     --}} 
    
    document.getElementById('save_button').addEventListener('click', function(e){
      document.getElementById('running_time').value = 1000; {{-- 実際にかかった時間　idがtimeのタグ（inputタグ）のvalue属性に「実際にかかった時間を」代入する --}} 
      document.getElementById('score').value = 10; {{--正解数とか？ --}} 
      document.getElementById('course_id').value = courses[0].id;  
      document.forms['recordtime'].submit();  {{--このフォームの送信ボタンを押した時と同じ挙動をする <input type="submit" value="送信ボタン">のsubmitと同じ意味 --}} 
    })

  </script>

@endsection