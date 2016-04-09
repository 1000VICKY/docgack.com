<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * WebAPI用コントローラー
 */
class Api extends CI_Controller {
    /**
     * コンストラクタ
     */
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
    /**
     * ログインしてきたユーザーが登録しているプロジェクト/設問の組み合わせ一覧を取得する
     * @return string
     */
    public function getAllDataToJson()
    {
        try{
            ob_start();
            print_r($this->input->post());
            print_r(json_decode($this->input->post(), true);
            $output = ob_get_clean();
            $this->output->set_content_type("text/plain");
            $this->output->set_output($output);
        }catch(Exception $e){
            print($e->getMessage());
        }
    }
    /**
     * 現在のdocgack.com稼働中DBのバックアップをサーバー上へ同期的に保存する
     */
    public function backupDB($userName = null, $password = null, $dbName = null, $backupFileName = null)
    {
        try{
            ob_start();
            print_r($this->input->post());
            print_r(json_decode($this->input->post() ,true));
            $output = ob_get_clean();
            $userName = "root";
            $password = "akisen10574318";
            $dbName ="exam_system";
            $timeObj = new DateTime();
            $dateTime = date_format($timeObj, "Y-m-d");
            $backupFileName = "{$dateTime}.sql";
            $backupDir = $this->input->server("DOCUMENT_ROOT") . "/{$backupFileName}";
            //サーバー側へ投げたいバックグランド処理
            $command = "/usr/bin/mysqldump -u {$userName} -p{$password} {$dbName} > {$backupDir}";
            $res = system($command);
            if(strlen($res) === 0){
            $this->output->set_content_type("text/plain");
                $this->output->set_output($output);
            }else{
                throw new Exception("DBのバックアップに失敗");
            }
        }catch(Exception $e){
            print($e->getMessage());
        }
    }
}
