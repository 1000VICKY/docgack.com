<?php
use \Model\Clean;
use \Model\QuestionFormat;
class Controller_Admin extends Controller_Template
{
    public function before()
    {
        parent::before();
        //ログインチェック
        if(Auth::check() !== true){
            Response::redirect("/");
            exit();
        }
        header("Content-type: text/html;charset=utf-8");
        $temp = Session::get();
        $this->userId = $temp["userId"];
        $this->userName = $temp["username"];
        $this->template->title = "ユーザーページ/初期ページ";
    }

    public function action_index()
    {
        try{
            $data = array();
            $projectList = Model_Project::find("all", array("where" => array("user_id" => $this->userId)));
            $res = Model_Project::toArray($projectList);
            $data["projectList"] = $res;
            $this->template->title = "ユーザーページ/あなたのMyページ";
            $this->template->content = View::forge("admin/index", $data);
        }catch(Exception $e){
            $data["errorMessage"] = $e->getMessage();
            $this->template->content = View::forge("error/index", $data);
        }
    }

    /**
     * 以下はプロジェクトに関する各メソッド群
     */
    public function action_newProject()
    {
        try{
            $data = array();
            $this->template->title = "ユーザーページ/新規プロジェクト作成";
            $this->template->content = View::forge("admin/project/newProject", $data);
        }catch(Exception $e){
            $this->template->content = View::forge("error/index", $data);
        }
    }
    public function action_addProject()
    {
        try{
            $data = array();
            $input = Input::post();
            $input["projectName"] = Clean::cleaning($input["projectName"]);
            if(mb_strlen($input["projectName"], "UTF-8") < 3){
                throw new Exception("プロジェクト名は、かならず3文字以上入力してください。");
            }
            if(array_key_exists("projectPublicLevel", $input) === true){
                if( (int)$input["projectPublicLevel"] === 0 || (int)$input["projectPublicLevel"] === 1){
                }else {
                    throw new Exception("プロジェクトの「公開」「非公開」は必ず選択してください。");
                }
            }else {
                throw new Exception("プロジェクトの公開レベルを選択してください。");
            }
            //新規プロジェクト登録データ配列の作成
            $insertData = array(
                "project_name" => $input["projectName"],
                "project_public_flag" => $input["projectPublicLevel"],
                "user_id" => $this->userId,
                "create_time" => $this->dateObj->format("Y-m-d H:i:s"),
                "update_time" => $this->dateObj->format("Y-m-d H:i:s"),
            );
            $model = new Model_Project($insertData);
            if($model->save()){
                Response::redirect("/admin/index");
                exit();
            }
            throw new Exception("新規プロジェクトの追加に失敗しました。");
        }catch(Exception $e){
            $data["errorMessage"] = $e->getMessage();
            $this->template->content = View::forge("error/index", $data);
        }
    }

    public function action_editProject($projectId = null)
    {
        try{
            $data = array();
            $checkProjectId = Model_Project::checkProjectId($this->userId, $projectId);
            if($checkProjectId !== true){
                throw new Exception("本プロジェクトはログイン中ユーザーの管理ではありません。");
            }
            $res = Model_Project::find("all", array("where" => array("project_id" => $projectId)));
            $res = Model_Project::toArray($res);
            $data["projectData"] = $res;
            $data["publicCheck"]  = (string)"";
            $data["privateCheck"] = (string)"";
            if((int)$res[0]["project_public_flag"] === 1){
                $data["publicCheck"]  = "checked=checked";
                $data["privateCheck"] = "";
            }else{
                $data["publicCheck"]  = "";
                $data["privateCheck"] = "checked=checked";
            }
            $this->template->title = "ユーザーページ/新規プロジェクト作成";
            $this->template->content = View::forge("admin/project/editProject", $data);
        }catch(Exception $e){
            $data["errorMessage"] = $e->getMessage() . $e->getLine();
            $this->template->title = "ユーザーページ/設問文新規作成時エラー発生";
            $this->template->content = View::forge("error/index", $data);
        }
    }
    public function action_updateProject()
    {
        try{
            $data = array();
            $input = Input::post();
            $input["projectName"] = Clean::cleaning($input["projectName"]);
            if(mb_strlen($input["projectName"]) < 3){
                throw new Exception("プロジェクト名は、かならず3文字以上入力してください。");
            }
            $userName = $this->userName;
            $projectId = $input["projectId"];
            //既存プロジェクトの更新作業
            $model = Model_Project::find($projectId);
            $updateData = array(
                "project_name" => $input["projectName"],
                "project_public_flag" => $input["projectPublicLevel"],
                "update_time" => $this->dateObj->format("Y-m-d H:i:s"),
            );
            $model->set($updateData);
            $model->save();
            Response::redirect("/admin/index");
            exit();
        }catch(Exception $e){
            $data["errorMessage"] = $e->getMessage();
            $this->template->title = "ユーザーページ/設問文新規作成時エラー発生";
            $this->template->content = View::forge("error/index", $data);
        }
    }

    /**
     * 以下は設問文作成に関する各メソッド群
     */
    public function action_newQuestion($projectId, $flat = null)
    {
        //変数初期化
        $data = array();
        try{
            //projectトークンを使って管理権限を検証する。
            $checkProjectId = Model_Project::checkProjectId($this->userId, $projectId);
            if($checkProjectId !== true){
                throw new Exception("本プロジェクトはログイン中ユーザーの管理ではありません。");
            }
            //選択肢の増減
            if($flat === null){
                Session::set("selectAnswerTotalCount", 6);
                $tempInt = 6;
            }else {
                if($flat === "up"){
                    $tempInt = (int)Session::get("selectAnswerTotalCount");
                    Session::set("selectAnswerTotalCount", ++$tempInt);
                }else if($flat === "down"){
                    //最低値は[1]
                    $tempInt = (int)Session::get("selectAnswerTotalCount");
                    if($tempInt > 1){
                        Session::set("selectAnswerTotalCount", --$tempInt);
                    }
                }
            }
            $defaultSelectCount = $tempInt;
            $questionTypeList = Model_QuestionType::find("all");
            $questionTypeList = Model_QuestionType::toArray($questionTypeList);
            $projectData = Model_Project::find("all", array("where" => array("project_id" => $projectId)));
            $projectData = Model_Project::toArray($projectData);
            $data["questionTypeList"] = $questionTypeList;
            $data["projectId"] = $projectData[0]["project_id"];
            $data["userId"] = $this->userId;
            $data["defaultSelectCount"] = $defaultSelectCount;
            $this->template->title = "ユーザーページ/新規設問文作成";
            $this->template->content = View::forge("admin/question/newQuestion", $data);
        }catch(Exception $e){
            $data["errorMessage"] = $e->getMessage();
            $this->template->title = "ユーザーページ/設問文新規作成時エラー発生";
            $this->template->content = View::forge("error/index", $data);
        }
    }
    public function action_checkQuestion()
    {
        try{
            if(strtoupper(Input::method()) !== "POST"){
                throw new Exception("不正なリクエストです。");
            }
            $data = array();
            $input = array();
            //サニタイズ処理
            foreach(Input::post() as $key => $value){
                $input[$key] = Clean::cleaning($value);
            }
            //選択肢一覧の作成
            $choiceList = array();
            $i = 0;
            foreach($input["choiceList"] as $key => $value){
                $value = Clean::cleaning($value);
                if(strlen($value) > 0){
                    $choiceList[] = array("choiceNumber" => $i++, "choiceText" => $value);
                }
            }
            $questionTypeList = Model_QuestionType::find("all");
            $questionTypeList = Model_QuestionType::toArray($questionTypeList);
            $questionIdList = Model_QuestionType::getIdOnly($questionTypeList);
            //以下バリデーション
            if((array_key_exists("questionType", $input) === true) && (in_array($input["questionType"], $questionIdList) === true)){
                if((int)$input["questionType"] === 1){
                    $choiceList = array();
                    $choiceList[] = array("choiceNumber" => 0, "choiceText" => "論述型用のダミー選択肢のため無視してください。");
                }
            }else {
                throw new Exception("設問タイプは必ず選択して下さい。");
            }
            if(strlen($input["questionTitle"]) === 0){
                throw new Exception("設問文のタイトルを必ず入力して下さい。");
            }
            if(strlen($input["questionText"]) === 0){
                throw new Exception("設問文を必ず入力して下さい。");
            }
            if(count($choiceList) === 0){
                throw new Exception("選択肢はかならず一つは入力して下さい。");
            }
            if(strlen($input["explanationText"]) === 0){
                throw new Exception("解説文は必ず入力して下さい。");
            }
            $data["projectId"] = $input["projectId"];
            $data["questionTitle"] = $input["questionTitle"];
            $data["choiceList"] = $choiceList;
            $data["questionType"] = $input["questionType"];
            $data["questionTypeList"] = $questionTypeList;
            $data["questionText"] = $input["questionText"];
            $data["explanationText"] = $input["explanationText"];
            $data["serializeData"] = base64_encode(serialize($data));
            $this->template->title = "ユーザーページ/新規設問文回答の選択";
            $this->template->content = View::forge("admin/question/checkQuestion", $data);
        }catch(Exception $e){
            $data["errorMessage"] = $e->getMessage(). $e->getLine();
            $this->template->title = "ユーザーページ/設問文編集時エラー発生";
            $this->template->content = View::forge("error/index", $data);
        }
    }
    public function action_completeQuestion()
    {
        try{
            $data = array();
            $inputAll = Input::post();
            $input = unserialize(base64_decode(Input::post("inputData")));

            if((int)$inputAll["questionType"] === 1){
                //論述式の場合
                $input["answerList"] = array();
            }else{
                //選択問題の場合
                //選択肢の中から正解の選択肢にチェックが入っているかどうかをチェック
                if(array_key_exists("selectAnswer", $inputAll) === true){
                    $selectAnswerCount = count($inputAll["selectAnswer"]);
                    //択一選択の場合
                    if((int)$input["questionType"] === 3){
                        if($selectAnswerCount !== 1){
                            throw new Exception("択一選択の場合は、回答は一つのみです。");
                        }
                    }
                    $input["answerList"] = array();
                    $input["answerList"] = $inputAll["selectAnswer"];
                }else{
                    throw new Exception("選択肢から回答を選択してください。");
                }
            }

            $choiceList = json_encode($input["choiceList"]);
            $answerList = json_encode($input["answerList"]);
            //question_tableへ設問の追加処理を行う。
            $insertData = array(
                "project_id" => $input["projectId"],
                "question_title" => $input["questionTitle"],
                "question_text" => $input["questionText"],
                "question_type" => $input["questionType"],
                //選択肢一覧
                "choice_list" => $choiceList,
                //解答番号
                "choice_number" => $answerList,
                "explanation_text" => $input["explanationText"],
                "create_time" => $this->dateObj->format("y-m-d H:i:s"),
                "update_time" => $this->dateObj->format("y-m-d H:i:s"),
                "delete_flag" => 0,
            );
            $model = Model_Question::forge($insertData);
            if($model->save() === true){
                Session::set("selectAnswerTotalCount", null);
                Response::redirect("/admin/editQuestion/{$input["projectId"]}");
                exit();
            }
            throw new Exception("新規設問文の追加に失敗しました。");
        }catch(Exception $e){
            $data["errorMessage"] = $e->getMessage();
            $this->template->title = "ユーザーページ/設問文編集時エラー発生";
            $this->template->content = View::forge("error/index", $data);
        }
    }
    public function action_editQuestion($projectId = null)
    {
        try{
            //projectトークンを使って管理権限を検証する。
            $checkProjectId = Model_Project::checkProjectId($this->userId, $projectId);
            if($checkProjectId !== true){
                throw new Exception("本プロジェクトはログイン中ユーザーの管理ではありません。");
            }
            $data = array();
            $questionList = Model_Question::find("all", array("where" => array("project_id" => $projectId),"order_by" => array("question_id" =>  "asc")));
            $questionList = Model_Question::toArray($questionList);
            $data["questionList"] = $questionList;
            $data["projectId"] = $projectId;
            $this->template->title = "ユーザーページ/既存設問文編集";
            $this->template->content = View::forge("admin/question/editQuestion", $data);
        }catch(Exception $e){
            $data["errorMessage"] = $e->getMessage();
            $this->template->title = "ユーザーページ/設問文編集時エラー発生";
            $this->template->content = View::forge("error/index", $data);
        }
    }

    public function action_modifyQuestion($projectId = null, $questionId = null, $flat = null)
    {
        try{
            $data = array();
            $choiceList = array();
            //projectトークンを使って管理権限を検証する。
            $checkProjectId = Model_Project::checkProjectId($this->userId, $projectId);
            if($checkProjectId !== true){
                throw new Exception("本プロジェクトはログイン中ユーザーの管理ではありません。");
            }
            $checkQuestionId = Model_Question::checkQuestionId($projectId, $questionId);
            if($checkQuestionId !== true){
                throw new Exception("指定の設問IDが指定のプロジェクトIDには存在しません。");
            }
            //DBから既存データを取得
            $questionData = Model_Question::find("all", array("where" => array("question_id" => $questionId, "project_id" => $projectId)));
            $questionData = Model_Question::toArray($questionData);
            $questionData[0]["choice_list"] = json_decode($questionData[0]["choice_list"], true);
            $questionData[0]["choice_number"] = json_decode($questionData[0]["choice_number"], true);
            $questionTypeList = Model_QuestionType::find("all");
            $questionTypeList = Model_QuestionType::toArray($questionTypeList);

            if($flat === null){
                $defaultSelectCount = count($questionData[0]["choice_list"]);
                Session::set("selectAnswerTotalCount", $defaultSelectCount);
            }else {
                if($flat === "up"){
                    $tempInt = (int)Session::get("selectAnswerTotalCount");
                    Session::set("selectAnswerTotalCount", ++$tempInt);
                    $questionData[0]["choice_list"] = array_pad($questionData[0]["choice_list"], $tempInt, array("choiceText" => "", "choiceNumber" => ""));
                }else if($flat === "down"){
                    $tempInt = (int)Session::get("selectAnswerTotalCount");
                    if($tempInt > 1){
                        Session::set("selectAnswerTotalCount", --$tempInt);
                    $questionData[0]["choice_list"] = array_pad($questionData[0]["choice_list"], $tempInt, array("choiceText" => "", "choiceNumber" => ""));
                    }
                }
                $defaultSelectCount = $tempInt;
            }
            QuestionFormat::escape($questionData);
            $data["projectId"] = $projectId;
            $data["questionId"] = $questionId;
            $data["questionData"] = $questionData;
            $data["questionTypeList"] = $questionTypeList;
            $data["defaultSelectCount"] = $defaultSelectCount;
            $this->template->title = "ユーザーページ/既存設問文編集";
            $this->template->subHeader = "独学用学習システム/既存設問の修正";
            $this->template->content = View::forge("admin/question/modifyQuestion", $data, false);
        }catch(Exception $e){
            $data["errorMessage"] = $e->getMessage();
            $this->template->title = "ユーザーページ/設問文編集時エラー発生";
            $this->template->content = View::forge("error/index", $data);
        }
    }
    public function action_modifyCheckQuestion()
    {
        try{
            $data = array();
            $input = array();
            foreach(Input::post() as $key => $value){
                $input[$key] = Clean::cleaning($value);
            }
            //選択肢の作成
            $tempList = array();
            $i = 0;
            foreach($input["choiceList"] as $key => $value){
                $value = Clean::cleaning($value);
                if(strlen($value) > 0 ){
                    $tempList[] = array("choiceNumber" => $i++, "choiceText" => $value);
                }
            }
            //DBへの保存用
            $rawChoiceList = $tempList;
            $rawQuestionTitle = $input["questionTitle"];
            $rawQuestionText = $input["questionText"];
            $rawExplanationText = $input["explanationText"];
            //画面表示用
            $input["choiceList"] = $tempList;
            //配列を再帰的にHTMLタグなどをエスケープ
            QuestionFormat::escape($input);
            //以下バリデーション
            $questionTypeList = Model_QuestionType::find("all");
            $questionTypeList = Model_QuestionType::toArray($questionTypeList);
            $questionIdList = Model_QuestionType::getIdOnly($questionTypeList);
            //以下バリデーション
            if( (array_key_exists("questionType", $input) === true) &&
                (in_array($input["questionType"], $questionIdList) === true) )
            {
                //論述式タイプの場合
                if((int)$input["questionType"] === 1)
                {
                    $input["choiceList"] = array(
                        "choiceText" => "論述型用のダミー選択肢のため無視してください。",
                        "choiceNumber" => 0
                    );
                }
            }else {
                throw new Exception("設問タイプは必ず選択して下さい。");
            }
            if(strlen($input["questionTitle"]) === 0){
                throw new Exception("設問文のタイトルを必ず入力して下さい。");
            }
            if(strlen($input["questionText"]) === 0){
                throw new Exception("設問文を必ず入力して下さい。");
            }
            if(count($input["choiceList"]) === 0){
                throw new Exception("選択肢はかならず一つは入力して下さい。");
            }
            if(strlen($input["explanationText"]) === 0){
                throw new Exception("解説文は必ず入力して下さい。");
            }
            $data["projectId"] = $input["projectId"];
            $data["questionId"] = $input["questionId"];
            $data["questionTitle"] = $input["questionTitle"];
            $data["questionType"] = $input["questionType"];
            $data["questionTypeList"] = $questionTypeList;
            $data["questionText"] = $input["questionText"];
            $data["choiceList"] = $input["choiceList"];
            $data["explanationText"] = $input["explanationText"];
            //生データ
            $data["rawQuestionTitle"] = $rawQuestionTitle;
            $data["rawQuestionText"] = $rawQuestionText;
            $data["rawExplanationText"] = $rawExplanationText;
            $data["rawChoiceList"] = $rawChoiceList;
            $data["serializeData"] = base64_encode(serialize($data));
            $this->template->title = "ユーザーページ/新規設問文回答の選択";
            $this->template->content = View::forge("admin/question/modifyCheckQuestion", $data);
        }catch(Exception $e){
            $data["errorMessage"] = $e->getMessage();
            $this->template->title = "ユーザーページ/設問文編集時エラー発生";
            $this->template->content = View::forge("error/index", $data);
        }
    }
    public function action_modifyCompleteQuestion()
    {
        try{
            $data = array();
            $inputAll = Input::post();
            $input = unserialize(base64_decode(Input::post("inputData")));
            if((int)$inputAll["questionType"] === 1){
                //論述式の場合
                $input["answerList"] = array();
            }else{
                //選択問題の場合
                //選択肢の中から正解の選択肢にチェックが入っているかどうかをチェック
                if(array_key_exists("selectAnswer", $inputAll) === true){
                    $selectAnswerCount = count($inputAll["selectAnswer"]);
                    //択一選択の場合
                    if((int)$input["questionType"] === 3){
                        if($selectAnswerCount !== 1){
                            throw new Exception("択一選択の場合は、回答は一つのみです。");
                        }
                    }
                    $input["choiceNumber"] = array();
                    $input["choiceNumber"] = $inputAll["selectAnswer"];
                }else{
                    throw new Exception("選択肢から回答を選択してください。");
                }
            }
            //選択肢一覧と解答番号一覧をJSON化する
            $rawChoiceList = json_encode($input["rawChoiceList"]);
            $choiceNumber = json_encode($input["choiceNumber"]);
            //question_tableへ設問の追加処理を行う。
            $insertData = array(
                "project_id" => $input["projectId"],
                "question_type" => $input["questionType"],
                "question_title" => $input["rawQuestionTitle"],
                "question_text" => $input["rawQuestionText"],
                "choice_list" => $rawChoiceList,
                "choice_number" => $choiceNumber,
                "explanation_text" => $input["rawExplanationText"],
                "create_time" => $this->dateObj->format("y-m-d H:i:s"),
                "update_time" => $this->dateObj->format("y-m-d H:i:s"),
                "delete_flag" => 0,
            );
            $model = Model_Question::find($input["questionId"]);
            $model->set($insertData);
            if($model->save() === true){
                Response::redirect("/admin/editQuestion/{$input["projectId"]}");
                exit();
            }
            throw new Exception("既存設問の更新作業に失敗しました。");
            exit();
        }catch(Exception $e){
            $data["errorMessage"] = $e->getMessage();
            $this->template->title = "ユーザーページ/設問文編集時エラー発生";
            $this->template->content = View::forge("error/index", $data);
        }
    }
    /**
     * 問題閲覧ページ
     * @param int $projectId
     */
    public function action_viewQuestion($projectId = null)
    {
        try{
            $data = array();
            $checkProjectId = Model_Project::checkProjectId($this->userId, $projectId);
            if($checkProjectId !== true){
                throw new Exception("不正なprojectIdです。");
            }
            $questionList = Model_Question::find("all", array("where" => array("project_id" => $projectId)));
            $questionList = Model_Question::toArray($questionList);
            foreach($questionList as $key => $value){
                $questionList[$key]["choice_list"] = json_decode($questionList[$key]["choice_list"], true);
                if(json_last_error() !== JSON_ERROR_NONE){
                    throw new Exception("「設問番号{$questionList[$key]["question_id"]}」の選択肢データの復号化に失敗しました。");
                }
                $questionList[$key]["choice_number"] = json_decode($questionList[$key]["choice_number"], true);
                if(json_last_error() !== JSON_ERROR_NONE){
                    throw new Exception("「設問番号{$questionList[$key]["question_id"]}」の選択肢の回答データの復号化に失敗しました。");
                }
            }
            QuestionFormat::escape($questionList);
            shuffle($questionList);
            $data["questionList"] = QuestionFormat::format($questionList);
            $this->template->title = "ユーザーページ/既存設問文編集";
            $this->template->content = View::forge("admin/question/viewQuestion", $data, false);
        }catch(Exception $e){
            $data["errorMessage"] = $e->getMessage();
            $this->template->title = "ユーザーページ/設問文回答中エラー発生";
            $this->template->content = View::forge("error/index", $data);
        }
    }

    public function action_logout()
    {
        try{
            $auth = Auth::instance();
            if ($auth->logout() === true) {
                Response::redirect("/");
                exit();
            } else {
                throw new Exception("ログアウトに失敗しました。");
            }
        }catch(Exception $e){
            $data["errorMessage"] = $e->getMessage();
            $this->template->title = "ユーザーページ/設問文回答中エラー発生";
            $this->template->content = View::forge("error/index", $data);
        }
    }
}
