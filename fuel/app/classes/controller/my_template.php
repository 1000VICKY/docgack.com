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
        $this->template->mainHeader = "独学.com";
        $this->template->subHeader = "独学用学習システム/管理画面";
        $this->template->mainStyle = "main.css";
        $this->template->mainJs = "jquery.php";
        $this->template->footer = "";
        $this->dateObj = new DateTime("now", new DateTimezone("Asia/Tokyo"));
        Session::set("referrer", Input::server("request_uri"));
    }
    public function after($response)
    {
        return parent::after($response);
    }
}
