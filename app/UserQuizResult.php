<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Log;

class UserQuizResult extends Model
{
    protected $guarded = array('id');
    
    public static function timeFunc($running_time){
      
      $minuts = floor($running_time / 60);
      $seconds = $running_time - ($minuts * 60) ;
      if($minuts < 10){
        $minuts = "0" . $minuts;
      }
      if($seconds > 10){
        return $minuts .":" . $seconds;
      }else{
        return $minuts .":" . "0" . $seconds;
      }
    }
    
    public static function getRankingInCategoryAndQuestionQuantity($category, $question_quantity){
      $users = User::all();
      $rankings = [];
      foreach ($users as $user) {
        $ary = [];
        $maxCi = UserQuizResult::where('user_id', $user->id)->max('challenge_id');
        for ($ci = 1; $ci <= $maxCi; $ci++) {
          $num_of_challenge = UserQuizResult::where('user_id', $user->id)->where('challenge_id', $ci)->get();
          $n = count($num_of_challenge);
          if($n == $question_quantity){
            $user_quiz_results = UserQuizResult::where('user_id', $user->id)->where('challenge_id', $ci)->where('judgement', 2)->get();
            Log::info('start##########');
            Log::info($user_quiz_results);
            Log::info($user_quiz_results->first());
            Log::info(count($user_quiz_results));
            Log::info('end##########');
            if(count($user_quiz_results) > 0 && Course::find($user_quiz_results->first()->course_id)->category == $category){
                $modelForTimeAndDate = UserQuizResult::where('user_id', $user->id)->where('challenge_id', $ci)->orderBy('running_time','DESC')->first();
                $date = $modelForTimeAndDate->created_at->format('Y/m/d');
                $running_time = $modelForTimeAndDate->running_time;
                $success = count($user_quiz_results);
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
        // $i = 1;
        $found = false;
        for($i = 0; !$found && $i < count($rankings); $i++){
          $rank = $rankings[$i];
          foreach($rank["uqz"] as $uqz){
            if($uqz->user_id == $your_id){
              $found = true;
              break;
            }
          }
        }
        if(!$found){
          $your_highscore_rank_text ="{$your_name}さんは「{$category}」の{$question_quantity}問クイズに まだランクインしていません";
        }else{
          $your_highscore_rank = $i;
          $your_highscore_rank_text ="{$your_name}さんは「{$category}」の{$question_quantity}問クイズで現在{$your_highscore_rank}位 です！";
        }
      }
      
      return [$your_highscore_rank, $your_highscore_rank_text];
  }
}
