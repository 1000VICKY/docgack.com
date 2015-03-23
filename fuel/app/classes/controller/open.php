<?php
use \Model\Clean;
use \Model\QuestionFormat;
class Controller_Open extends Controller_Template
{
    public function before()
    {
        parent::before();
        header("Content-type: text/html;charset=utf-8");
        $this->template->title = "公開中問題一覧";
        $this->template->footer = "独学.com Copyright 独学.com All Rights Reserved.";
    }
    /**
     * 問題閲覧を一問ずつ行っていく
     */
    public function action_index($projectId = null, $page = null)
    {
        try{
            $data = array();
            //指定のプロジェクトNoが公開か非公開かを常にチェック
            if(is_numeric($projectId) === false){
                throw new Exception("不正なURLです。");
            }
            $checkProjectId = Model_Project::query()->where("project_id", "=", $projectId)->get();
            $checkProjectId = Model_Project::toArray($checkProjectId);
            if((int)$checkProjectId[0]["project_public_flag"] !== 1){
                throw new Exception("非公開中のプロジェクトです。");
            }
            //指定のプロジェクトが公開中であれば設問一覧を取得
            $questionList = Model_Question::query()->where("project_id", "=", $projectId)->where("delete_flag", "=", 0)->get();
            $questionList = Model_Question::toArray($questionList);
            if(count($questionList) === 0){
                throw new Exception("設問が一問も登録されていません。<br />");
                exit();
            }
            $questionNumberList = array();
            foreach($questionList as $key => $value){
                $questionNumberList[] = $value["question_id"];
            }
            //URLパラメータの$page変数がnullの時のみ問題のシャッフルを行う。
            if($page === null){
                shuffle($questionNumberList);
                Session::set("questionNumberList", $questionNumberList);
                $page = 0;
            }
            if((bool)Session::get("questionNumberList") === false){
                Response::redirect("/open/index/{$projectId}/");
            }

            Session::set("referer", "/admin/oneQuestion/{$projectId}/{$page}");
            $questionNumberList = Session::get("questionNumberList");
            $nowNumber = $questionNumberList[$page];

            $question = Model_Question::find($nowNumber);
            $question = $question->to_array();
            $question["choice_list"] = json_decode($question["choice_list"], true);
            if(json_last_error() !== JSON_ERROR_NONE){
                throw new Exception("「設問番号{$questionList[$key]["question_id"]}」の選択肢データの復号化に失敗しました。");
            }
            $question["choice_number"] = json_decode($question["choice_number"], true);
            if(json_last_error() !== JSON_ERROR_NONE){
                throw new Exception("「設問番号{$questionList[$key]["question_id"]}」の選択肢の回答データの復号化に失敗しました。");
            }
            $temp = array($question);
            QuestionFormat::escape($temp);
            $question = QuestionFormat::format($temp);
            $data["projectId"] = $projectId;
            $data["questionList"] = $question[0];
            $data["nowNumber"] = $nowNumber;
            $data["page"] = $page;
            $data["questionNumberList"] = $questionNumberList;
            $this->template->title = "公開中プロジェクト";
            $this->template->content = View::forge("open/index", $data, false);
        }catch(Exception $e){
            $data["errorMessage"] = $e->getMessage() . "<" . $e->getLine() . ":" . $e->getFile() . ">" ;
            $this->template->title = "設問文回答中エラー発生";
            $this->template->content = View::forge("error/index", $data, false);
        }
    }

}
