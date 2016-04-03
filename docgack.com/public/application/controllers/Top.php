<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * ドメインルート直下にアクセスされた場合に実行されるクラス
 */
class Top extends CI_Controller {
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent :: __construct();
        //プロジェクトモデルの読み込み
        $this->load->database();
        $this->load->library("session");
        $this->load->model('Project');
        $this->load->helper("url");
        $this->data = [];
        $this->data["mainHeader"] = "独学.com";
        $this->data["title"] = "独学.com";
        $this->data["footer"] = "独学.com Copyright 独学.com All Rights Reserved.";
        $this->data["https"] = $this->input->server("HTTPS");
    }

    /**
     * トップページ(ログイン)
     * @return void
     */
    public function index()
    {
        try{
            /**
             * 現在公開中のプロジェクト一覧を取得する
             * プロジェクトの公開フラグ=>1
             * プロジェクトの非公開フラグ=>2
             */
            $publicFlag = 0;
            $res = $this->Project->getOpenProjectList($publicFlag);
            $this->data["projectList"] = $res;
            $this->data["dummyToken"] = "";
            $this->load->view("common/header", $this->data);
            $this->load->view("top/index", $this->data);
            $this->load->view("common/footer", $this->data);
        }catch(Exception $e){
            $this->data["errorMessage"] = $e->getMessage();
            $this->load->view("common/header", $this->data);
            $this->load->view("error/index", $this->data);
            $this->load->view("common/footer", $this->data);
        }
    }
}
