<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    private $userData = array();
    private $userId = null;
    public function __construct()
    {
        parent::__construct();
        /**
         * コントローラー事前処理
         * 各種ライブラリの取得
         */
        $this->load->database();
        $this->load->library("session");
        $this->load->library("Clean");
        $this->load->model('Project');
        $this->load->helper("url");
        $this->config->load("config");
        $this->salt = $this->config->item("salt");
        $this->data= [];
        $this->data["mainHeader"] = "独学.com";
        $this->data["title"] = "独学.com";
        $this->data["footer"] = "独学.com Copyright 独学.com All Rights Reserved.";
        $this->data["https"] = $this->input->server("HTTPS");
        $this->domain = $this->input->server("SERVER_NAME");

        $temp = $this->session->all_userdata();
        if( (array_key_exists("userData", $temp) === true) && (count($temp["userData"]) > 0) ){
            //user_idを個別のメンバ変数に格納
            $this->userId = $temp["userData"][0]->id;
        }else{
            if($this->data["https"] === "on"){
                redirect("https://{$this->domain}");
            }
            exit("リダイレクト中");
        }
    }

    /**
     * 管理画面トップのメソッド
     * @return void
     */
    public function index()
    {
        try{
            $res = $this->Project->getProjectList($this->userId);
            $this->data["projectList"] = $res;
            $this->data["userId"] = $this->userId;
            $this->load->view("common/header", $this->data);
            $this->load->view("admin/index", $this->data);
            $this->load->view("common/footer", $this->data);
        }catch(Exception $e){
            $this->data["errorMessage"] = $e->getMessage();
            $this->load->view("common/header", $this->data);
            $this->load->view("error/index", $this->data);
            $this->load->view("common/footer", $this->data);
        }
    }

    /**
     * ログアウト処理
     * @return void
     */
    public function logout()
    {
        try{
            //セッションをNULL状態にする
            $this->session->unset_userdata("userData");
            $this->session->unset_userdata("newAddQuestion");
            if($this->session->userdata("userData") === null){
                redirect("https://{$this->domain}");
                exit("リダイレクト中");
            }
            throw new Exception("ログアウト処理に失敗しました。再度ログアウトをして下さい。");
        }catch(Exception $e){
            $this->data["errorMessage"] = $e->getMessage();
            $this->load->view("common/header", $this->data);
            $this->load->view("error/index", $this->data);
            $this->load->view("common/footer", $this->data);
        }
    }

    /**
     * プロジェクトの追加
     * @return void
     */
    public function addProject()
    {
        try{
            $this->load->view("common/header", $this->data);
            $this->load->view("admin/addProject", $this->data);
            $this->load->view("common/footer", $this->data);
        }catch(Exception $e){
            $this->data["errorMessage"] = $e->getMessage();
            $this->load->view("common/header", $this->data);
            $this->load->view("error/index", $this->data);
            $this->load->view("common/footer", $this->data);
        }
    }
    public function insertProject()
    {
        try{
            $postData = $this->input->post();
            $res = $this->Project->insertNewProject($this->userId, $postData);
            if($res === true){
                redirect("https://{$this->domain}/admin/index");
                exit();
            }
            throw new Exception("新規プロジェクトの登録に失敗しました。");
        }catch(Exception $e){
            $this->data["errorMessage"] = $e->getMessage();
            $this->load->view("common/header", $this->data);
            $this->load->view("error/index", $this->data);
            $this->load->view("common/footer", $this->data);
        }
    }
    /**
     * プロジェクトの編集画面
     * @param int $projectId
     * @param void
     */
    public function editProject($projectId = null)
    {
        try{
            $projectId = (int)$projectId;
            $res = $this->Project->getTargetProject($this->userId, $projectId);
            $this->data["projectData"] = $res;
            $this->load->view("common/header", $this->data);
            $this->load->view("admin/editProject", $this->data);
            $this->load->view("common/footer", $this->data);
        }catch(Exception $e){
            $this->data["errorMessage"] = $e->getMessage();
            $this->load->view("common/header", $this->data);
            $this->load->view("error/index", $this->data);
            $this->load->view("common/footer", $this->data);
        }
    }
    /**
     * プロジェクトの更新
     * @return void
     */
    public function updateProject()
    {
        try{
            if($this->input->server("REQUEST_METHOD") === "POST"){
                $userId = $this->userId;
                $inputData = $this->input->post();
                $projectId = $inputData["projectId"];
                $dateObj = new DateTime();
                $updateTime = $dateObj -> getTimestamp();
                $updateData = [
                    "projectName" => $inputData["projectName"],
                    "projectPublicFlag" => $inputData["projectPublicFlag"],
                    "updateTime" => $updateTime
                ];
                $res = $this->Project->updateTargetProject($userId, $projectId, $updateData);

                if($res === true){
                    redirect("https://{$this->domain}/admin/index");
                    exit("プロジェクト情報の更新中");
                }
            }else{
                throw new Exception("不正なアクセスです。");
            }
        }catch(Exception $e){
            $this->data["errorMessage"] = $e->getMessage();
            $this->load->view("common/header", $this->data);
            $this->load->view("error/index", $this->data);
            $this->load->view("common/footer", $this->data);
        }
    }

    /**
     * プロジェクトの削除
     * @param int $projectId
     * @return void
     */
    public function deleteProject($projectId = null)
    {
        try{
            $requestMethod = strtoupper($this->input->server("REQUEST_METHOD"));
            if($requestMethod === "GET"){
                $this->data["projectId"] = $projectId;
                $this->load->view("common/header", $this->data);
                $this->load->view("admin/deleteProject", $this->data);
                $this->load->view("common/footer", $this->data);
            }else if($requestMethod === "POST"){
                $projectId = (int)$this->input->post("projectId");
                $userId = $this->userId;
                $res = $this->Project->deleteTargetProject($userId, $projectId);
                if($res === true){
                    redirect("https://{$this->domain}/admin/index");
                    exit();
                }
            }
        }catch(Exception $e){
            $this->data["errorMessage"] = $e->getMessage();
            $this->load->view("common/header", $this->data);
            $this->load->view("error/index", $this->data);
            $this->load->view("common/footer", $this->data);
        }
    }
    /**
     * 指定したプロジェクトの既存設問の編集作業
     */
    public function editQuestion($projectId = null)
    {
        try{
            $projectId = (int)$projectId;
            $userId = $this->userId;
            if($this->Project->checkProjectId($userId, $projectId) === true){
                $res = $this->Project->getSelectedProjectQuestionList($projectId);
                if(count($res) === 0){
                    throw new Exception("指定したプロジェクトには、設問が存在しません。");
                }
                $this->data["questionList"] = $res;
                $this->data["projectId"] = $projectId;
                $this->load->view("common/header", $this->data);
                $this->load->view("admin/editQuestion", $this->data);
                $this->load->view("common/footer", $this->data);
            }else{
                throw new Exception("指定したプロジェクトが不正です。");
            }
        }catch(Exception $e){
            $this->data["errorMessage"] = $e->getMessage();
            $this->load->view("common/header", $this->data);
            $this->load->view("error/index", $this->data);
            $this->load->view("common/footer", $this->data);
        }
    }
    /**
     * 指定したプロジェクトに対して新規設問を追加する
     * @param int $projectId
     * @param int $flat
     * @return void
     */
    public function addQuestion($projectId = null, $flat = null)
    {
        try{
            $userId = $this->userId;
            if($this->Project->checkProjectId($userId, $projectId) !== true){
                throw new Exception("指定したプロジェクトがあなたのプロジェクトではありません。");
            }
            //選択肢一覧のデフォルト配列を用意
            $choiceList = [];
            //新規追加設問情報がセッションに保存されていればそれを表示
            $tempData = $this->session->userdata("newAddQuestion");
            if($tempData === null){
                $tempInt = 6;
                for($i = 0; $i < $tempInt; $i++){
                    $choiceList[] = array("choiceNumber" => $i, "choiceText" => "");
                }
                $tempData = [
                    "projectId" => $projectId,
                    "questionType" => 1,
                    "questionTitle" => "",
                    "questionText" => "",
                    "choiceList" => $choiceList,
                    "explanationText" => "",
                ];
            }else {
                $tempInt = count($tempData["choiceList"]);
                if($tempInt === 0){
                    $tempInt = 6;
                    for($i = 0; $i < $tempInt; $i++){
                        $choiceList[] = array("choiceNumber" => $i, "choiceText" => "");
                    }
                    $tempData = [
                        "projectId" => $projectId,
                        "questionType" => 1,
                        "questionTitle" => "",
                        "questionText" => "",
                        "choiceList" => $choiceList,
                        "explanationText" => "",
                    ];
                }
            }
            $questionTypeList = $this->Project->getQuestionTypeList();
            $this->data["questionTypeList"] = $questionTypeList;
            $this->data["projectId"] = $projectId;
            $this->data["defaultSelectCount"] = $tempInt;
            $this->data["tempData"] = $tempData;
            $this->load->view("common/header", $this->data);
            $this->load->view("admin/addQuestion", $this->data);
            $this->load->view("common/footer", $this->data);
        }catch(Exception $e){
            $this->data["errorMessage"] = $e->getMessage();
            $this->load->view("common/header", $this->data);
            $this->load->view("error/index", $this->data);
            $this->load->view("common/footer", $this->data);
        }
    }
    /**
     * 新規設問登録の入力チェックを行う。
     */
    public function checkQuestion()
    {
        try{
            if(strtoupper($this->input->server("REQUEST_METHOD")) !== "POST"){
                throw new Exception("不正なリクエストです。");
            }
            $inputData = $this->input->post();
            //サニタイズ処理
            foreach($inputData as $key => $value){
                if(is_array($value)){
                    foreach($value as $innerKey => $innerValue){
                        $value[$innerKey] = Clean::cleaning($innerValue);
                    }
                }
                $inputData[$key] = Clean::cleaning($value);
            }

            //選択肢一覧の作成
            $choiceList = array();
            $i = 0;
            foreach($inputData["choiceList"] as $key => $value){
                $value = Clean::cleaning($value);
                if(strlen($value) > 0){
                    $choiceList[] = array("choiceNumber" => $i++, "choiceText" => $value);
                }
            }
            $inputData["choiceList"] = $choiceList;
            //新規追加設問情報をセッションに保存
            if(count($choiceList) > 0){
                $this->session->set_userdata("newAddQuestion", $inputData);
            }
            $questionIdList =[];
            $questionTypeList = $this->Project->getQuestionTypeList();
            foreach($questionTypeList as $key => $value){
                $questionIdList[] = (int)$value->type_id;
                if(array_key_exists("questionType", $inputData) === true){
                    if((int)$inputData["questionType"] === (int)$value->type_id){
                        $inputData["questionTypeName"] = $value->type_name;
                        $inputData["questionTypeAttr"] = $value->type_attr;
                    }
                }else {
                    throw new Exception("設問タイプは必ず選択して下さい。");
                }
            }
            //以下バリデーション
            if((array_key_exists("questionType", $inputData) === true) && (in_array($inputData["questionType"], $questionIdList) === true)){
                if((int)$inputData["questionType"] === 1){
                    $choiceList = array();
                    $choiceList[] = array("choiceNumber" => 0, "choiceText" => "論述型用のダミー選択肢のため無視してください。");
                    $inputData["choiceList"] = $choiceList;
                    $inputData["questionType"] = 2;
                    foreach($questionTypeList as $key => $value){
                        $questionIdList[] = (int)$value->type_id;
                        if(array_key_exists("questionType", $inputData) === true){
                            if((int)$inputData["questionType"] === (int)$value->type_id){
                                $inputData["questionTypeName"] = $value->type_name;
                                $inputData["questionTypeAttr"] = $value->type_attr;
                            }
                        }else {
                            throw new Exception("設問タイプは必ず選択して下さい。");
                        }
                    }
                }
            }else {
                throw new Exception("設問タイプは必ず選択して下さい。");
            }
            if(strlen($inputData["questionTitle"]) === 0){
                throw new Exception("設問文のタイトルを必ず入力して下さい。");
            }
            if(strlen($inputData["questionText"]) === 0){
                throw new Exception("設問文を必ず入力して下さい。");
            }
            if(count($choiceList) === 0){
                throw new Exception("選択肢はかならず一つは入力して下さい。");
            }
            /*
            if(strlen($inputData["explanationText"]) === 0){
                throw new Exception("解説文は必ず入力して下さい。");
            }*/
            $this->data["serializeData"] = base64_encode(serialize($inputData));
            $this->data["inputData"] = $inputData;
            $this->load->view("common/header", $this->data);
            $this->load->view("admin/checkQuestion", $this->data);
            $this->load->view("common/footer", $this->data);
        }catch(Exception $e){
            $this->data["errorMessage"] = $e->getMessage();
            $this->load->view("common/header", $this->data);
            $this->load->view("error/index", $this->data);
            $this->load->view("common/footer", $this->data);
        }
    }
    /**
     * 新規設問登録の入力チェックでバリデーション処理などが済んだものを
     * DBへと新規登録させる。
     */
    public function completeQuestion()
    {
        try{
            $inputData = $this->input->post();
            if(array_key_exists("selectAnswer", $inputData) !== true){
                throw new Exception("回答欄一覧から必ず一つ以上回答を選択して下さい。");
            }
            $projectId = $inputData["projectId"];
            $newQuestionData = unserialize(base64_decode($inputData["inputData"]));
            $newQuestionData["selectAnswer"] = $inputData["selectAnswer"];
            $res = $this->Project->addNewQuestion($projectId, $newQuestionData);
            //新規追加設問情報をセッションから削除する。
            $this->session->unset_userdata("newAddQuestion");
            if($res === true){
                redirect("https://{$this->domain}/admin/editProject/{$projectId}");
                exit("リダイレクト中");
            }
            throw new Exception("新規設問の登録に失敗しました。");
        }catch(Exception $e){
            $this->data["errorMessage"] = $e->getMessage();
            $this->load->view("common/header", $this->data);
            $this->load->view("error/index", $this->data);
            $this->load->view("common/footer", $this->data);
        }
    }
    /**
     * 指定のプロジェクトの既存問題の修正作業
     * @param int $projectId
     * @param int $questionId
     * @param int $flat
     */
    public function modifyQuestion($projectId = null, $questionId = null, $flat = null)
    {
        try{
            $userId = $this->userId;
            $res = $this->Project->checkProjectId($userId, $projectId);
            if($res !== true){
                throw new Exception("不正なアクセスです。");
                exit("不正なアクセスです");
            }

            $questionTypeList = $this->Project->getQuestionTypeList();
            $res = $this->Project->getTargetQuestionData($questionId);
            $res[0]->choice_list = json_decode($res[0]->choice_list, true);
            $res[0]->choice_number = json_decode($res[0]->choice_number, true);
            $nowAnswer = base64_encode(serialize($res[0]->choice_number));

            $defaultSelectCount = count($res[0]->choice_list);
            //選択肢の入力欄の追加
            if($flat === null){
                $defaultSelectCount = count($res[0]->choice_list);
                $this->session->set_userdata("selectAnswerTotalCount", $defaultSelectCount);
            }else {
                if($flat === "up"){
                    $tempInt = (int)$this->session->userdata("selectAnswerTotalCount", $defaultSelectCount);
                    $this->session->set_userdata("selectAnswerTotalCount", ++$tempInt);
                    $res[0]->choice_list = array_pad($res[0]->choice_list, $tempInt, array("choiceText" => "", "choiceNumber" => ""));
                }else if($flat === "down"){
                    $tempInt = (int)$this->session->userdata("selectAnswerTotalCount", $defaultSelectCount);

                    if($tempInt > 1){
                        $this->session->set_userdata("selectAnswerTotalCount", --$tempInt);
                        $res[0]->choice_list = array_pad($res[0]->choice_list, $tempInt, array("choiceText" => "", "choiceNumber" => ""));
                    }
                }
                $defaultSelectCount = $tempInt;
            }

            $this->data["questionData"] = $res;
            $this->data["questionTypeList"] = $questionTypeList;
            $this->data["projectId"] = $projectId;
            $this->data["questionId"] = $questionId;
            $this->data["nowAnswer"] = $nowAnswer;
            $this->data["defaultSelectCount"] = $defaultSelectCount;
            $this->load->view("common/header", $this->data);
            $this->load->view("admin/modifyQuestion", $this->data);
            $this->load->view("common/footer", $this->data);
        }catch(Exception $e){
            $this->data["errorMessage"] = $e->getMessage();
            $this->load->view("common/header", $this->data);
            $this->load->view("error/index", $this->data);
            $this->load->view("common/footer", $this->data);
        }
    }
    /**
     *
     */
    public function modifyCheckQuestion()
    {
        try{
            if(strtoupper($this->input->server("REQUEST_METHOD")) === "POST"){
                $inputData = $this->input->post();
                /**
                 * 選択肢一覧の作成
                 * 空白のみの選択肢などを削除する。
                 */
                $choiceList = array();
                $i = 0;
                foreach($inputData["choiceList"] as $key => $value){
                    $value = Clean::cleaning($value);
                    if(strlen($value) > 0){
                        $choiceList[] = array("choiceNumber" => $i++, "choiceText" => $value);
                    }
                }
                $inputData["choiceList"] = $choiceList;
                $nowAnswer = unserialize(base64_decode($inputData["nowAnswer"]));
                $serializeData = base64_encode(serialize($inputData));
                $userId = $this->userId;
                $res = $this->Project->checkProjectId($userId, $inputData["projectId"]);
                if($res !== true){
                    throw new Exception("不正なアクセスです。");
                    exit("不正なアクセスです。");
                }
                $questionTypeList = $this->Project->getQuestionTypeList();
                $thisQuestionTypeName = "";
                foreach($questionTypeList as $key => $value){
                    if((int)$value->type_id === (int)$inputData["questionType"]){
                        $thisQuestionTypeName = $value->type_attr;
                        break;
                    }
                }
                $this->data["typeAttr"] = $value->type_attr;
                $this->data["questionTypeList"] = $questionTypeList;
                $this->data["questionType"] = $inputData["questionType"];
                $this->data["projectId"] = $inputData["projectId"];
                $this->data["questionId"] = $inputData["questionId"];
                $this->data["questionTitle"] = $inputData["questionTitle"];
                $this->data["questionText"] = $inputData["questionText"];
                $this->data["choiceList"] = $inputData["choiceList"];
                $this->data["explanationText"] = $inputData["explanationText"];
                $this->data["nowAnswer"] = $nowAnswer;
                $this->data["serializeData"] = $serializeData;
                $this->load->view("common/header", $this->data);
                $this->load->view("admin/modifyCheckQuestion", $this->data);
                $this->load->view("common/footer", $this->data);
            }else{
                throw new Exception("不正なHTTPリクエストです。");
            }
        }catch(Exception $e){
            $this->data["errorMessage"] = $e->getMessage();
            $this->load->view("common/header", $this->data);
            $this->load->view("error/index", $this->data);
            $this->load->view("common/footer", $this->data);
        }
    }
    public function modifyCompleteQuestion()
    {
        try{
            if(strtoupper($this->input->server("REQUEST_METHOD")) !== "POST"){
                throw new Exception("不正なHTTPリクエストです。");
            }
            $inputData = $this->input->post();
            if((int)$inputData["questionType"] === 3){
                if(count($inputData["selectAnswer"]) !== 1){
                    throw new Exception("択一選択型の場合は、選択肢は一つだけを選択して下さい。");
                }
            }
            $unserializeData = unserialize(base64_decode($inputData["inputData"]));
            $projectId = $inputData["projectId"];
            $questionId = $inputData["questionId"];
            $deleteFlag = 0;
            $dateTime = new DateTime();
            $updateData = [
                "question_title" => $unserializeData["questionTitle"],
                "question_text" => $unserializeData["questionText"],
                "choice_list" => json_encode($unserializeData["choiceList"]),
                "choice_number" => json_encode($inputData["selectAnswer"]),
                "question_type" => $inputData["questionType"],
                "explanation_text" => $unserializeData["explanationText"],
                "delete_flag" => $deleteFlag,
            ];
            $res = $this->Project->updateSelectTargetQuestionData($projectId, $questionId, $updateData);
            if($res === true){
                redirect("https://{$this->domain}/admin/editQuestion/{$projectId}/#{$questionId}");
                exit("リダイレクト中");
            }
            throw new Exception("システム異常です。データベースの更新に失敗しました。");
        }catch(Exception $e){
            $this->data["errorMessage"] = $e->getMessage();
            $this->load->view("common/header", $this->data);
            $this->load->view("error/index", $this->data);
            $this->load->view("common/footer", $this->data);
        }
    }

    /**
     * 問題の削除用メソッド
     * @param int $projectId
     * @param int $questionId
     */
    public function deleteQuestion($projectId = null, $questionId = null)
    {
        try{
            if($this->input->server("REQUEST_METHOD") === "GET"){
                if(is_numeric($projectId) === false || is_numeric($questionId) === false){
                    throw new Exception("不正なパラメータです。");
                }
                $this->data["projectId"] = $projectId;
                $this->data["questionId"] = $questionId;
                $this->load->view("common/header", $this->data);
                $this->load->view("admin/deleteQuestion", $this->data);
                $this->load->view("common/footer", $this->data);
            }else if($this->input->server("REQUEST_METHOD") === "POST"){
                $projectId = $this->input->post("projectId");
                $questionId = $this->input->post("questionId");
                $res = $this->Project->deleteQuestionMethod($projectId, $questionId);
                if($res === true){
                    redirect("https://{$this->domain}/admin/index");
                    exit("リダイレクト中");
                }
                $this->load->view("common/header", $this->data);
                $this->load->view("admin/deleteQuestion", $this->data);
                $this->load->view("common/footer", $this->data);
            }
        }catch(Exception $e){
            $this->data["errorMessage"] = $e->getMessage();
            $this->load->view("common/header", $this->data);
            $this->load->view("error/index", $this->data);
            $this->load->view("common/footer", $this->data);
        }
    }

    /**
     * 問題を一問ずつ回答していく
     * @param int $projectId
     * @param int $questionNumber
     */
    public function oneQuestion($projectId = null, $questionNumber = null)
    {
        try{
            /**
             * 事前に指定したプロジェクトIDがログイン中のユーザーのものであるかどうかを
             * 確認する。
             */
            if($questionNumber === null){
                $this->session->unset_userdata("questionNumberList");
            }

            $projectId = (int)$projectId;
            $userId = $this->userId;
            $this->data["projectId"] = $projectId;
            $this->data["questionNumber"] = $questionNumber;
            $this->data["userId"] = $userId;
            $this->data["page"] = $questionNumber;
            $res = $this->Project->checkProjectId($userId, $projectId);
            if($res === true){
                //セッションに$questionNumberListが保持されている場合
                if($this->session->userdata("questionNumberList") !== null){
                    $questionNumber = (int)$questionNumber;
                    $questionNumberList = $this->session->userdata("questionNumberList");
                    $questionData = $this->Project->getSelectedTargetQuestionData($projectId, $questionNumberList[$questionNumber]);
                    $this->data["questionData"] = $questionData;
                    $this->data["questionNumberList"] = $questionNumberList;
                    $this->load->view("common/header", $this->data);
                    $this->load->view("admin/oneQuestion", $this->data);
                    $this->load->view("common/footer", $this->data);
                }else {
                    //セッションに$questionNumberListが保持されていない場合
                    $questionNumber = (int)$questionNumber;
                    $questionNumberList = $this->Project->getQuestionNumberList($projectId);
                    $this->session->set_userdata("questionNumberList", $questionNumberList);
                    $questionData = $this->Project->getSelectedTargetQuestionData($projectId, $questionNumberList[$questionNumber]);
                    $this->data["questionData"] = $questionData;
                    $this->data["questionNumberList"] = $questionNumberList;
                    $this->load->view("common/header", $this->data);
                    $this->load->view("admin/oneQuestion", $this->data);
                    $this->load->view("common/footer", $this->data);
                }
            }else{
                throw new Exception("プロジェクトIDがログイン中のユーザーのものかどうかを確認できませんでした。");
            }
        }catch(Exception $e){
            $this->data["errorMessage"] = $e->getMessage();
            $this->load->view("common/header", $this->data);
            $this->load->view("error/index", $this->data);
            $this->load->view("common/footer", $this->data);
        }
    }
}
