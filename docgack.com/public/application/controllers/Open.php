<?php


defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * ドメインルート直下にアクセスされた場合に実行されるクラス
 */
class Open extends CI_Controller {

    public function __construct()
    {
        parent :: __construct();
        /**
         * コンストラクタの事前実行処理
         * データベースのロード
         * セッションのロード
         * Projectモデルのロード
         * ヘルパーのロード
         */
        $this->load->database();
        $this->load->library("session");
        $this->load->helper('url');
        $this->load->model('Project');
        $this->data= [];
        $this->data["mainHeader"] = "独学.com";
        $this->data["title"] = "独学.com";
        $this->data["footer"] = "独学.com Copyright 独学.com All Rights Reserved.";
        $this->data["https"] = $this->input->server("HTTPS");
    }
    /**
     * 現在公開中の問題集(プロジェクト内容)
     * @return void
     */
    public function index($projectId = null, $page = null)
    {
        try{
            /**
             * ページング機能の実装
             * 初回トップページに来た時はセッションにquestion_idリストを保持する
             */
            $projectId = (int)$projectId;
            $questionIdList = $this->Project->getSelectedProjectQuestionIdList($projectId);
            $page = (int)$page;
            $sessionData = $this->session->all_userdata();
            if($page === 0){
                $this->session->set_userdata("questionIdList", $questionIdList);
                $sessionData = $this->session->all_userdata();
                $page = 0;
            }else{
                if(array_key_exists("questionIdList", $sessionData) === false){
                   //この場合、設問問題のトップへリダイレクト
                    redirect("/open/index/{$projectId}/");
                    exit();
                }
                $sessionData = $this->session->get_userdata("questionIdList", $questionIdList);
            }
            $questionIdList = $sessionData["questionIdList"];
            //該当するプロジェクトIDの設問IDの設問内容を取得
            $res = $this->Project->getSelectedTargetQuestionData($projectId, $questionIdList[$page]);
            $this->session->set_userdata("referer", "/admin/oneQuestion/{$projectId}/{$page}");
            $questionIdList = $this->session->userdata("questionIdList");
            $nowNumber = $questionIdList[$page];

            //viewに引き渡す変数
            $this->data["questionList"] = $res;
            $this->data["page"] = $page;
            $this->data["nowNumber"] = $nowNumber;
            $this->data["questionIdList"] = $questionIdList;
            $this->data["projectId"] = $projectId;

            //viewの設定
            $this->load->view('common/header', $this->data);
            $this->load->view('open/index', $this->data);
            $this->load->view('common/footer', $this->data);
        }catch(Exception $e){
            //viewの設定
            $this->data["errorMessage"] = $e->getMessage();
            $this->load->view('common/header', $this->data);
            $this->load->view('error/index', $this->data);
            $this->load->view('common/footer', $this->data);
        }
    }
}