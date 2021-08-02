<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

 // CSVを取り込むための宣言 2021.7
use Goodby\CSV\Import\Standard\LexerConfig;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;

use Illuminate\Support\Facades\Auth;
use App\Course;
use App\User;

class CourseController extends Controller
{
       // 7.24 スタート画面を作るために追加
  public function start()  // 
  {   //dd($request);
      
      $users = User::where('name', "tester")->get(); //戻り値を配列にして、Viewに渡す場合のコード。これに対して、インスタンスでViewに渡すのが↓の行
      $user = User::where('name', "tester")->first(); //->first() は、1件だけ取り出すメソッド。もし複数見つかったら、1件目 対して->get()は一致するすべてのデータを取り出す。
                                                        //->first() はUserクラスのインスタンスを取得 ->get()はUserクラスのコレクション（配列の型）で取得する
      
      return view('admin.course.start', ['user' => $user, 'users' =>$users]); // userはインスタンスを渡している。usersは配列としてインスタンスを渡している
  }   
       // 7.28 プロフィール編集画面を作るために追加
  public function profile()  // 
  {     
      return view('admin.course.profile'); // 
      
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
  public function profileUpdate(Request $request)  //
  {     
        $user = Auth::user(); // ログインユーザーのインスタンスの獲得
         // $id= Auth::id(); 【参考】ログインユーザーのidの獲得　【参考２】Auth::user() == User::find(Auth::id()); 同じことをしている
         // idはinputではなく、サーバーからuserに与えられる値。ゆえにnameとmygoalだけでOK
        $profile_data = $request->all();//ユーザーが入力した項目  名前、目標、画像選択のみが連想配列で渡されている
            if ($request->file('image')) { //=file()ファイル選択ダイアログで、画像(bladeで設定した"image"を選択したかtrue or false
                //$path = Storage::disk('s3')->putFile('/',$profile_data['image'],'public');
                $path = $request->file('image')->store('public/image'); //任意の名前での保存練習中につきコメントアウト
                //$path = $request->file('image')->storeAs('public/tango');// store()は、画像の場所を返す。画像の場所を$pathへ代入する
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
        $user->image_path = $profile_data['image_path'];
        $user->save();
            // 該当するデータを上書きして保存する
          // $user->fill($profile_data)->save(); 
          // ユーザーの入力したデータを$userに渡して（fill）保存（save）
      return view('admin.course.profile', ['user' => $user]); // 
      
  }       
        // 7.3 コース画面を作るために追加
  public function index()  // 
  {     
      return view('admin.course.index'); // 
  }
       // 7.10 作成開始画面を作るために追加
  public function create()  // 
  {     
      return view('admin.course.create'); // 
  }
       // 7.10 単語帳orテストを作るために追加
  public function select()  // 
  {     
      return view('admin.course.select'); // 
  }
       // 7.10 単語帳画面を作るために追加
  //public function wordbook(Request $request)
  public function wordbook(Request $request)  
  {
        //説明… $requestの中のidとUserモデルの'id'【？】が一致するか確認。一致する場合、該当idのレコードを取得。$usersに代入する。
        //$users = User::where('id', $id)->get();
        
        $count = 1; // 最終的にページ数になる変数
        if(Course::all() -> count() == 0){
            $course = new Course;
            $course->id = 1;
            $course->front = 'フロント';
            $course->back = 1;
        }else{
            $course = Course::find($request -> abc);// URLの？abc=で送った値を($request ->abc)で取得している。 例えばURLが、?abc=10であれば、find(10)になる 
             // ちなみにfindは DBで「id」を検索するメソッド（laravelの） 今回はCourseというDBを検索している
            $courses = Course::all(); // 取得した全ての配列を$coursesへ代入
            foreach($courses as $tmp){ // ＄tmpに1件ずつ$courses（配列）から取り出して入れていきます。foreach文は配列の数だけ回すfor文です。
              $count += 1; // ループが回るたびに$countが1増えていく
              if($tmp->id == $request->abc){ // id abc (例えば id:372など)比較して同じであれば表示する
                $course = $tmp; // $courseは、Viewファイルでpostへ渡す変数。これに結果を渡す
                break; // ここを通ると強制的にループが終わる。あまりいい実装ではない（特殊なケースが増える）
              }
            }
        }
        /*$course['id'] = 1;
        $course['front'] = 'フロント'; 
        $course['back'] = 1;*/
        $users = User::where('name', "tester")->get(); //戻り値を配列にして、Viewに渡す場合のコード。これに対して、インスタンスでViewに渡すのが↓の行
        $user = User::where('name', "tester")->first(); //->first() は、1件だけ取り出すメソッド。もし複数見つかったら、1件目 対して->get()は一致するすべてのデータを取り出す。
                                                        //->first() はUserクラスのインスタンスを取得 ->get()はUserクラスのコレクション（配列の型）で取得する
      
    
        return view('admin.course.wordbook', ['post' => $course, "all_courses_count" => $courses->count(),'page_num' => $count, 'hoge' =>'hello', 'user' => $user, 'users' =>$users]); //
  }
  
  
  // 7.3 csv作成画面を作るために追加(その１)
  public function csv()  // 
  {     
      return view('admin.course.csv'); // 
  }
  
  // 7.10 csv作成画面を作るために追加(その２)㈱ビヨンドのWebサイト参考
  public function csv2()  // 
  {     
      return view('admin.course.csv2'); // 
  }
  
         // 7.10 書き込み画面を作るために追加
  public function write()  // 
  {     
      return view('admin.course.write'); // 
  }
  
        // csv取り込みメソッドを作るために追加 (その１)
  /*public function upload_regist(Request $rq)
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
      // deleteのメソッドをここか$datalistの上の行に噛ませる。その際、同一のcategoryカラムを条件にdeleteするようにする。
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
   // 7.13 csvインポートメソッドを作るために追加(その２)
  public function flashcard()
{
}
}
