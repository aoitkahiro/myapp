<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
 // CSVを取り込むための宣言 2021.7
use Goodby\CSV\Import\Standard\LexerConfig;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Illuminate\Support\Facades\Auth;//Laravel ではデフォルトでクラスをオートロードしている。設定は app.php に記述有。∴use Auth でも宣言できる。
use App\Course;
use App\User;
use App\History;
use App\UserQuizResult;
use Log;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
  // 7.24 スタート画面を作るために追加
  public function start()  
  {
      
      $users = User::where('id', Auth::id())->get(); //戻り値を配列にして、Viewに渡す場合のコード。これに対して、インスタンスでViewに渡すのが↓の行
      $user = User::where('id', Auth::id())->first(); //->first() は、1件だけ取り出すメソッド。もし複数見つかったら、1件目 対して->get()は一致するすべてのデータを取り出す。
                                                     //->first() はUserクラスのインスタンスを取得 ->get()はUserクラスのコレクション（配列の型）で取得する
      return view('admin.course.start', ['user' => $user, 'users' =>$users]); // userはインスタンスを渡している。usersは配列としてインスタンスを渡している
  }   
  // 7.28 プロフィール編集画面を作るために追加
  public function upImage(Request $request)
  {
    return view('admin.course.upImage'); 
  } 
  public function profile(Request $request)
  {
    $a_user = Auth::user();
    //dd($a_user);
    return view('admin.course.profile',['a_user'=>$a_user]); 
  } 
  /*1．storeメソッドの引数（？）で、任意の名前で画像を保存する方法を調べて実装する（まずはhallo.jpgなど）
                $path = $request->file('image')->storeAs('public/tango', hallo);
  　　store/image にtango フォルダを作った方が「何の1か？」が管理しやすい
  　2. id.jpg で保存（文字列の連結）
                $path = $request->file('image')->storeAs('tango', $request->course()->id);
  　3. donotrepeatyourself dryの原則（繰り返しはよくない）どうしてidを名前で保存する必要があるのか_本質的に
  　4. DBからidを参照して、Viewファイルで表示するようコーディングする
  　tip 作った設計が今後保守できるか。あとから来た人が壊さず使えるかという観点も持ってみよう
  */
  public function profileUpdate(Request $request)  
  {     
        $user = Auth::user(); // ログインユーザーのインスタンスの獲得
        $a_user = Auth::user();
         // $id= Auth::id(); 【参考】ログインユーザーのidの獲得　【参考２】Auth::user() == User::find(Auth::id()); 同じことをしている
         // idはinputではなく、サーバーからuserに与えられる値。ゆえにnameとmygoalだけでOK
        $profile_data = $request->all();//ユーザーが入力した項目  名前、目標、画像選択のみが連想配列で渡されている
        if ($request->file('image')) { //=file()ファイル選択ダイアログで、画像(bladeで設定した"image"を選択したかtrue or false
            //$path = Storage::disk('s3')->putFile('/',$profile_data['image'],'public');
            //$path = $request->file('image')->store('public/image'); //任意の名前での保存練習中につきコメントアウト
            $ext = $request->file('image')->extension(); // 拡張子を取るコード
            $path = $request->file('image')->storeAs('public/tango', Auth::user()->id . "." . $ext);// store()は、画像の場所を返す。画像の場所を$pathへ代入する
            //$user->image_path = Storage::disk('s3')->url($path);
            $profile_data['image_path'] = basename($path);//public/image/xxxxxx.jpg の場所情報を取り除くのがbasename。image_pathというキーがここで作られる
        } else {
            $profile_data['image_path'] = $user->image_path;
        }
        //unset($profile_data['image']);//無駄なデータかつ、usersテーブルにimageカラムがない（→エラーになる）ので消す
        //unset($profile_data['_token']);
        //unsetは、以下のように1件ずつ代入する場合は不要。fillメソッドを使う場合は必要
        $user->name = $profile_data["name"];
        $user->mygoal = $profile_data["mygoal"];
        $user->looking_level = $profile_data["looking_level"];
        $user->image_path = $profile_data['image_path'];
        $user->save();
            // 該当するデータを上書きして保存する
            // $user->fill($profile_data)->save(); 
            // ユーザーの入力したデータを$userに渡して（fill）保存（save）
        return view('admin.course.profile', ['user' => $user, 'a_user' =>$user]);  
  } 
  
  //━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━　↑ プロフィール機能　━━━━　↓ 単語帳機能　━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    
  // 7.3 コース画面を作るために追加
  public function index()   
  {     
    $courses = Course::all();
    $i = 0;
    $arr = [];
    foreach($courses as $course){
      // dd($course);
      array_push($arr,$course->category);
      $i++;
    }
    $unique_categories = array_unique($arr);
    // dd($unique_categories);
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
      // dd($bunbo_ids);
      // 'lerning_level 1 or 2'で絞り込む書き方が7行ほどあってやや複雑
      $id_count = 0;
      $vol1 = 1;
      $vol2 = 2;
      foreach($bunbo_ids as $bunbo_id){
        $bunshi = History::where(function($bunshi) use($vol1,$vol2)
        {
           $bunshi->where('learning_level', $vol1)
                  ->orWhere('learning_level', $vol2);
        })->where('user_id', $user->id)->where('course_id', $bunbo_id)->first();
        // Log::info('####');
        
        // Log::info($bunshi);
        if($bunshi != []){
          $id_count++;
        }
      }
      // dd($id_count);
      $bunshi_num = $id_count;
      $memory_per[] = round($bunshi_num/$bunbo_num*100,0);
      // dd($bunshi_num,$bunbo_num,$bunshi_num/$bunbo_num,$memory_per);
    }
      // dd($memory_per);
      
      return view('admin.course.index',['memory_per'=>$memory_per,'five'=>$five,'ten'=>$ten,'fifteen'=>$fifteen,'unique_categories'=>$unique_categories, 'courses'=>$courses]);
  }
  // 7.10 単語帳orテストを作るために追加
  public function select()
  {     
      return view('admin.course.select');  
  }
  // 7.10 単語帳画面を作るために追加
  public function wordbook(Request $request)
  {
    // dd($request->page,$request->category,$request);
    $users = User::where('id', Auth::id())->get(); //戻り値を配列にして、Viewに渡す場合のコード。これに対して、インスタンスでViewに渡すのが↓の行
    //$user = User::where('id', Auth::id())->first(); //->first() は、1件だけ取り出すメソッド。もし複数見つかったら1件目取得。対して->get()は一致するすべてのデータを取り出す。
                                                    //->first() はUserクラスのインスタンスを取得 ->get()はUserクラスのコレクション（配列の型）で取得する
    $user = Auth::user(); //2行上のuserの取り方と同じ
    $count = 0; // 最終的にページ数になる変数
    //$course = Course::find($request->tango_id); // URLの"?tango_id=整数値"で送った値を($request ->tango_id)で取得している。 例えばURLが、?tango_id=10であれば、find(10)になる
    // ちなみにfindは DB内のレコードを「id」で検索・取得するための、Laravelのメソッド。今回はCourseというDBを tango_idで検索・取得している
    $tango_id = $request->tango_id;
    $looking_level = $user->looking_level; // 特定のuserレコードの、looking_level"0~2"を$looking_level に取得
    $some_history = History::where('user_id', $user->id)->get();//historiesテーブル内を$user->id で検索して特定userのhistoriesレコードを取得している
    $history = History::where('user_id',$user->id)->where('course_id', $tango_id + 1)->first();
    
    $course_id_in_histories_1 = [];
    $course_id_in_histories_2 = [];
    foreach($some_history as $a_history){ // 当ユーザーのhistoryを一つずつ入れていって…
      if($a_history->learning_level == 2){ // もし一つのhistoryのlearning_levelが2なら
        $course_id_in_histories_2[]= $a_history->course_id; //この配列に、learning_levelが2（覚えた）のhistoryのcourse_idを入れる
      }elseif($a_history->learning_level == 1){ // もし一つのhistoryのlearning_levelが1なら
        $course_id_in_histories_1[]= $a_history->course_id; //この配列に、learning_levelが1（最初から知ってる）のhistoryのcourse_idを入れる
      }
    }
    // $unique_category = Course::find($tango_id + 1)->category;
    $unique_category = $request->category;
    // dd($request,$unique_category);
    if($some_history != NULL){
      if($looking_level == 2){ // もし、looking_levelが 2 なら
      $courses = Course::where('category',$unique_category)->whereNotIn('id', $course_id_in_histories_2)->WhereNotIn('id', $course_id_in_histories_1)->get();//get();
      }elseif($looking_level == 1){ // もし、looking_levelが 1なら
        $courses = Course::where('category',$unique_category)->whereNotIn('id', $course_id_in_histories_2)->get(); //learning_levelが1のcourse_id以外を表示させる
      }elseif($looking_level == 0){ // もし、looking_levelが初期値の 0 なら
        $courses = Course::where('category',$unique_category)->get();
      // dd($courses,$unique_category);
      }
    // dd($courses);
    }
    if($courses->count() == 0){ //もし、courseテーブルの全てのデータを取得してデータ件数が0だったら（大前提として0ではない。開発中のエラーを回避するためのif文）
        $massage ="この科目にはデータがありません";
    }else{
        $massage = "";
        // 以下、ページングのコード
        /*foreach($courses as $tmp){ // ＄tmpに1件ずつ$courses（配列）から取り出して入れていきます。foreach文は配列の数だけ回すfor文です。
          $count += 1; // ループが回るたびに$countが1増えていく
            if($tmp->id == $request->tango_id){ // id と tango_id (例えば id:372など)を比較して同じであれば表示する
              $course = $tmp; // $courseは、Viewファイルでpostへ渡す変数。これに結果を渡す
              break; // ここを通ると強制的にループが終わる。あまりいい実装ではない（特殊なケースが増える）
            }                     
        }*///以上、ページングのコード
    }
    Log::info('####');
    Log::info($courses);//画面遷移のときは空にならないが、「最初から知ってる」を押したときは空になる。なぜ？
    
    $can_reward = count($courses);
    $noimage="hoge";
    if($can_reward <= $tango_id){
    return view('admin.course.reward');
    }else{
    //正解率を出すメソッドを作成
    //categoryで絞り込んだcourse_idの数が分母。分子はcategoryで絞り込んだcourse_idのうち、count(History::where('id',$the_ids)->get)
    $bunbo_courses = Course::where('category',$unique_category)->get();
    // dd($bunbo_courses,count($bunbo_courses));
    $bunbo_num = count($bunbo_courses);
    $bunbo_ids = [];
    foreach($bunbo_courses as $bunbo_course){
      array_push($bunbo_ids,$bunbo_course->id);
    };
    // dd($bunbo_ids);
    // 'lerning_level 1 or 2'で絞り込む書き方が7行ほどあってやや複雑
    $id_count = 0;
    $vol1 = 1;
    $vol2 = 2;
    foreach($bunbo_ids as $bunbo_id){
      $bunshi = History::where(function($bunshi) use($vol1,$vol2)
      {
         $bunshi->where('learning_level', $vol1)
                ->orWhere('learning_level', $vol2);
      })->where('user_id', $user->id)->where('course_id', $bunbo_id)->first();
      // Log::info('####');
      
      // Log::info($bunshi);
      if($bunshi != []){
        $id_count++;
      }
    }
    // dd($id_count);
    $bunshi_num = $id_count;
    
    $path = 'public/tango/';
    $jpgboolean = Storage::exists($path . $courses[$tango_id]->id. '.jpg');
    $pngboolean = Storage::exists($path . $courses[$tango_id]->id. '.png');
    // dd($jpgboolean,$path . $courses[$tango_id]->id . '.png');
    if($jpgboolean == true){
      $a = "ヒント画像あります";
    }elseif($pngboolean == true){
      $a = "ヒント画像あります";
    }else{
      $a = "画像未登録";
    }
    //↓の$valueはView側で[最初から知ってる][覚えた]ボタンを裏表切り替えるために、準備するための変数
    // dd($courses,$tango_id,$user);B
    $value = History::where('user_id',$user->id)->where('course_id', $courses[$tango_id]->id)->first();
    // dd($value,$courses[$tango_id],$tango_id, $unique_category);
    return view('admin.course.wordbook', ['hintImage'=>$a,'noimage'=>$noimage, 'bunshi_num'=>$bunshi_num,'bunbo_num'=>$bunbo_num,'unique_category'=>$unique_category, 'value'=>$value, 'history'=>$history, 'tango_id'=> $tango_id, 
    'post' => $courses,  'user' => $user, 'users' =>$users, 'message' => $massage]);
    //return view('admin.course.wordbook', ['post' => $course, "all_courses_count" => $courses->count(),'page_num' => $count, 'user' => $user, 'users' =>$users , 'hoge' =>'hello']);
    }
  }
  public function reward(Request $request)
  {
      $courses = Course::all();
      $i = 0;
      $arr = [];
      foreach($courses as $course){
        // dd($course);
        array_push($arr,$course->category);
        $i++;
      }
      $unique_categories = array_unique($arr);
      // dd($unique_categories);
      
    $massage = "お疲れ様でした";
      
    return view('admin.course.reward',['unique_categories'=>$unique_categories, 'courses'=>$courses]);
  }
  
  //$course にはid,front,back,kind,category,degree の値等が入っている。 
  // 7.10 書き込み画面を作るために追加
  public function write(Request $request)  // wordbookからGETできたらこちら
  {
    $a_course = Course::where('id',$request->tango_id)->first();
  //dd(session('extention')); // ->with で渡された場合は settion('xxxxx')で受ける
  // dd($request);
    return view('admin.course.write',['tango_id_for_write'=>$request->tango_id, 'a_course'=>$a_course, 'ext'=> session('extention'),
    'unique_category'=>$request->category,'page'=>$request->page]); // $request->tango_id の中身は整数値。URLの?tango_id=1 ならば、1）
  }                                 //　　↑次のViewで使う値に↑getパラメータのtango_idを取得している
  
  public function update(Request $request)  // writeからPOSTでRoutingされたらこちら
  {
    // dd($request->category,$request->all());
    $tango_data = $request->all();//ユーザーが入力した項目が連想配列で渡されている
    // dd($tango_data);
    if ($request->file('image')) { //=file()ファイル選択ダイアログで、画像(bladeでnameに設定した"image"）を選択したか true or false で返す
      $ext = $request->file('image')->extension();
      $path = $request->file('image')->storeAs('public/tango', $request->course_id . "." . $ext);
    } //falseの場合何もしない
    $a_course = Course::where('id',$request->course_id)->first();
    // dd($a_course);
    if($request->front != NULL){
      $a_course->update(['front'=> $request->front]);
    }
    if($request->back != NULL){
      $a_course->update(['back'=> $request->back]);
    }
    if($request->memo != NULL){
      $a_course->update(['memo'=> $request->memo]);
    }
    $page = $request->page; // この文がないと、値を渡せない？
    return redirect('admin/course/wordbook?tango_id=' . $page .'&category=' . $request->category);//->with(['extention'=>$ext]); // "with"実装してみたかったが、エラーになりそうだったので一旦コメントアウト2021.8.7
  }
  
  //━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━　↑ 単語帳機能　━━　↓ 単語帳新規作成機能　━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  
  // 7.10 作成開始画面を作るために追加
  public function create()
  {     
    return view('admin.course.create');  
  }
 
  // 7.10 csv作成画面を作るために追加(その２)㈱ビヨンドのWebサイト参考
  public function csv2()   
  {     
    return view('admin.course.csv2');  
  }
  
  // 7.13 csvインポートメソッドを作るために追加(その２)
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
        //$hoge->fill(['front' => $row[3]]);//Course モデルのインスタンスに、
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
    // dd($request,urldecode($request->category));
    $courses = Course::inRandomOrder()->where('category',$category)->limit($question_quantity)->get();
    // dd($courses);
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
    // dd($latest_user_quiz_result);
    return view('admin.course.quiz', ['ranking_title'=> $ranking_title, 'latest_user_quiz_result'=>$latest_user_quiz_result,'result'=> $result, 'challenge_id'=>$challenge_id, 'category'=>$request->category, 'question_quantity'=>$question_quantity,
    'correct_and_dummy_answers'=>$correct_and_dummy_answers,'dummy_answers'=>$dummy_answers, 'dummy_courses'=>$dummy_courses, 'courses'=>$courses,'forgotten'=>$request->forgotten]); 
  }
  
  public function PostQuizTime(Request $request)
  {
    // dd($request);
    $category = urldecode($request->category);
    $question_quantity = $request->question_quantity;
    // dd($request->all(),$category);
    $user_quiz_results = UserQuizResult::where('user_id', Auth::id())->where('challenge_id', $request->challenge_id)->
        orderBy("id")->get();
    // $quiz_data = $request->all(); //$quiz_data にはrunning_time や score などHTMLからsubmitされた値が入っている。
    // $running_time_in_string = $quiz_data['running_time'];
    // $running_time_array = explode("/" , $running_time_in_string);
    // $result_in_string = $quiz_data['result'];
    // $result_array = explode(" " , $result_in_string);
    $course_id_array = json_decode($request->course_id_array,true);//true は連想配列に、falseはオブジェクトにデコードする
    $result_items = json_decode($request->result_items,true);
    // dd($request);
    $results = array_column($result_items,'rslt');
    $running_times = array_column($result_items,'rng_time');
    // dd(Auth::id(),$request->challenge_id,$user_quiz_results,$results,$running_times);
    // dd($course_id_array,$running_time_array,$request,$quiz_data);
    $user_quiz_result = [];
    $i = 0;
    $pre_running_time = 0;//デバッグ用の変数（ランニングタイムでバグが起こっているため）
    foreach($user_quiz_results as $user_quiz_result){
      $user_quiz_result->update(['running_time' => $running_times[$i]]);
      $user_quiz_result->update(['judgement' => $results[$i]]);
      if($running_times[$i] < $pre_running_time){
          dd("running_time の保存がバグっているようです",$running_times,$user_quiz_results,$results);
      }
      $user_quiz_result->save();
      $pre_running_time = $running_times[$i];
      $i++;
    }
    //もし"覚えたを解除するswitch"がonなら
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
    // dd($forgotten,$request->all());
    return redirect()->action('Admin\CourseController@quiz',['forgotten' => $forgotten, 'category'=>$category, 'question_quantity'=>$question_quantity]);
  }
  
  public function showResult(Request $request)
  {
    // dd($request->category);
    $category = $request->category;
    $question_quantity = $request->question_quantity;
    $hoge = UserQuizResult::getRankingInCategoryAndQuestionQuantity($category, $question_quantity);
    $ranking_title = $hoge[1];
    // dd($ranking_title);
    return view('admin.course.showResult', ['ranking_title'=>$ranking_title,'category'=>$category, 'question_quantity'=>$question_quantity]); 
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
        // dd($n,$question_quantity);
        if($n == $question_quantity){
          $user_quiz_results = UserQuizResult::where('user_id', $user->id)->where('challenge_id', $ci)->where('judgement', 2)->get();
          // dd($user_quiz_results);
          if(count($user_quiz_results) > 0 && Course::find($user_quiz_results->first()->course_id)->category == $category){
              $modelForTimeAndDate = UserQuizResult::where('user_id', $user->id)->where('challenge_id', $ci)->orderBy('running_time','DESC')->first();
              // dd($user->id, $ci, $modelForDate);
              $date = $modelForTimeAndDate->created_at->format('Y/m/d');
              $running_time = $modelForTimeAndDate->running_time;
              $mygoal = $user->mygoal;
              // $date, $success, $running_time　の帳尻が合わないと、ランキングのソートが正常に動かなさそう
              // success = あるチャレンジの正答数
              // running_time = あるチャレンジの３問解くのにかかった時間
              // $date = あるチャレンジの終了日
              $success = count($user_quiz_results);
              $ary = ['name' => $user->name, '挑戦回数' => $ci, /*'c_id'=> $c_id,*/ '正解回数' => $success, '挑戦日'=> $date, 
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
    // dd($rankings);
    // $beforeSort = $rankings;
    $result = array_multisort($days, SORT_DESC,$numbers, SORT_DESC, $times, SORT_ASC, $rankings); // 上位以外をはじくために、配列を整える
    // dd($beforeSort,$rankings,$result);
    $existed_user_names = [];
    $pre_date = "";
    $count = 0;
    // dd($rankings);
    
    foreach($rankings as $ranking){
      // dd($rankings);
      $checking_date = $ranking["挑戦日"];
      $checking_name = $ranking["name"];
      
      if($checking_date != $pre_date){ // 同日のデータをはじくために、"" あるいは 前のループの日付と比較
        $existed_user_names = [];
      }//もし日付が前ループと同じなら、$ $existed_user_names[] は初期化しない
      
      if(in_array($checking_name, $existed_user_names)){ // 既存の名前をはじくために、配列に存在するかcheck
        unset($rankings[$count]);  //$rankngを$rankingsから削除する
      }else{
        array_push($existed_user_names, $checking_name);//unset()に該当しなかった名前は「既存の名前」に追加して、次のcheckで使用
      }
      $pre_date = $checking_date; //チェックした日付を次のループで使用する
      $count++;
    }
    // dd($rankings);//unset()済
    $days = array_column($rankings, '挑戦日');
    $numbers = array_column($rankings, '正解回数');
    $times = array_column($rankings, 'タイム');
    // dd($rankings);
    $result = array_multisort($numbers, SORT_DESC, $times, SORT_ASC, $days, SORT_DESC,$rankings); // 今度は正解回数、タイム、挑戦日の優先順に並べ替える
    
    $your_highscore_rank = UserQuizResult::getRankingInCategoryAndQuestionQuantity($category, $question_quantity);
    $ranking_title = $your_highscore_rank[1];
    // dd($your_highscore_rank);
    return view('admin.course.ranking', ['ranking_title'=>$ranking_title, 'rankings'=> $rankings, 'courses'=>$courses, 'category'=>$category, 'question_quantity'=>$question_quantity]); 
  }
  
}