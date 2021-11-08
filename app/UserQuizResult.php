<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserQuizResult extends Model
{
    protected $guarded = array('id'); //'id'は通常、$guarded にする？
    
    public static function getRankingInCategoryAndQuestionQuantity($category, $question_quantity){
        
    //なんやかんや処理を書く
    // まずUserQuizResultモデルにおいて
    // Auth::id でuser_idを取得
    // UserQuizResult::::where('user_id', Auth::id)->where('challenge_id', $challenge_id);でchallengeを一つピックアップ
    // もしそのレコードのcourse_id のcategoryが$categoryで絞り込む
    // $questionNumで絞り込む
    // user_id=XX さんの challenge_id=XX の挑戦は category=XXXXにおいて、今xxx位です
    $users = User::all();
    $rankings = [];
    foreach ($users as $user) {
      $ary = []; // ここに正解回数が入る([0]が一回目の結果)
      $maxCi = UserQuizResult::where('user_id', $user->id)->max('challenge_id'); // そのユーザのチャレンジID最大値を取得
      for ($ci = 1; $ci <= $maxCi; $ci++) {
        $num_of_challenge = UserQuizResult::where('user_id', $user->id)->where('challenge_id', $ci)->get();
        $n = count($num_of_challenge);
        if($n == $question_quantity){
          $user_quiz_results = UserQuizResult::where('user_id', $user->id)->where('challenge_id', $ci)->where('judgement', 2)->get();
          if(count($user_quiz_results) > 0 && Course::find($user_quiz_results->first()->course_id)->category == $category){//category一致するならいいよ
              // 結果を示している、firstのレコードを入れましょう
              $modelForTimeAndDate = UserQuizResult::where('user_id', $user->id)->where('challenge_id', $ci)->orderBy('running_time','DESC')->first();
              $date = $modelForTimeAndDate->created_at->format('Y/m/d');
              $running_time = $modelForTimeAndDate->running_time;
              $success = count($user_quiz_results);
              // $date, $success, $running_time　の帳尻が合わないと、ランキングのソートが正常に動かなさそう
              // success = あるチャレンジの正答数
              // running_time = あるチャレンジでn問解くのにかかった時間
              // $date = あるチャレンジの終了日
              $ary = [ 'name' => $user->name, '正解回数' => $success, '挑戦日'=> $date, 'タイム'=>$running_time, 'uqz'=> $user_quiz_results];
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
      }//もし日付が前ループと同じなら、$ $existed_user_names[] は初期化しない
      
      if(in_array($checking_name, $existed_user_names)){ // 既存の名前をはじくために、配列に存在するかcheck
        unset($rankings[$count]);  //$rankingを$rankingsから削除する
      }else{
        array_push($existed_user_names, $checking_name);//unset()に該当しなかった名前は「既存の名前」に追加して、次のcheckで使用
      }
      $pre_date = $checking_date; //チェックした日付を次のループで使用する
      $count++;
    }
    $days = array_column($rankings, '挑戦日');
    $numbers = array_column($rankings, '正解回数');
    $times = array_column($rankings, 'タイム');
    $result = array_multisort($numbers, SORT_DESC, $times, SORT_ASC, $days, SORT_DESC,$rankings); // 今度は正解回数、タイム、挑戦日の優先順に並べ替える
    // dd($rankings);
    $your_name = Auth::user()->name;
    $your_highscore_rank = NULL;
    if($rankings== NULL){
      $your_highscore_rank_text ="「{$category}」の{$question_quantity}問クイズには まだ誰もランクインしていません";
    }else{
      $your_id = Auth::id();
      $i = 1;
      $found = false;
      foreach($rankings as $rank){
      // dd($your_id, Auth::user()->name, $rank["uqz"][$i]->user_id, $rank["uqz"][$i], $rankings);
        foreach($rank["uqz"]as $uqz){
          if($uqz->user_id == $your_id){
            $found = true;
            dd($i,$rankings);
            break;
          }
        }
        if($found){
          break;
        }
        $i++;
      //   dd($rank["uqz"][0]->course_id,$user->name);
      }
      if(!$found){
        $your_highscore_rank_text ="{$your_name}さんは「{$category}」の{$question_quantity}問クイズに まだランクインしていません";
      }else{
        $your_highscore_rank = $i;
        $your_highscore_rank_text ="{$your_name}さんは「{$category}」の{$question_quantity}問クイズで現在{$your_highscore_rank}位 です！";
      }
    }
    // dd($your_highscore_rank);
    
    return [$your_highscore_rank, $your_highscore_rank_text];
  }
}
