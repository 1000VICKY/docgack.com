<?php
/**
 * テンプレートクラスの拡張クラス
 */
abstract class Controller_Template extends \Fuel\Core\Controller_Template
{
    public $userId = "";
    public $template = 'template';
    public $dateObj = "";
    public function before()
    {
        parent::before();
        //アクセスしたURLがwwwのサブドメインならリダイレクト
        if(preg_match("/^www\./", Input::server("HTTP_HOST")) === 1){
            Response::redirect("http://docgack.com");
            exit();
        }
        //SSL通信ではない場合HTTPSにリダイレクトさせる。
        if(array_key_exists("HTTPS", Input::server()) === false){
            Response::redirect("https://docgack.com");
            exit();
        }
        header("Content-type: text/html;charset=utf-8");
        $this->template->mainHeader = "独学.com";
        $this->template->subHeader = "独学用学習システム/管理画面";
        $this->template->mainStyle = "main.css";
        $this->template->mainJs = "jquery.php";
        $this->template->title = "独学.com/ユーザーページ/初期ページ";
        $this->template->footer = "Copyright 独学.com|docgack.com All Rights Reserved.";
        $this->dateObj = new DateTime("now", new DateTimezone("Asia/Tokyo"));
        Session::set("referrer", Input::server("request_uri"));
    }
    public function after($response)
    {
        return parent::after($response);
    }
}
