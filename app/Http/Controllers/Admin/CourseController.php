<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

 // CSVを取り込むための宣言 2021.7
use Goodby\CSV\Import\Standard\LexerConfig;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;

use App\Course;

class CourseController extends Controller
{
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
  public function wordbook()  // 
  {     
      return view('admin.course.wordbook'); // 
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
  }
  
  
  
   // 7.10 csvインポートメソッドを作るために追加(その２)
  /*public function inportCsv(Request $request)
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
    $count = 0;
    foreach($dataList as $row){
        Book::insert(['title' => $row[0], 'price' => $row[1]]);
        $count++;
    }
 
    return redirect()->action('CourseController@csv2')->with('flash_message', $count . '枚のカードを登録しました！');
} */

   // 7.13 csvインポートメソッドを作るために追加(その３)
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
    $db_data = new Course;
    $db_data->where('category', 'EnglishWords')->delete();

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
        
    
        $hoge->save();
        
    }
 
    return redirect('admin/course/csv2')->with('done', count($dataList) . '件のデータを登録しました！');
}
}
