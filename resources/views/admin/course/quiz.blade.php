@extends('layouts.admin')
@section('title', 'Q')
@section('content')

<div class="container">
  <div id="QuizStart" onClick = "startQuiz()" class="btn btn-black">clickStart() ▶</div>
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
        <div><button type="button" id="js-btn-1" class="btn btn--yellow selection">{{$courses[0]->back}}</button></div>
      </div>
      <div class="m-2">
        <div><button type="button" id="js-btn-2" class="btn btn--yellow selection">dummy</button></div>
      </div>
      <div class="m-2">
        <button type="button" id="js-btn-3" class="btn btn--yellow selection">dummy</button>
      </div>
      <div class="m-2">
        <button type="button" id="js-btn-4" class="btn btn--yellow selection">dummy</button>
      </div>
    </div>
  <form name="recordtime"  method="post">
  @csrf
    <input type="hidden" name="score" id="score">
    <input type="hidden" name="user_quiz_result" id="user_quiz_result">
    <input type="hidden" name="running_time" id="running_time">
    <input type="hidden" name="result" id="result">
    <input type="hidden" name="course_id_array" id="course_id_array">{{--要確認（何のためにRequestへinputしているのか--}}
    <input type="hidden" name="result_items" id="result_items">{{--要確認（何のためにRequestへinputしているのか--}}
    <input type="hidden" name="challenge_id" id="challenge_id">
    <input type="hidden" name="ArrayForHistoriesChange[]" id="ArrayForHistoriesChange">
    <p><input type="checkbox" name="forgotten" id="forgotten" value="1"> 間違えた語の[覚えた]を解除</p>
    <button type="button" id="save_button">記録を送信する</button>
  </form>
  </div>
</div>
  <p class="margin_bottom_2"></p>
<div class="container">
  <p id="">"{{$courses[0]->category}}"に挑戦した人たち</p>
  {{--@foreach($hoge as $h)--}}
    <li class="Ranking"> {{$result->user_id}} さん 記録：???秒challengeidごとの最大秒</li>
  {{--@endforeach  --}}
</div>
<div class="container">
  <p class="margin_bottom_2"></p>
  <a href="{{action('Admin\CourseController@quiz')}}" type="button" id="restart" class="btn btn-black"><h2>↺</h2><br><h8>もう一度</h8></a>
  <a href="{{action('Admin\CourseController@index')}}" type="button" id="goIndex" class="btn btn-black"><h2>↩</h2><br><h8>もどる</h8></a>

    @foreach ($courses as $course)
      <li class="list-group-item"><span id="judgement{{ $course->id }}"> </span> {{$course->front}}<br>  {{$course->back}}</li>
    @endforeach
</div>

  
@endsection
@section('js')

  <script>
    const courses = {!!$courses!!}; {{-- '$courses'を渡す時、' がquotと表示されてしまうのを防ぐため --}}
    const dummy_courses =  {!!$dummy_courses!!};
    const dummy_answers = @json($dummy_answers);{{--@json とは配列をJavaScriptで扱いやすくしたデータ構造（詳しくしる）--}}
    const correct_and_dummy_answers = @json($correct_and_dummy_answers);
    const challenge_id =  {!!$challenge_id!!};
    let quiz = [];
    let course_id_array = [];
    let result_items = [];
    let counter = 0;
    console.log(courses);
    courses.forEach(function(course){ 
      quiz.push({
        question: course.front,
        answer: course.back,
        selections: dummy_answers[counter],
        correct: course.back
      })
      course_id_array.push(course.id);
      counter++;
    })
    console.log(course_id_array);
    const $window = window;
    const $doc = document;
    const $question = $doc.getElementById('js-question');
    let $buttons = $doc.getElementsByClassName('selection');
    let buttonLen = $buttons.length;
    const quizLen = quiz.length;
    let quizCount = 0;
    let score = 0;
    let running_time = "";{{--runnning timeミリ秒保存用 例"1000 2000 2200" --}} 
    let result = "";
    let ArrayForHistoriesChange = [];
    
    const setupQuiz = () => {
        console.log("setquiz関数が呼ばれました");
        $question.textContent = quiz[quizCount].question;
        $buttons = $doc.getElementsByClassName('selection');
        console.log($buttons);
        buttonLen = $buttons.length;
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
        console.log("new_value = " + new_value);
        
        let btnIndex = 0;
        console.log("btnIndex:" + btnIndex + "  buttonLen:" + buttonLen)
        console.log($buttons);
        while(btnIndex < buttonLen){
              if($buttons[btnIndex]){
                    console.log("正常  btnindex:" + btnIndex);
                    console.log($buttons[btnIndex]);
                      $buttons[btnIndex].textContent = new_value[btnIndex];
                      btnIndex++;
              }else{
                   console.log("異常  btnindex:" + btnIndex)
                       btnIndex++;  
              }
        }

        for(var i = 1; i <= buttonLen; i++){
          document.getElementById('js-btn-'+ i).className = "btn btn--yellow selection";
        }
    };
            {{-- ↓クリックされたボタンに基づいて、正誤文を出したり次の問題へ進める処理 --}} 
    const clickHandler = (elm) => { {{--elmとは、「eventの、targetである今clickされたbuttonを取得」--}}
        if(elm.textContent === quiz[quizCount].correct){
          elm.className = "btn btn-orange selection"
          document.getElementById('sound').textContent = "ピンポン♪";
          score++;
          result = result + "2" + " ";
          rslt = 2;
          ArrayForHistoriesChange.push(2);
          } else {
          elm.className = "btn btn-black selection"
          document.getElementById('sound').textContent = "ブブー";
          result = result + "1" + " ";
          rslt = 1;
          ArrayForHistoriesChange.push(1);
        }
        
        result_items.push({
            rslt: rslt,
            rng_time: zeroAndMinutes + zeroAndSeconds,
        })
        console.log(result_items);
        running_time = running_time + zeroAndMinutes + zeroAndSeconds + "/";{{-- ++と書ける？ --}}
        console.log(running_time);
        console.log("結果："+ result);
        goToNext();
    };
    
    const goToNext = () => {
        quizCount++;
        if(quizCount < quizLen){
          setTimeout(function(){setupQuiz(quizCount)},500);    
        } else {
          stopTheWatch();
          judgeString = result.replace(/0|1/g, '✖');
          judgeString = judgeString.replace(/2/g, '〇'); 
          judgeString = (judgeString.trimEnd());
          const someJudgements = judgeString.split(" ");
          console.log(someJudgements);
          let count = 0;
          console.log(courses);
          console.log(courses[count]);
          console.log(courses[count].id);
          console.log(ArrayForHistoriesChange);
          someJudgements.forEach((judgement) => {
          document.getElementById("judgement"+ courses[count].id).textContent = judgement;
          count++;
          });
          showEnd();
        };
    };
    
    const showEnd = () => {
        $question.textContent = '【成　績】　　' + score + '問 / ' + quizLen + '問中';
        
        const $items = $doc.getElementById('js-items');
        $items.style.visibility = 'hidden';
        
        
    };
    
    
    let handlerIndex = 0;
        {{-- let answersLen = quiz[quizCount].answers.length; --}} 
    function startQuiz(){
        document.getElementById('QuizStart').textContent = 'STARTED!!';
        setupQuiz();
    }
        {{-- ↓のclickによるイベント実行関数は（第1引数 実行条件, 第2引数 実行内容をクロージャーで行う --}}
    while(handlerIndex < buttonLen){
        {{-- ↓handlerIndex番めの<button>が'click'されたときに第2引数の関数が実行されますよ、という処理（何行も書かずにwhile文でrefactoringしている）--}}
        $buttons[handlerIndex].addEventListener('click', (e) => {  {{-- (e)は処理の中で使う --}} 
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
      console.log(running_time);
      document.getElementById('running_time').value = running_time;
      document.getElementById('result').value = result;
      console.log("コンソールログです");
      console.log(2 + "問正解とレコード登録します");
      document.getElementById('challenge_id').value = challenge_id;  
      document.getElementById('course_id_array').value = JSON.stringify(course_id_array);
      document.getElementById('result_items').value = JSON.stringify(result_items);
      document.getElementById('ArrayForHistoriesChange').value = ArrayForHistoriesChange;
      document.forms['recordtime'].submit();
        {{--このフォームの送信ボタンを押した時と同じ挙動をする <input type="submit" value="送信ボタン">のsubmitと同じ意味 --}} 
    })
    
    $('#record_result_submit').click(function(){
      $('#time').val() = //JSの変数上に存在する、かかった時間の値
        {{--$('#score').val() = //JSの変数上に存在する、かかったスコアの値--}}
      $('#record_result_form').submit()
    })

  </script>

@endsection