<?php
/**
 *   @引数にとる変数のクリーニングを行う。
 *   @文字列の前後の空白、改行、ヌルバイト文字の削除などを行う。
 */

namespace Model;
class Questionformat extends \Model
{
    public static function format($param){
        $outputArray = array();
        foreach($param as $key => $value){
            if((int)$value["question_type"] === 1){
                foreach($value["choice_list"] as $innerKey => $innerValue){
                    $param[$key]["question_list_format"] = array();
                }
            }else if((int)$value["question_type"] === 2){
                foreach($value["choice_list"] as $innerKey => $innerValue){
                    $tempVal = <<< EOF
                        <div class="checkbox">
                            <label for="answerList{$key}_{$innerKey}">
                                <input id="answerList{$key}_{$innerKey}" type="checkbox" name="answerList" value="{$innerValue["choiceNumber"]}" />
                                <pre class="inputClick" >{$innerValue["choiceText"]}</pre>
                            </label>
                        </div>

EOF;
                    $param[$key]["question_list_format"][] = $tempVal;
                }
            }else if((int)$value["question_type"] === 3){
                foreach($value["choice_list"] as $innerKey => $innerValue){
                    $tempVal = <<< EOF
                        <div class="radio">
                            <label for="answerList{$key}_{$innerKey}">
                                <input class="" id="answerList{$key}_{$innerKey}" type="radio" name="answerList" value="{$innerValue["choiceNumber"]}" />
                                <pre class="inputClick" >{$innerValue["choiceText"]}</pre>
                            </label>
                        </div>

EOF;
                    $param[$key]["question_list_format"][] = $tempVal;
                }
            }
            shuffle($param[$key]["question_list_format"]);
        }
        return $param;
    }

    /**
     * 多次元配列を総ナメにして出力をエスケープ
     * @param array $value
     */
    public static function escape(&$param){
        foreach($param as $key => &$value){
            if(is_string($value) === true){
                $value = htmlspecialchars($value, ENT_QUOTES);
            }else if(is_array($value) === true){
                Questionformat::escape($value);
            }
        }
    }
}