@extends('layouts.admin')
@section('title', 'Q')
@section('content')

<div class="container">
  <div id="QuizStart" onClick = "clickStart()">QuizStart</div>
  <div class="jumbotron mt-5 text-center">
    <div>
      <h4>この単語は何ですか？▼</h4>
      <h1 id="js-question">
        - - - -
      </h1>
    </div>
      <br><br><br><br><br>
    <div id="js-items" class="text">
        <p id="sound">　</p>
      <div class="m-2">
        <div><button type="button" id="js-btn-1" class="btn btn-orange">{{$answer_back}}</button></div>
      </div>
      <div class="m-2">
        <div><button type="button" id="js-btn-2" class="btn btn-orange">Primary</button></div>
      </div>
      <div class="m-2">
        <button type="button" id="js-btn-3" class="btn btn-orange">Primary</button>
      </div>
      <div class="m-2">
        <button type="button" id="js-btn-4" class="btn btn-orange">Primary</button>
      </div>
    </div>
  </div>
</div>

  
@endsection
@section('js')
  <script>
    var word = "{{$word}}"
    var question = "{{$question}}"
    var question_front = "{{$question_front}}"
    var answer_back = "{{$answer_back}}"
    alert(answer_back);
    
    const quiz = [{{--６．データベースから以下の要素を引っ張ってくるように変更する（ループを使う）--}}
      {
        question: question_front,
        answers: [ answer_back, '予想する', '引っ張る', '射る'],
        correct: answer_back
      }, {
        question: 'tiger',
        answers: [ '犬', '猫', 'トラ', 'ライオン'],
        correct: 'トラ'
      }, {
        question: 'product',
        answers: [ '製品', '雇用', '商品', '企画'],
        correct: '製品'
      }
    ];
    
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
      let btnIndex = 0;
      
      while(btnIndex < buttonLen){
        $button[btnIndex].textContent = quiz[quizCount].answers[btnIndex];
        btnIndex++;
      }
      document.getElementById('js-btn-1').className = "btn btn-orange";
      document.getElementById('sound').textContent = "　";
    };
         {{-- ↓クリックされたボタンに基づいて、正誤文を出したり次の問題へ進める処理 --}} 
    const clickHandler = (elm) => { {{--elmとは、「eventの、targetである今clickされたbuttonを取得」--}}
      if(elm.textContent === quiz[quizCount].correct){
      elm.className = "btn btn--yellow";{{--５．idを変数にする--}}
      document.getElementById('sound').textContent = "ピンポン♪";
      {{--１．<p>タグなどで、正解！と出すコードを書く--}}
        score++;
        
      } else {
      elm.className = "btn btn--yellow"
      document.getElementById('sound').textContent = "ブブー";
      }
      {{--２．300ミリ秒待つコードを書く--}}
      {{--４．正解ボタンの色を元に戻す--}}
      {{--５．０．クイズのスタートボタンを作る--}}
      goToNext();
      
    };
    const goToNext = () => {
      quizCount++;
      if(quizCount < quizLen){
        setTimeout(function(){setupQuiz(quizCount)},2000);
      } else {
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
    function clickStart(){
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
  </script>

@endsection