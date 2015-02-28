<?php

/**
 * 問題作成用モデル
 * ORM継承
 */
class Model_QuestionType extends Orm\Model{

    public static $_propertyies = array(
        "type_id",
        "type_name",
        "create_time",
        "update_time",
        "delete_flag",
    );
    public static $_table_name = "question_type_table";
    public static $_primary_key  = array("type_id");

    public static function toArray(array $myArray)
    {
        $res = array();
        foreach($myArray as $key => $value){
            $res[] = $value->to_array();
        }
        return $res;
    }

    //IDのみ返す
    public static function getIdOnly(array $questionTypeList)
    {
        $res = array();
        foreach($questionTypeList as $key => $value){
            $res[] = $value["type_id"];
        }
        return $res;
    }
}