<?php
/**
 *   @引数にとる変数のクリーニングを行う。
 *   @文字列の前後の空白、改行、ヌルバイト文字の削除などを行う。
 */

namespace Model;
class Clean extends \Model{
    /**
     *  外部入力されたデータのサニタイズ処理
     *  @param string $cleaningParam
     *  @return string $cleaningParam
     */
    public static function cleaning($cleaningParam){

        //メソッドに渡された引数が配列かどうかのチェック
        if(is_string($cleaningParam) !== true){
            //配列の場合は、配列のまま値を返す。
            return $cleaningParam;
        }
        /**
         * 事前処理
         * 全角英数字を半角英数字に置換する
         */
        $cleaningParam = mb_convert_kana($cleaningParam,"a","UTF8");
        $res = preg_match("/^[ 　\r\n\n\r]+$/u", $cleaningParam, $match);
        if($res === 1){
            return null;
        }
        //POSTされたデータの前後の空白や改行の類を削除する。
        $cleaningParam = preg_replace("/^[ 　]+/u", "", $cleaningParam);
        $cleaningParam = preg_replace("/[ 　]+$/u", "", $cleaningParam);
        $cleaningParam = ltrim($cleaningParam);
        $cleaningParam = rtrim($cleaningParam);
        //渡された文字列データから改行文字、全角スペース、ヌルバイト文字を削除する
        $targetRegex = array("/\\0/", "/\\x00/");
        $targetReplace = array("", "");
        $cleaningParam = preg_replace($targetRegex,$targetReplace,$cleaningParam);
        //入力された文字列のタグをエスケープする。
        //$cleaningParam = htmlspecialchars($cleaningParam, ENT_QUOTES);
        //$cleaningParam = filter_var($cleaningParam,FILTER_SANITIZE_STRING);
        return $cleaningParam;
    }
}