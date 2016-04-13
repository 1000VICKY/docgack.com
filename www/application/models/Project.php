<?php
class Project extends CI_Model {

    public function __construct()
    {
        // Model クラスのコンストラクタを呼び出す
        parent::__construct();
        $this->load->library("Clean");
    }

    /**
     * 新規プロジェクトの登録
     */
    public function insertNewProject($userId, $postData)
    {
        try{
            $postData["projectName"] = Clean::cleaning($postData["projectName"]);
            if(strlen($postData["projectName"]) === 0 ){
                throw new Exception ("プロジェクト名が空白です。");
            }
            $this->db->where("project_name", $postData["projectName"]);
            $this->db->where("user_id", $userId);
            $this->db->where("delete_flag", 0);
            $res = $this->db->get("project_table");
            if(count($res->result()) >= 1){
                throw new Exception("既に同名のプロジェクトが立ち上がっています。");
                exit();
            }
            $timeObj = new DateTime();
            $insertData = [
                "project_name" => $postData["projectName"],
                "project_public_flag" => $postData["projectPublicFlag"],
                "user_id" => $userId,
                "update_time" => $timeObj->createFromFormat("Y-m-d H:i:s", $timeObj->getTimestamp()),
                "delete_flag" => 0,
            ];
            $res = $this->db->insert("project_table", $insertData);
            if($res === true){
                return $res;
            }
            throw new Exception("新規プロジェクトの登録に失敗しました。");
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    /**
     * ログイン中のユーザーが現在、登録中のプロジェクト一覧の取得
     * delete_flag 0=>生存中,1=>削除中
     * @param int $userId
     * @return array
     */
    public function getProjectList($userId = null)
    {
        try{
            $userId = (int)$userId;
            $deleteFlag = 0;
            $this->db->where("user_id", $userId);
            $this->db->where("delete_flag", $deleteFlag);
            $query = $this->db->get('project_table');
            return $query->result();
        }catch(Exception $e){
            throw new Exception ($e->getMessage());
        }
    }
    /**
     * 指定したプロジェクトがログイン中のユーザーのものかどうかを確認
     * delete_flag 0=>生存中,1=>削除中
     * @param int $userId
     * @param int $projectId
     * @return array
     */
    public function checkProjectId($userId = null, $projectId)
    {
        try{
            $deleteFlag = 0;
            $this->db->where("user_id", $userId);
            $this->db->where("project_id", $projectId);
            $this->db->where("delete_flag", $deleteFlag);
            $res = $this->db->get("project_table");
            $res = $res->result();
            if(count($res) === 1){
                return true;
            }
            throw new Exception("指定されたプロジェクトIDはログイン中ユーザーのものではございません。");
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    /**
     * ログイン中のユーザー任意のプロジェクト情報の取得
     * delete_flag 0=>生存中,1=>削除中
     * @param int $userId
     * @param int $projectId
     * @return array
     */
    public function getTargetProject($userId = null, $projectId = null)
    {
        try{
            $deleteFlag = 0;
            $this->db->where("user_id", $userId);
            $this->db->where("project_id", $projectId);
            $this->db->where("delete_flag", $deleteFlag);
            $res = $this->db->get("project_table");
            $res = $res->result();
            if((int)$res[0]->project_public_flag === 1)
            {
                $res[0]->public_check = "checked=checked";
                $res[0]->private_check = "";
            }else if((int)$res[0]->project_public_flag === 2){
                $res[0]->public_check = "";
                $res[0]->private_check = "checked=checked";
            }else {
                $res[0]->public_check = "";
                $res[0]->private_check = "";
            }
            return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
            return array();
        }
    }

    /**
     * ログイン中のユーザーの任意のプロジェクトの情報の更新
     * @param int $userId
     * @param int $projectId
     * @return bool
     */
    public function updateTargetProject($userId = null, $projectId = null, array $updateData)
    {
        try{
            $updateData = [
                "project_name" => $updateData["projectName"],
                "project_public_flag" => $updateData["projectPublicFlag"],
                "update_time" => $updateData["updateTime"]
            ];
            $this->db->where("user_id", $userId);
            $this->db->where("project_id", $projectId);
            $res = $this->db->update("project_table", $updateData);
            if($res === true){
                return true;
            }
            throw new Exception ("プロジェクトの情報更新に失敗しました。");
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    /**
     * ログイン中のユーザーの任意のプロジェクト情報の削除
     * @param int $userId
     * @param int $projectId
     * @retrun bool
     */
    public function deleteTargetProject($userId = null, $projectId = null)
    {
        try{
            $deleteFlag = 1;
            $updateData = array(
                "delete_flag" => $deleteFlag,
            );
            $this->db->where("user_id", $userId);
            $this->db->where("project_id", $projectId);
            $res = $this->db->update("project_table", $updateData);
            if($res === true){
                return true;
            }
            throw new Exception("指定したプロジェクトの削除に失敗しました。");
            return false;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
            return false;
        }
    }

    /**
     * 指定したプロジェクトのquestion_idのみを取得
     * @param int $targetProject プロジェクトIDの指定
     * @retrun bool
     */
    public function getQuestionNumberList($targetProjectId = null)
    {
        try{
            //引数がint型の場合
            $targetProjectId = (int)$targetProjectId;
            if(is_int($targetProjectId) === true) {
                $deleteFlag = 0;
                $this->db->where("project_id", $targetProjectId);
                $this->db->where("delete_flag", $deleteFlag);
                $this->db->select("question_id");
                $query = $this->db->get("question_table");
                $res = $query->result();
                $questionNumberList = [];
                foreach($res as $key => $value){
                    $questionNumberList[] = (int)$value->question_id;
                }
                if(count($questionNumberList) === 0){
                    throw new Exception("選択したプロジェクトには、現在設問が一問も登録されていません。");
                }
                shuffle($questionNumberList);
                return $questionNumberList;
            }else {
                //引数がint型以外の場合例外発生
                throw new Exception("メソッドへ渡す引数が適切ではありません。");
            }
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 指定したプロジェクトの全カラムを取得
     * @param int $targetProject プロジェクトIDの指定
     * @retrun bool
     */
    public function getQuestionDataBySelectTargetId($targetProjectId = null, $taragetQuestionId = null)
    {
        try{
            //引数がint型の場合
            $targetProjectId = (int)$targetProjectId;
            if(is_int($targetProjectId) === true) {
                $deleteFlag = 0;
                $this->db->select("question_id, question_title, question_text, choice_list, choice_number, question_type, explanation_text, project_id");
                $this->db->where("project_id", $targetProjectId);
                $this->db->where("delete_flag", $deleteFlag);
                $this->db->where("question_id", $taragetQuestionId);
                $query = $this->db->get("question_table");
                $res = $query->result();
                return($res);
            }else {
                //引数がint型以外の場合例外発生
                throw new Exception("メソッドへ渡す引数が適切ではありません。");
            }
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }


    /**
     * 指定した設問を削除
     * delete_flag 0=>生存中,1=>削除中
     * @param int $projectId
     * @param int $deleteQuestionId
     * @param boolean
     */
    public function deleteQuestionMethod($projectId, $deleteQuestionId){
        try{
            $timeObj = new DateTime();
            $timeObj = $timeObj->getTimestamp();
            $deletFlag = 1;
            $updateArray = [
                "update_time" => $timeObj,
                "delete_flag" => $deletFlag
            ];
            $this->db->where("project_id", $projectId);
            $this->db->where("question_id", $deleteQuestionId);
            $res = $this->db->update("question_table", $updateArray);
            if($res === true){
                return true;
            }else{
                throw new Exception("指定した設問IDの設問の削除に失敗しました。");
            }
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    /**
     * 新規設問を登録
     * delete_flag 0=>生存中,1=>削除中
     * @param int $projectId
     * @param array $newQuestionData
     * @return bool
     */
    public function addNewQuestion($projectId, $newQuestionData)
    {
        try{
            $timeObj = new DateTime();
            $deleteFlag = 0;
            $insertData = [
                "question_title" => $newQuestionData["questionTitle"],
                "question_text" => $newQuestionData["questionText"],
                "choice_list" => json_encode($newQuestionData["choiceList"]),
                "choice_number" => json_encode($newQuestionData["selectAnswer"]),
                "question_type" => $newQuestionData["questionType"],
                "explanation_text" => $newQuestionData["explanationText"],
                "project_id" => $newQuestionData["projectId"],
                "create_time" => $timeObj->getTimestamp(),
                "update_time" => $timeObj->getTimestamp(),
                "delete_flag" => $deleteFlag,
            ];
            $res = $this->db->insert("question_table", $insertData);
            if($res === true){
                return true;
            }else{
                throw new Exception("新規設問の登録に失敗しました。");
            }
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    /**
     * 既存の設問の更新用メソッド
     * @param int $projectId
     * @param int $questionId
     * @param array $updateData
     * @return bool
     */
    public function updateSelectTargetQuestionData($projectId = null, $questionId = null, array $updateData)
    {
        try{
            $this->db->where("project_id", $projectId);
            $this->db->where("question_id", $questionId);
            $res = $this->db->update("question_table", $updateData);
            if($res === true){
                return true;
            }
            throw new Exception("システム異常です。データベースの更新に失敗しました。");
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    function insert_entry()
    {
        $this->title   = $_POST['title']; // 下の Note を参照してください
        $this->content = $_POST['content'];
        $this->date    = time();
        $this->db->insert('entries', $this);
    }

    function update_entry()
    {
        $this->title   = $_POST['title'];
        $this->content = $_POST['content'];
        $this->date    = time();

        $this->db->update('entries', $this, array('id' => $_POST['id']));
    }

    /**
    * 任意のプロジェクトIDのquestion_idのみ全て取得する
    * 指定したプロジェクトに属するquestion_idが存在しない場合は、例外を発生
    * @param int $projectId
    * @return array
    */
    public function getSelectedProjectQuestionList($projectId = null)
    {
        try{
            if(is_int($projectId) === true){
                $this->db->where("project_id", $projectId);
                $this->db->where("delete_flag", 0);
                $this->db->order_by("question_id", "asc");
                $res = $this->db->get("question_table");
                $res = $res->result();
                foreach($res as $key => $value){
                    $res[$key]->choice_list = json_decode($res[$key]->choice_list, true);
                }
                return $res;
            }else{
                throw new Exception("指定したプロジェクトIDに設問が登録されていません。");
            }
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 指定したquestion_idの問題情報をquestion_tableから取得する
     * 設問が存在しない場合、例外を発生させる。
     * @param int $questionId
     * @return array
     */
    public function getTargetQuestionData($questionId = null)
    {
        try{
            $questionId = (int)$questionId;
            if(is_int($questionId) === true){
                $this->db->where("question_id", $questionId);
                $res = $this->db->get("question_table");
                $res = $res->result();
                return $res;
            }else{
                throw new Exception("指定した設問IDの情報が存在しません。");
            }
        }catch(Exception $e){
            throw new Exception ($e->getMessage());
        }
    }

    /**
     * 指定したプロジェクトの設問情報を取得
     * @param int $projectId
     * @param int $question_id
     * @return array
     */
    public function getSelectedTargetQuestionData($projectId = null, $questionId = null)
    {
        try{
            $this->db->where("project_id", $projectId);
            $this->db->where("question_id", $questionId);
            $this->db->from("question_table");
            $this->db->join("question_type_table", "question_table.question_type = question_type_table.type_id");
            $res = $this->db->get();
            $res = $res->result();
            if(count($res) === 0){
                throw new Exception("選択したプロジェクトには、現在設問が一問も登録されていません。");
            }
            $res = $res[0];
            $res->choice_number = json_decode($res->choice_number, true);
            $res->choice_list = json_decode($res->choice_list, true);
            shuffle($res->choice_list);
            return $res;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 設問の質問形式を取得
     * @return array
     */
    public function getQuestionTypeList()
    {
        try{
            $this->db->select("*");
            $res = $this->db->get("question_type_table");
            $res = $res -> result();
            if(count($res) > 0){
                return $res;
            }else {
                throw new Exception("設問の質問形式(type)一覧取得に失敗しました。");
            }
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 公開中プロジェクト一覧を取得
     * @param int $publicFlag
     * @return array $res
     */
    public function getOpenProjectList($publicFlag = null)
    {
        $deleteFlag = 0;
        $publicFlag = 1;
        if(is_int($publicFlag) === true){
            $this->db->where("project_public_flag", $publicFlag);
            $this->db->where("delete_flag", $deleteFlag);
            $res = $this->db->get("project_table");
            $res = $res -> result();
            return $res;
        }else{
            //引数がint型以外の場合は空の配列を返す
            return array();
        }
    }

    /**
     * 公開中向けプロジェクト向けメソッド
     * 現在公開中の指定したプロジェクトの[question_id]のみを取得する
     * @param int $projectId
     * @return array
     */
    public function getSelectedProjectQuestionIdList($projectId = null)
    {
        try{
            $deleteFlag = 0;
            $this->db->where("project_id", $projectId);
            $this->db->where("delete_flag", $deleteFlag);
            $this->db->select("question_id");
            $res = $this->db->get("question_table");
            $res = $res->result();
            if(count($res) === 0){
                throw new Exception("選択したプロジェクトには、現在設問が一問も登録されていません。");
            }
            $questionIdList = array();
            foreach($res as $key => $value){
                $questionIdList[] = $value->question_id;
            }
            shuffle($questionIdList);
            return $questionIdList;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
}