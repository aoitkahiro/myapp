<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
 // CSVを取り込むための宣言
use Goodby\CSV\Import\Standard\LexerConfig;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Illuminate\Support\Facades\Auth;//設定は app.php に記述有。∴use Auth でも宣言できる。
use App\Course;
use App\User;
use App\History;
use App\UserQuizResult;
use Log;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
  public function profile(Request $request)
  {
    $a_user = Auth::user();
    //dd($a_user);
    return view('admin.course.profile',['a_user'=>$a_user]); 
  } 

  public function profileUpdate(Request $request)
  {     
        $user = Auth::user();
        $a_user = Auth::user();
        $profile_data = $request->all();
        if ($request->file('image')) {
            $ext = $request->file('image')->extension();
            $path = $request->file('image')->storeAs('public/tango', time() . Auth::user()->id . "." . $ext);// store()は、画像の場所を返す。画像の場所を$pathへ代入する
            $profile_data['image_path'] = basename($path);//public/image/xxxxxx.jpg の場所情報を取り除くのがbasename。image_pathというキーをここで作成
        } else {
            $profile_data['image_path'] = $user->image_path;
        }
        $user->name = $profile_data["name"];
        $user->mygoal = $profile_data["mygoal"];
        $user->image_path = $profile_data['image_path'];
        $user->save();
        
        return redirect('admin/course/index');  
  } 
  
  public function index(Request $request)   
  {
    $courses = Course::all();
    $arr = [];
    foreach($courses as $course){
      array_push($arr,$course->category);
    }
    $unique_categories = array_unique($arr);
    /* TODO indexにランキングを乗せると重くなるので適切な技術を習得するまでコメントアウト
    $five = [];
    $ten = [];
    $fifteen = [];
    foreach ($unique_categories as $key => $value) {
      // dd($value);
      array_push($five, UserQuizResult::getRankingInCategoryAndQuestionQuantity($value, 5));
      array_push($ten, UserQuizResult::getRankingInCategoryAndQuestionQuantity($value, 10));
      array_push($fifteen, UserQuizResult::getRankingInCategoryAndQuestionQuantity($value, 15));
    }
    // dd($five,$ten,$fifteen);
    // dd($five[2][1],$five[0],$five,$unique_categories[$key]);
    // dd($unique_categories);
      */
    $user = Auth::user();
    $memory_per =[];
    foreach($unique_categories as $unique_category){
      $bunbo_courses = Course::where('category',$unique_category)->get();
      // dd($bunbo_courses,count($bunbo_courses));
      $bunbo_num = count($bunbo_courses);
      $bunbo_ids = [];
      foreach($bunbo_courses as $bunbo_course){
        array_push($bunbo_ids,$bunbo_course->id);
      };
      // 'lerning_level 1 or 2'で絞り込む機能
      $id_count = 0;
      $vol1 = 1;
      $vol2 = 2;
      foreach($bunbo_ids as $bunbo_id){
        $bunshi = History::where(function($bunshi) use($vol1,$vol2)
        {
           $bunshi->where('learning_level', $vol1)
                  ->orWhere('learning_level', $vol2);
        })->where('user_id', $user->id)->where('course_id', $bunbo_id)->first();
        if($bunshi != []){
          $id_count++;
        }
      }
      $bunshi_num = $id_count;
      $memory_per[] = round($bunshi_num/$bunbo_num*100,0);
    }
      
      return view('admin.course.index',['memory_per'=>$memory_per,/*'five'=>$five,'ten'=>$ten,'fifteen'=>$fifteen,*/
      'unique_categories'=>$unique_categories, 'courses'=>$courses, 'last_category'=>$request->last_category, 'has_done'=>$request->has_done]);
  }

  public function select()
  {     
      return view('admin.course.select');  
  }

  public function wordbook(Request $request)
  {
    $users = User::where('id', Auth::id())->get();
    $user = Auth::user(); 
    $count = 0; // 最終的にページ数になる変数
    $tango_id = $request->tango_id;
    $looking_level = $user->looking_level;
    $some_history = History::where('user_id', $user->id)->get();
    $history = History::where('user_id',$user->id)->where('course_id', $tango_id + 1)->first();
    
    $course_id_in_histories_1 = [];
    $course_id_in_histories_2 = [];
    foreach($some_history as $a_history){
      if($a_history->learning_level == 2){
        $course_id_in_histories_2[]= $a_history->course_id;
      }elseif($a_history->learning_level == 1){
        $course_id_in_histories_1[]= $a_history->course_id;
      }
    }
    $unique_category = $request->category;
    
    if($some_history != NULL){
      if($looking_level == 2){
      $courses = Course::where('category',$unique_category)->whereNotIn('id', $course_id_in_histories_2)->WhereNotIn('id', $course_id_in_histories_1)->get();
      }elseif($looking_level == 1){
        $courses = Course::where('category',$unique_category)->whereNotIn('id', $course_id_in_histories_2)->get();
      }elseif($looking_level == 0){
        $courses = Course::where('category',$unique_category)->get();
      }
    }
    if($courses->count() == 0){
        $massage ="この科目にはデータがありません";
    }else{
        $massage = "";
    }
    
    $can_reward = count($courses);
    $noimage="hoge";
    if($can_reward <= $tango_id){
      $has_done = true;
      return view('admin.course.reward',['has_done' => $has_done, 'unique_category'=>$unique_category]);
    }else{
    $bunbo_courses = Course::where('category',$unique_category)->get();
    $bunbo_num = count($bunbo_courses);
    $bunbo_ids = [];
    foreach($bunbo_courses as $bunbo_course){
      array_push($bunbo_ids,$bunbo_course->id);
    };
    $id_count = 0;
    $vol1 = 1;
    $vol2 = 2;
    foreach($bunbo_ids as $bunbo_id){
      $bunshi = History::where(function($bunshi) use($vol1,$vol2)
      {
         $bunshi->where('learning_level', $vol1)
                ->orWhere('learning_level', $vol2);
      })->where('user_id', $user->id)->where('course_id', $bunbo_id)->first();
      
      if($bunshi != []){
        $id_count++;
      }
    }
    $bunshi_num = $id_count;
    
    $path = 'public/tango/';
    $jpgboolean = Storage::exists($path . $courses[$tango_id]->id. '.jpg');
    $pngboolean = Storage::exists($path . $courses[$tango_id]->id. '.png');
    if($jpgboolean == true){
      $hintImage = "ヒント画像あります";
    }elseif($pngboolean == true){
      $hintImage = "ヒント画像あります";
    }else{
      $hintImage = "画像未登録";
    }
    
    $memorize = Course::where('id',$courses[$tango_id]->id)->first()->memo;
    if($memorize != NULL){
      $memo_exists = "メモあります";
    }else{
      $memo_exists = "メモ未登録";
    }
    
    //↓の$valueはView側で[最初から知ってる][覚えた]ボタンを裏表切り替えるために、準備するための変数
    $value = History::where('user_id',$user->id)->where('course_id', $courses[$tango_id]->id)->first();
    $google_url = 'https://www.google.com/search?q=' . $courses[$tango_id]->front . '+意味';
    $google_url_back = 'https://www.google.com/search?q=' . $courses[$tango_id]->back;
    $google_url_oboekata = 'https://www.google.com/search?q=' . $courses[$tango_id]->front . '+覚え方+画像';
    $EtoJ_weblio_url = 'https://ejje.weblio.jp/content/' . $courses[$tango_id]->front;
    $JtoJ_weblio_url = 'https://www.weblio.jp/content/' . $courses[$tango_id]->back;
    $JtoN_weblio_url = 'https://njjn.weblio.jp/content/' . $courses[$tango_id]->back;
    
    return view('admin.course.wordbook', ['JtoN_weblio_url' => $JtoN_weblio_url, 'EtoJ_weblio_url' => $EtoJ_weblio_url, 'JtoJ_weblio_url' => $JtoJ_weblio_url, 'google_url_oboekata' => $google_url_oboekata, 'google_url_back' => $google_url_back, 'google_url' => $google_url, 
    'memo_exists'=>$memo_exists,'hintImage'=>$hintImage,'noimage'=>$noimage, 'bunshi_num'=>$bunshi_num,'bunbo_num'=>$bunbo_num,'unique_category'=>$unique_category, 'value'=>$value, 'history'=>$history, 'tango_id'=> $tango_id, 
    'post' => $courses,  'user' => $user, 'users' =>$users, 'message' => $massage]);
    }
  }
  public function reward(Request $request)
  {
    $courses = Course::all();
    $i = 0;
    $arr = [];
    foreach($courses as $course){
      array_push($arr,$course->category);
      $i++;
    }
    $unique_categories = array_unique($arr);
    $unique_category = $request->unique_category;
    $has_done = true;
      
    return view('admin.course.reward',['has_done' => $has_done, 'unique_categories'=>$unique_categories, 'unique_category'=>$unique_category, 'courses'=>$courses]);
  }
  
  public function write(Request $request) 
  {
    $a_course = Course::where('id',$request->tango_id)->first();
    return view('admin.course.write',['tango_id_for_write'=>$request->tango_id, 'a_course'=>$a_course, 'ext'=> session('extention'),
    'unique_category'=>$request->category,'page'=>$request->page]); // $request->tango_id の中身は整数値。URLの?tango_id=1 ならば、1）
  }
  
  public function update(Request $request) 
  {
    $tango_data = $request->all();
    if ($request->file('image')) {
      $ext = $request->file('image')->extension();
      $path = $request->file('image')->storeAs('public/tango', $request->course_id . "." . $ext);
    }
    $a_course = Course::where('id',$request->course_id)->first();
    if($request->front != NULL){
      $a_course->update(['front'=> $request->front]);
    }
    if($request->back != NULL){
      $a_course->update(['back'=> $request->back]);
    }
    if($request->memo != NULL){
      $a_course->update(['memo'=> $request->memo]);
    }
    $page = $request->page;
    return redirect('admin/course/wordbook?tango_id=' . $page .'&category=' . $request->category);
  }
  
 
  // 7.10 csv作成画面を作るために追加(その２)㈱ビヨンドのWebサイト参考
  public function csv2()   
  {     
    return view('admin.course.csv2');  
  }
  
  public function inportCsv(Request $request)
  {
    // CSV ファイル保存
    $tmpName = mt_rand().".".$request->file('csv')->guessExtension(); //TMPファイル名
    $request->file('csv')->move(public_path()."/csv/tmp",$tmpName);
    $tmpPath = public_path()."/csv/tmp/".$tmpName;
 
    //Goodby CSVのconfig設定
    $config = new LexerConfig();
    $interpreter = new Interpreter();
    $lexer = new Lexer($config);
 
    //CharsetをUTF-8に変換、CSVのヘッダー行を無視
    $config->setToCharset("UTF-8");
    $config->setFromCharset("sjis-win");
    $config->setIgnoreHeaderLine(true);
 
    $dataList = [];
    //deleteのメソッドをここか$datalistの上の行に噛ませる。その際、同一のcategoryカラムを条件にdeleteするようにする。
    //$db_data = new Course;
    //$db_data->where('category', 'EnglishWords')->delete();

    // 新規Observerとして、$dataList配列に値を代入
    $interpreter->addObserver(function (array $row) use (&$dataList){
      // 各列のデータを取得
      $dataList[] = $row;
    });
 
    // CSVデータをパース（エラーがないか解析）
    $lexer->parse($tmpPath, $interpreter);
 
    // TMPファイル削除
    unlink($tmpPath);
 
    // 登録処理
    foreach($dataList as $row){
        $hoge = new Course();
        $hoge->category = $row[0];
        $hoge->difficulty = $row[1];
        $hoge->front = $row[2];
        $hoge->back = $row[3];
        $hoge->kind = $row[4];
        $hoge->memo = $row[5];
        $hoge->save();
    }
  return redirect('admin/course/csv2')->with('done', count($dataList) . '件のデータを登録しました！');
  }
  public function practice()   
  {     
    return view('admin.course.practice');  
  }
  public function quiz(Request $request)
  {
    $category = urldecode($request->category);
    // $course = Course::find(1);
    $question_quantity = $request->question_quantity;//３は、のちのち20などにする予定
    $courses = Course::inRandomOrder()->where('category',$category)->limit($question_quantity)->get();
    $dummy_courses = Course::where('id' ,'<>', $courses[0]->id)->
      where('kind',$courses[0]->kind)->inRandomOrder()->limit($question_quantity)->get();
    $dummy_answers = array();
    for ($i = 0; $i < $question_quantity; $i++) {
      array_push($dummy_answers,Course::where('id' ,'<>', $courses[$i]->id)
        ->where('kind',$courses[$i]->kind)->inRandomOrder()->limit(3)->get());
    }
    $correct_and_dummy_answers = $dummy_answers;
    $correct_and_dummy_answers[] = $courses;
    shuffle($correct_and_dummy_answers);
    $latest_user_quiz_result = UserQuizResult::where('user_id', Auth::id())->orderBy("challenge_id","desc")->first();
    $challenge_id = 1;
    if(isset($latest_user_quiz_result)){
      $challenge_id = $latest_user_quiz_result->challenge_id + 1;
    }
    foreach($courses as $course){
      $result = new UserQuizResult();
      $result->user_id = Auth::id();
      $result->course_id = $course->id;
      $result->challenge_id = $challenge_id;
      $result->judgement = 0;
      $result->save();
    }
      $your_highscore_rank = UserQuizResult::getRankingInCategoryAndQuestionQuantity($category, $question_quantity);
      $ranking_title = $your_highscore_rank[1];
    // dd($request->forgotten);
    // $user = Auth::user();
    
    return view('admin.course.quiz', ['user'=>$user, 'ranking_title'=> $ranking_title, 'latest_user_quiz_result'=>$latest_user_quiz_result,'result'=> $result, 'challenge_id'=>$challenge_id, 'category'=>$request->category, 'question_quantity'=>$question_quantity,
    'correct_and_dummy_answers'=>$correct_and_dummy_answers,'dummy_answers'=>$dummy_answers, 'dummy_courses'=>$dummy_courses, 'courses'=>$courses,'forgotten'=>$request->forgotten]); 
  }
  
  public function PostQuizTime(Request $request)
  {
    $category = urldecode($request->category);
    $question_quantity = $request->question_quantity;
    $user_quiz_results = UserQuizResult::where('user_id', Auth::id())->where('challenge_id', $request->challenge_id)->
        orderBy("id")->get();
    $course_id_array = json_decode($request->course_id_array,true);//true は連想配列に、falseはオブジェクトにデコードする
    $result_items = json_decode($request->result_items,true);
    $results = array_column($result_items,'rslt');
    $running_times = array_column($result_items,'rng_time');
    $user_quiz_result = [];
    $i = 0;
    $pre_running_time = 0;
    foreach($user_quiz_results as $user_quiz_result){
      $user_quiz_result->update(['running_time' => $running_times[$i]]);
      $user_quiz_result->update(['judgement' => $results[$i]]);
      $user_quiz_result->save();
      $pre_running_time = $running_times[$i];
      $i++;
    }
    if($request->forgotten == "on"){
      $i = 0;
      foreach($course_id_array as $course_id){
        if($results[$i] == 1){
        //   dd($course_id);    
          $history = History::where('user_id',Auth::id())->where('course_id',$course_id)->first();
          if($history != NULL){
            $history->update(['learning_level'=> 0]);
          }
        }
      }
    }
    
    if(isset($request->forgotten)){
      $forgotten = $request->forgotten;
    }else{
      $forgotten = 0;
    }
    $correct = 0;
    foreach($results as $result){
      if($result == 2){
        $correct++;
      }
    }
    return redirect()->action('Admin\CourseController@showResult',
    ['forgotten' => $forgotten, 'question_quantity'=>$question_quantity, 'category'=>$category]); 
  }
  
  public function showResult(Request $request)
  {
    // dd($request->all());
    $currenct_challenge_id = UserQuizResult::where('user_id', Auth::id())->max('challenge_id');
    $currenct_results = UserQuizResult::where('user_id', Auth::id())->where('challenge_id', $currenct_challenge_id)->orderBy('id','ASC')->get();
    $question_quantity = count($currenct_results);
    $running_time = $currenct_results[$question_quantity-1]->running_time;
    $correct = 0;
    $incorrect = 0;
    $incorrect_list = [];
    foreach($currenct_results as $result){
      if($result->judgement == 2){
        $correct++;
      }else{
        $incorrect++;
        array_push($incorrect_list,$result->course_id);
      }
    }
    $incorrect_fronts = [];
    $incorrect_backs = [];
    foreach($incorrect_list as $word){
      $course = Course::where('id',$word)->first();
      array_push($incorrect_fronts,$course->front);
      array_push($incorrect_backs,$course->back);
    }
    $correctRatio = $correct / $question_quantity;
    $running_time = UserQuizResult::timeFunc($running_time);
    $category = $request->category;
    if($correctRatio == 1){
        $message = "す、す…すごい！満点！";
        $img = secure_asset('image/' . 'excellent.png');
    }elseif($correctRatio >= 0.9){
        $message = "すごい、もう少しで満点です";
        $img = secure_asset('image/' . '90_dog.png');
    }elseif($correctRatio >= 0.8){
        $message = "8割越えですか…なかなかやりますね";
        $img = secure_asset('image/' . 'mugi80.jpg');
    }elseif($correctRatio >= 0.5){
        $message = "平均以上です。その調子！";
        $img = secure_asset('image/' . 'hand_good.png');
    }elseif($correctRatio < 0.5){
        $message = "たまには休憩してね";
        $img = secure_asset('image/' . 'mugi.jpg');
    }
    $hoge = UserQuizResult::getRankingInCategoryAndQuestionQuantity($category, $question_quantity);
    $ranking_title = $hoge[1];
    $forgotten = $request->forgotten;
    return view('admin.course.showResult', 
    ['list_length'=>count($incorrect_fronts),'incorrect_fronts'=>$incorrect_fronts, 'incorrect_backs'=>$incorrect_backs, 'img'=>$img, 'forgotten' => $forgotten, 'message' => $message, 'running_time'=>$running_time, 'correct' => $correct,
    'question_quantity'=>$question_quantity, 'category'=>$category, 'ranking_title'=>$ranking_title]); 
  }
  
  public function ranking(Request $request)
  {
    $courses = Course::all();
    $users = User::all();
    $rankings = [];
    $category = $request->category;
    // dd($category);
    $question_quantity = $request->question_quantity;
    foreach ($users as $user) {
      $ary = []; // ここに正解回数が入る([0]が一回目の結果)
      $maxCi = UserQuizResult::where('user_id', $user->id)->max('challenge_id'); // そのユーザのチャレンジID最大値を取得
        // dd($maxCi);
      for ($ci = 1; $ci <= $maxCi; $ci++) {
        $num_of_challenge = UserQuizResult::where('user_id', $user->id)->where('challenge_id', $ci)->get();
        $n = count($num_of_challenge);
        if($n == $question_quantity){
          $user_quiz_results = UserQuizResult::where('user_id', $user->id)->where('challenge_id', $ci)->where('judgement', 2)->get();
          if(count($user_quiz_results) > 0 && Course::find($user_quiz_results->first()->course_id)->category == $category){
              $modelForTimeAndDate = UserQuizResult::where('user_id', $user->id)->where('challenge_id', $ci)->orderBy('running_time','DESC')->first();
              $date = $modelForTimeAndDate->created_at->format('Y/m/d');
              $running_time = $modelForTimeAndDate->running_time;
              $mygoal = $user->mygoal;
              // $date, $success, $running_time　の帳尻が合わないと、ランキングのソートが正常に動かなない
              // success = あるチャレンジの正答数
              // running_time = あるチャレンジの３問解くのにかかった時間
              // $date = あるチャレンジの終了日
              $success = count($user_quiz_results);
              $ary = ['name' => $user->name, '挑戦回数' => $ci, '正解回数' => $success, '挑戦日'=> $date, 
              'タイム'=>$running_time, '目標'=>$mygoal, '画像'=>$user->image_path, 'uqz'=> $user_quiz_results];
              $rankings[] = $ary;
          }
        }
      }
    }
    // ランキングを日付、正解回数、タイムの降順に並べ替える
    $days = array_column($rankings, '挑戦日');
    $numbers = array_column($rankings, '正解回数');
    $times = array_column($rankings, 'タイム');
    $result = array_multisort($days, SORT_DESC,$numbers, SORT_DESC, $times, SORT_ASC, $rankings); // 上位以外をはじくために、配列を整える
    $existed_user_names = [];
    $pre_date = "";
    $count = 0;
    
    foreach($rankings as $ranking){
      $checking_date = $ranking["挑戦日"];
      $checking_name = $ranking["name"];
      
      if($checking_date != $pre_date){ // 同日のデータをはじくために、"" あるいは 前のループの日付と比較
        $existed_user_names = [];
      }
      
      if(in_array($checking_name, $existed_user_names)){ // 既存の名前をはじくために、配列に存在するかcheck
        unset($rankings[$count]);  //$rankngを$rankingsから削除する
      }else{
        array_push($existed_user_names, $checking_name);//unset()に該当しなかった名前は「既存の名前」に追加して、次のcheckで使用
      }
      $pre_date = $checking_date; //チェックした日付を次のループで使用する
      $count++;
    }
    $days = array_column($rankings, '挑戦日');
    $numbers = array_column($rankings, '正解回数');
    $times = array_column($rankings, 'タイム');
    $result = array_multisort($numbers, SORT_DESC, $times, SORT_ASC, $days, SORT_DESC,$rankings);
    
    $your_highscore_rank = UserQuizResult::getRankingInCategoryAndQuestionQuantity($category, $question_quantity);
    $ranking_title = $your_highscore_rank[1];
    return view('admin.course.ranking', ['ranking_title'=>$ranking_title, 'rankings'=> $rankings, 'courses'=>$courses, 'category'=>$category, 'question_quantity'=>$question_quantity]); 
  }
  
}