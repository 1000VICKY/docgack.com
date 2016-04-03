<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * ドメインルート直下にアクセスされた場合に実行されるクラス
 */
class Auth extends CI_Controller {

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
        $this->load->library("Clean");
        $this->load->helper("url");
        $this->load->model("Project");
        $this->load->model("Myauth");
        $this->data= [];
        $this->data["mainHeader"] = "独学.com";
        $this->data["title"] = "独学.com";
        $this->data["footer"] = "独学.com Copyright 独学.com All Rights Reserved.";
        $this->data["https"] = $this->input->server("HTTPS");
    }
    /**
     * 現在公開中の問題集(プロジェクト内容)
     * @param int $projectId
     * @param int $page
     * @return void
     */
    public function index($projectId = null, $page = null)
    {
        try{
            $this->data["errorMessage"] = "指定したURLのページが存在しません。";
            $this->output->set_header("HTTP/1.1 400 Not Found");
            //viewの設定
            $this->load->view('common/header', $this->data);
            $this->load->view('error/index', $this->data);
            $this->load->view('common/footer', $this->data);
        }catch(Exception $e){
            //viewの設定
            $this->output->set_header("HTTP/1.1 400 Not Found");
            $this->load->view('common/header', $this->data);
            $this->load->view('error/index', $this->data);
            $this->load->view('common/footer', $this->data);
        }
    }
    /**
     * システムの新規登録
     * @return bool
     */
    public function newRegister()
    {
        try{
            //変数の初期化
            $userName = "";
            $userMail = "";
            $userPass = "";
            $userPassCheck = "";
            $httpMethod = $this->input->server("REQUEST_METHOD");

            if($httpMethod === "POST")
            {
                /**
                 * POSTデータを変数化
                 * および入力データのサニタイズ処理
                 */
                $inputData = $this->input->post();
                foreach($inputData as $key => $value){
                    $inputData[$key] = Clean::cleaning($value);
                }
                //ユーザー名チェック
                if( (array_key_exists("userName", $inputData) === true) && (strlen($inputData["userName"]) > 0) ){
                    $userName = $inputData["userName"];
                }else {
                    throw new Exception("ユーザー名が未入力です。");
                }
                //ユーザーメールチェック
                if( (array_key_exists("userMail", $inputData) === true) && (strlen($inputData["userMail"]) > 0) ){
                    $userMail = $inputData["userMail"];
                }else {
                    throw new Exception("メールアドレスが未入力です。");
                }
                //パスワード入力チェック
                if( (array_key_exists("userPass", $inputData) === true) && (strlen($inputData["userPass"]) > 0) ){
                    $userPass = $inputData["userPass"];
                }else {
                    throw new Exception("パスワードが未入力です。");
                }
                //確認用パスワード入力チェック
                if( (array_key_exists("userPassCheck", $inputData) === true) && (strlen($inputData["userPassCheck"]) > 0) ){
                    $userPassCheck = $inputData["userPassCheck"];
                }else {
                    throw new Exception("チェック用パスワードが未入力です。");
                }
                if($userPass === $userPassCheck)
                {
                    $res = $this->Myauth->newRegister($userName, $userPass, $userPassCheck, $userMail);
                    $this->load->view('common/header', $this->data);
                    $this->load->view('auth/newRegister', $this->data);
                    $this->load->view('common/footer', $this->data);
                }else {
                    throw new Exception("入力したパスワードが一致しません。");
                }
            }else if($httpMethod === "GET"){
                //viewの設定
                $this->load->view("common/header", $this->data);
                $this->load->view("auth/newRegister", $this->data);
                $this->load->view("common/footer", $this->data);
            }else{
                throw new Exception("不正なアクセスです。");
                return false;
            }
        }catch(Exception $e){
            $this->data["errorMessage"] = $e->getMessage();
            $this->load->view("common/header", $this->data);
            $this->load->view("error/index", $this->data);
            $this->load->view("common/footer", $this->data);
        }
    }
    /**
     * 本システムのログイン処理機能
     */
    public function login()
    {
        try{
            $postData = $this->input->post();
            if(array_key_exists("userMail", $postData) === true){
                $userMail = $postData["userMail"];
            }else{
                throw new Exception ("メールアドレスが入力されていません。");
            }
            if(array_key_exists("userPass", $postData) === true){
                $userPass = $postData["userPass"];
            }else {
                throw new Exception("パスワードが入力されていません。");
            }
            $token = $postData["token"];
            $res = $this->Myauth->login($userMail, $userPass);
            if($res === true){
                //認証成功
                $domain = $this->input->server("SERVER_NAME");
                $redirectUrl = "https://" . $domain;
                redirect("{$redirectUrl}/admin/index");
                exit;
            }else{
                throw new Exception("認証失敗");
            }
        }catch(Exception $e){
            $this->data["errorMessage"] = $e->getMessage();
            $this->load->view("common/header", $this->data);
            $this->load->view("error/index", $this->data);
            $this->load->view("common/footer", $this->data);
        }
    }
}