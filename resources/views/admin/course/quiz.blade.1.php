@extends('layouts.admin')
@section('title', 'クイズ')
@section('content')

<div class="container">
Quiz
    <div class="jumbotron mt-5">
      <div class="d-flex justify-content-center">
        <div id="js-question" class="alert alert-primary" role="alert">
          A simple primary alert—check it out!
        </div>
      </div>
      
      <div id="js-items" class="d-flex justify-content-center">
        <div class="m-2">
          <button type="button" id="js-btn-1" class="btn btn--yellow btn--cubic">Primary</button>
        </div>
        <div class="m-2">
          <button type="button" id="js-btn-2" class="btn btn--yellow btn--cubic">Primary</button>
        </div>
        <div class="m-2">
          <button type="button" id="js-btn-3" class="btn btn-primary">Primary</button>
        </div>
        <div class="m-2">
          <button type="button" id="js-btn-4" class="btn btn-primary">Primary</button>
        </div>
      </div>
    </div>
    <script>
      const quiz = [
        {
          question: 'ecxtend',
          answers: [ '引き継ぐ', '予想する', '引っ張る', '射る'],
          correct: '引き継ぐ'
        }, {
          question: 'tiger',
          answers: [ '犬', '猫', 'トラ', 'ライオン'],
          correct: 'トラ}'
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
      const buttonLen = $button.length; // buttonLenにはintegerが入る
      
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
      };
      
      const goToNext = () => {
        quizCount++;
        if(quizCount < quizLen){
          setupQuiz(quizCount);
        } else {
          // $window.alert('クイズ終了！');
          showEnd();
        }
      };
      
      const clickHandler = (elm) => {/////38.47(e)
        if(elm.textContent === quiz[quizCount].correct){//////27.40
          document.getElementById('js-btn-1').textContent = '正解！';
          score++;
        } else {
          $window.alert('不正解!');
        }
        // ここに後で Ajax を活用して結果を記録していくコードを書く
        goToNext();
      };
      
      const showEnd = () => {
        $question.textContent = '終了！成　績' + score + '/' + quizLen + 'です';
        
        const $items = $doc.getElementById('js-items');
        $items.style.visibility = 'hidden';
      };
      
      setupQuiz();
      
      let handlerIndex = 0;
      //let answersLen = quiz[quizCount].answers.length;
      
      while(handlerIndex < buttonLen){
        $button[handlerIndex].addEventListener('click', (e) => {
          clickHandler(e.target);
        });
        handlerIndex++;
      }
    </script>
  </div>
  
  <script src="app.js"></script>
  <script src="index.js"></script>
@endsection