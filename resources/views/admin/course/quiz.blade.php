@extends('layouts.admin')
@section('title', 'Q')
@section('content')

<p class ="text-center">{{$ranking_title}}</p>
<div class="container">
    
    <div class="text-center">
      <br>
      <div>
      <h2 id="display" class="sample2">00:00:00</h2>
        <h3 id="js-question">
          - - - -
        </h3>
      </div>
      <div id="js-items" class="text margin_bottom_2px">
          <div id="sound"><div id="QuizStart" onClick = "startQuiz()" class="btn btn-orange eye_catching_word">▶</div></div>
        <div class="m-2">
          <div><button type="button" id="js-btn-1" style="min-width:50%" class="btn btn--yellow selection">???</button></div>
        </div>
        <div class="m-2">
          <div><button type="button" id="js-btn-2" style="min-width:50%" class="btn btn--yellow selection">???</button></div>
        </div>
        <div class="m-2">
          <button type="button" id="js-btn-3" style="min-width:50%" class="btn btn--yellow selection">???</button>
        </div>
        <div class="m-2">
          <button type="button" id="js-btn-4" style="min-width:50%" class="btn btn--yellow selection">???</button>
        </div>
      </div>
      <form name="recordtime"  method="post">
      @csrf
        <input type="hidden" name="score" id="score">
        <input type="hidden" name="user_quiz_result" id="user_quiz_result">
        <input type="hidden" name="running_time" id="running_time">
        <input type="hidden" name="result" id="result">
        <input type="hidden" name="course_id_array" id="course_id_array">
        <input type="hidden" name="result_items" id="result_items" value="">
        <input type="hidden" name="challenge_id" id="challenge_id">
        <input type="hidden" name="resultArray[]" id="resultArray">
        <input type="hidden" name="category" value={{urlencode($category)}}>
        <input type="hidden" name="question_quantity" value={{$question_quantity}}>
        <input type="hidden" name="forgotten" value="0" >
        {{-- FIXME:
        <button type="button" id="save_button" class = "margin_bottom_2em">記録を送信する</button>
        <div class="margin_bottom_2px"><input type="checkbox" {{ $forgotten == "0" ? ""  : "checked" }} class="sample2" name="forgotten"> 間違えた語の[覚えた]を解除</div>
        --}}
      </form>
    </div>
  <div class="text-center margin_bottom_2px">
    <a href="{{action('Admin\CourseController@quiz',['category'=>$category, 'question_quantity'=>$question_quantity])}}" type="button" id="restart" class="btn btn-black col-3 margin_top_20px"><span class="eye_catching_word">↻</span> やりなおす</a>
    <span class= text-center id="wrongList"></span>
  </div>
</div>

@endsection
@section('js')

<script>

  
  const courses = {!!$courses!!}; {{-- '$courses'を渡す時、' がquotと表示されてしまうのを防ぐため --}}
  const dummy_courses =  {!!$dummy_courses!!};
  const dummy_answers = @json($dummy_answers);
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
  let whereQuizNum = 1;
  let score = 0;
  let running_time = "";{{--runnning timeミリ秒保存用 例"1000 2000 2200" --}} 
  let result = "";
  let resultArray = [];
  const selection1 = document.getElementById('js-btn-1');
  const selection2 = document.getElementById('js-btn-2');
  const selection3 = document.getElementById('js-btn-3');
  const selection4 = document.getElementById('js-btn-4');
    
  const selections = [selection1, selection2, selection3, selection4];
  const soundPinpon = new Audio("{{secure_asset('music/sound_pinpon.mp3')}}");
  
  const setupQuiz = () => {
      $question.textContent = quiz[quizCount].question;
      $buttons = $doc.getElementsByClassName('selection');
      buttonLen = $buttons.length;
      document.getElementById('sound').textContent = whereQuizNum + "問目 / " + quizLen + "問";
      
      let new_value = [quiz[quizCount].answer];
      quiz[quizCount].selections.forEach(selection =>
        new_value.push(selection.back)
      )
       {{--NOTE: new_valueの中身は['正','誤','誤','誤'] --}} 
       {{--NOTE:  new_value(配列)の中身をシャッフルする --}} 
      for (let i = new_value.length - 1; i >= 0; i--) {
       const randomNumber = Math.floor(Math.random() * (i + 1));
       [new_value[i], new_value[randomNumber]] = [new_value[randomNumber], new_value[i]];
      }
      
      let btnIndex = 0;
      while(btnIndex < buttonLen){
            if($buttons[btnIndex]){
                    $buttons[btnIndex].textContent = new_value[btnIndex];
                    btnIndex++;
            }else{
                     btnIndex++;  
            }
      }

      for(var i = 1; i <= buttonLen; i++){
        document.getElementById('js-btn-'+ i).className = "btn btn--yellow selection";
      }
  };
  
  function wait(ms) {
    return new Promise( resolve => { setTimeout( resolve, ms ) } ); {{--NOTE: Promiseとawaitはセット  --}} 
  };
  function activeAllSelections(){
    selections.forEach((s)=>{
      s.disabled = false;
    })
  }
  
  function disabledAllSelections(){
    selections.forEach((s)=>{
      s.disabled = true;
    })
  }
  {{--NOTE:  ↓クリックされたボタンに基づいて、正誤文を出したり次の問題へ進める処理 --}} 
  async function clickHandler (elm) { {{--NOTE: elmとは、「eventの、targetである今clickされたbuttonを取得」--}}
      if(quizCount + 1  == quizLen){ {{--NOTE: もし最終問題なら、clickした瞬間にタイマーを止めるという仕様--}}
        stopTheWatch();
      }
      if(elm.textContent === quiz[quizCount].correct){
        elm.className = "btn btn-orange selection"
        document.getElementById('sound').textContent = "ピンポン♪";
        soundPinpon.play();
        score++;
        result = result + "2" + " ";
        rslt = 2;
        resultArray.push(2);
        disabledAllSelections();
        waitTime = 700;
      } else {
        elm.className = "btn btn-black selection"
        document.getElementById('sound').textContent = quiz[quizCount].correct + " が正解です";
        result = result + "1" + " ";
        rslt = 1;
        resultArray.push(1);
        disabledAllSelections();
        waitTime = 1100;
      }
      await wait(waitTime);{{--await：ここ（wait()）が終わるまでは進まないことを保証。関数にasyncも記述するのが規則--}}
      result_items.push({
          quiz: quiz[quizCount],
          rslt: rslt,
          rng_time: Math.round(runningTime * 100) /100,
      })
      running_time = running_time + zeroAndMinutes + zeroAndSeconds + "/";{{-- ++と書ける？ --}}
      goToNext();
  };
  
  const goToNext = () => {
      quizCount++;
      if(quizCount < quizLen){
      activeAllSelections();
        {{--setTimeout(function(){setupQuiz(quizCount)},500);--}} 
        whereQuizNum++;
        setupQuiz(quizCount);
      } else {
        stopTheWatch();
        judgeString = result.replace(/0|1/g, '✖');
        judgeString = judgeString.replace(/2/g, '〇'); 
        judgeString = (judgeString.trimEnd());
        let someJudgements = judgeString.split(" ");
        let count = 0;
        let i = 0;
        let currenctCourseIds = [];
        courses.forEach((course) =>{
          currenctCourseIds.push(courses[i].id);
          i++
        });
        
        function createObject(keys, values) {
	        let outputObject = {}; 
  	 
        	{{--NOTE: 配列の長さが一致しているか確認--}} 
        	if (keys.length != values.length) { 
            	console.error("配列の長さが一致しないので、空を返します"); 
            	return outputObject; 
          } 
        	 
        	{{--NOTE: 両方の配列をループしてオブジェクトに追加--}} 
        	for (let i = 0; i < keys.length; ++i) { 
            	let key = keys[i]; 
            	let value = values[i]; 
            	 
          	outputObject[key] = value; 
          } 
        	return outputObject;
        };
        let keys = currenctCourseIds; 
        let values = someJudgements; 
        let myObject = createObject(keys, values); 
        let key = Object.keys(myObject);
         
        showEnd(result_items);
      };
  };
  {{--無名関数をshowEnd()に入れるという書き方--}}
  const showEnd = (result_items_array) => {
      $question.textContent = score + '問 / ' + quizLen + '問中';
      console.log(result_items_array);
      let correctRatio = score / quizLen;
      const $items = $doc.getElementById('js-items');
      let hoge = document.getElementById('result_items');
      hoge.value  = JSON.stringify(result_items_array);
      document.getElementById('course_id_array').value = JSON.stringify(course_id_array);
      document.getElementById('challenge_id').value = challenge_id; 
      let save_button = document.getElementById('save_button');{{--OPTIMIZE--}}
      document.forms['recordtime'].submit();
      
  };
  
  
  let handlerIndex = 0;
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
        startStop.innerHTML = "成 績";
        status = "stop";
  }
  
   {{-- 以下ストップウォッチのコード--}} 
  
  let display = document.getElementById("display");
  let startStop = document.getElementById("QuizStart");
  let reset = document.getElementById("reset");
  
  let hours = 0;
  let minutes = 0;
  let seconds = 0;
  let oneHandredthOfSeconds = 0;
  let runningTime = 0;
  
  let zeroAndHours = 0;
  let zeroAndMinutes = 0;
  let zeroAndSeconds = 0;
  let zeroAndoneHandredthOfSeconds = 0;
  
  let status = "stop";
  let interval;
  
  function stopWatch(){
    runningTime += 0.01;
    oneHandredthOfSeconds++;
    if(oneHandredthOfSeconds / 100 == 1){
        seconds++;
        oneHandredthOfSeconds = 0;
        if(seconds / 60 == 1){
            minutes++;
            seconds = 0;
            if(minutes / 60 == 1){
                hours++;
                minutes = 0;
            }
        }
    }
    
    if(oneHandredthOfSeconds < 10){
        zeroAndoneHandredthOfSeconds = "0" + oneHandredthOfSeconds;
    }else{
        zeroAndoneHandredthOfSeconds = oneHandredthOfSeconds;
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
    display.innerHTML = zeroAndMinutes + ":" + zeroAndSeconds + ":" + zeroAndoneHandredthOfSeconds;
  };
  
  startStop.addEventListener("click", function(){
    if(status == "stop"){
        interval = setInterval(stopWatch, 10); {{-- 0.01秒ごとに、stopWatch()を実行する--}}
        startStop.innerHTML = "Challenge!!";
        status = "start";
        
    }else{
        clearInterval(interval);
        startStop.innerHTML = "START";
        status = "stop";
    }
  })
  
  
  document.getElementById('save_button').addEventListener('click', function(e){
    document.getElementById('running_time').value = running_time;
    document.getElementById('result').value = result;
    document.getElementById('challenge_id').value = challenge_id;  
    document.getElementById('course_id_array').value = JSON.stringify(course_id_array);
    document.getElementById('result_items').value = JSON.stringify(result_items);
    document.getElementById('resultArray').value = resultArray;
    document.forms['recordtime'].submit();
      {{--NOTE: このフォームの送信ボタンを押した時と同じ挙動をする <input type="submit" value="送信ボタン">のsubmitと同じ意味 --}} 
  })
  
  $('#record_result_submit').click(function(){
    $('#time').val() = //JSの変数上に存在する、かかった時間の値
      {{--$('#score').val() = //JSの変数上に存在する、かかったスコアの値--}}
    $('#record_result_form').submit()
  })

</script>

@endsection