<?php

/**
 * 問題作成用モデル
 * ORM継承
 */
class Model_Question extends Orm\Model{

    public static $_propertyies = array(
        "question_id",
        "question_title",
        "question_text",
        "question_list",
        "choice_list",
        "question_type",
        "answer_text",
        "project_id",
        "create_time",
        "update_time",
        "delete_flag",
    );
    public static $_table_name = "question_table";
    public static $_primary_key  = array("question_id");

    public static function toArray(array $myArray)
    {
        $res = array();
        foreach($myArray as $key => $value){
            $res[] = $value->to_array();
        }
        return $res;
    }

    public static function checkQuestionId($projectId, $questionId)
    {
        $res = self::find("all", array("where" => array("question_id" => $questionId, "project_id" => $projectId)));
        if(count($res) === 0){
            return false;
        }
        return true;
    }
}