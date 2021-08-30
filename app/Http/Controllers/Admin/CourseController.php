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
        $user->has_known = $profile_data["has_known"];
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
      return view('admin.course.index');  
  }
  // 7.10 単語帳orテストを作るために追加
  public function select()
  {     
      return view('admin.course.select');  
  }
  // 7.10 単語帳画面を作るために追加
  public function wordbook(Request $request)  
  {
    $users = User::where('id', Auth::id())->get(); //戻り値を配列にして、Viewに渡す場合のコード。これに対して、インスタンスでViewに渡すのが↓の行
    //$user = User::where('id', Auth::id())->first(); //->first() は、1件だけ取り出すメソッド。もし複数見つかったら1件目取得。対して->get()は一致するすべてのデータを取り出す。
                                                    //->first() はUserクラスのインスタンスを取得 ->get()はUserクラスのコレクション（配列の型）で取得する
    $user = Auth::user(); //2行上のuserの取り方と同じ
    $count = 0; // 最終的にページ数になる変数
    //$course = Course::find($request->tango_id); // URLの"?tango_id=整数値"で送った値を($request ->tango_id)で取得している。 例えばURLが、?tango_id=10であれば、find(10)になる 
        // ちなみにfindは DB内のレコードを「id」で検索・取得するための、Laravelのメソッド。今回はCourseというDBを tango_idで検索・取得している
    $tango_id = $request->tango_id;
    $known_switch = $user->has_known; // 特定のuserレコードの、has_knownが"0"か"1"かを$known_switch に取得
    $learnd_switch = $user->has_learnd; 
    $some_history = History::where('user_id', $user->id)->get();//historiesテーブル内を$user->id で検索して特定userのhistoriesレコードを取得している
    $history = History::where('user_id',$user->id)->where('course_id', $tango_id + 1)->first();
    //dd($history);
    $course_id_in_histories1 = [];
    $course_id_in_histories1 = [];
    foreach($some_history as $a_history)
    {
      if($a_history->hide_known == 1){
        $course_id_in_histories1[]= $a_history->course_id; //配列を入れる
      }elseif($a_history->hide_learned == 1){
        $course_id_in_histories2[]= $a_history->course_id;
      }
    }
    if($some_history != NULL){
      if($known_switch == 1){
          $courses = Course::whereNotIn('id', $course_id_in_histories1)->get();
      }elseif($known_switch == 0 and $learnd_switch == 1){
          $courses = Course::whereNotIn('id', $course_id_in_histories2)->get();
      }elseif($known_switch == 0 and $learnd_switch == 0){
          $courses = Course::all();
      }
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
    }//if文の終わり
    
    /*【usersテーブルの$has_known が 1 で且つ、Historyクラスのレコードである$historyの $hide_known が 1 の時、
    　　$historyの$hide_knownが1じゃないレコードまで回すコード】目的：使い道が　である値を取得
     1st, 取得したuserレコードのhas_known の値が 0or1 を取得して、$known_switch に代入する
     2nd, もし、$known_switch が0なら何もしない ←書かない。1の時だけ書く
     3rd, もし、$known_switch が1なら$history の ->hide_known が1かどうかを判定する
     4th, もし、3rd の判定結果が TRUE なら（ページを表示せず）$course に次の id のレコードを代入する。その後、3rd へ戻る）
     5th, もし、3rd の判定結果が FALSEなら $courseに代入されているレコードをViewに渡す
    */
    return view('admin.course.wordbook', ['history'=>$history, 'tango_id'=> $tango_id, 'post' => $courses,  'user' => $user, 'users' =>$users, 'message' => $massage]); 
    //return view('admin.course.wordbook', ['post' => $course, "all_courses_count" => $courses->count(),'page_num' => $count, 'user' => $user, 'users' =>$users , 'hoge' =>'hello']);
  }                                         //$course にはid,front,back,kind,category,degree の値等が入っている。 
  // 7.10 書き込み画面を作るために追加
  public function write(Request $request)  // writeからGETできたらこちら
  {
    $a_course = Course::where('id',$request->tango_id)->first();
  //dd(session('extention')); // ->with で渡された場合は settion('xxxxx')で受ける
    return view('admin.course.write',['tango_id_for_write'=>$request->tango_id, 'a_course'=>$a_course, 'ext'=> session('extention')]); // $request->tango_id の中身は整数値。URLの?tango_id=1 ならば、1）
  }                                 //　　↑次のViewで使う値に↑getパラメータのtango_idを取得している
  
  public function update(Request $request)  // writeからPOSTでRoutingされたらこちら
  {
    $tango_data = $request->all();//ユーザーが入力した項目が連想配列で渡されている
    if ($request->file('image')) { //=file()ファイル選択ダイアログで、画像(bladeでnameに設定した"image"）を選択したか true or false で返す
      $ext = $request->file('image')->extension();
      $path = $request->file('image')->storeAs('public/tango', $request->course_id . "." . $ext);
    } //falseの場合何もしない
    $a_course = Course::where('id',$request->course_id)->first();
    if($request->front != NULL){
      $a_course->update(['front'=> $request->front]);
    }
    if($request->back != NULL){
      $a_course->update(['back'=> $request->back]);
    }
    $correct_id = $request->course_id - 1; // この文がないと、値を渡せない？
    return redirect('admin/course/wordbook?tango_id=' . $correct_id );//->with(['extention'=>$ext]); // "with"実装してみたかったが、エラーになりそうだったので一旦コメントアウト2021.8.7
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
        $hoge->kind = $row[0];
        $hoge->category = $row[1];
        $hoge->degree = $row[2];
        $hoge->front = $row[3];
        $hoge->back = $row[4];
        //$hoge->fill(['front' => $row[3]]);//Course モデルのインスタンスに、
        $hoge->save();
    }
  return redirect('admin/course/csv2')->with('done', count($dataList) . '件のデータを登録しました！');
  }
  public function practice()   
  {     
    return view('admin.course.practice');  
  }
}



/*
  // 7.3 csv作成画面を作るために追加(その１)
  public function csv()   
  {     
    return view('admin.course.csv');  
  }
  // csv取り込みメソッドを作るために追加 (その２)
  public function upload_regist(Request $rq)
  {
    if($rq->hasFile('csv') && $rq->file('csv')->isValid()) {
        // CSV ファイル保存
        $tmpname = uniqid("CSVUP_").".".$rq->file('csv')->guessExtension(); //TMPファイル名
        $rq->file('csv')->move(public_path()."/csv/tmp",$tmpname);
        $tmppath = public_path()."/csv/tmp/".$tmpname;

        // Goodby CSVの設定
        $config_in = new LexerConfig();
        $config_in
            ->setFromCharset("SJIS-win")
            ->setToCharset("UTF-8") // CharasetをUTF-8に変換
            ->setIgnoreHeaderLine(true) //CSVのヘッダーを無視
        ;
        $lexer_in = new Lexer($config_in);

        $datalist = array();

        $interpreter = new Interpreter();
        $interpreter->addObserver(function (array $row) use (&$datalist){
           // 各列のデータを取得
           $datalist[] = $row;
        });

        // CSVデータをパース
        $lexer_in->parse($tmppath,$interpreter);

        // TMPファイル削除
        unlink($tmppath);

        // 処理
        foreach($datalist as $row){
            // 各データ取り出し
            $csv_user = $this->get_csv_user($row);

            // DBへの登録
            $this->regist_user_csv($csv_user);
        }
        return redirect('admin.course.csv')->with('flashmessage','CSVのデータを読み込みました。');
    }
    return redirect('admin.course.csv')->with('flashmessage','CSVの送信エラーが発生しましたので、送信を中止しました。');
  }*/