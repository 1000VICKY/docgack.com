<?php

/**
 * プロジェクト作成用もモデル
 * ORM継承
 */
class Model_Project extends Orm\Model{

    public static $_propertyies = array(
        "project_id",
        "project_name",
        "project_public_flag",
        "user_id",
        "create_time",
        "update_time",
    );
    public static $_table_name = "project_table";
    public static $_primary_key  = array("project_id");

    public static function toArray(array $myArray)
    {
        $res = array();
        foreach($myArray as $key => $value){
            $res[] = $value->to_array();
        }
        return $res;
    }
    /**
     * プロジェクトトークンから当該のプロジェクトが
     * ログイン中のユーザーの管理内かどうかをチェックする。
     * @param int $userId
     * @param int $projectId
     * @return mixed
     */
    public static function checkProjectId($userId, $projectId)
    {
        $res = self::find("all", array("where" => array("project_id" => $projectId)));
        if(count($res) === 0){
            return false;
        }
        $res = self::toArray($res);
        if((int)$res[0]["user_id"] === (int)$userId){
            return true;
        }
        return false;
    }
}