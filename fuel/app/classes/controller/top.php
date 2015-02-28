<?php

class Controller_Top extends Controller_Template
{
    public $template = "template";
    public function before()
    {
        parent::before();
        $this->template->mainStyle = "main.css";
        $this->template->mainJs = "jquery.php";
        $this->template->footer = "";
    }
    public function action_index()
    {
        $data = array();
        $dateObj = new DateTime("now");
        $data["dummyToken"] = md5($dateObj->getTimestamp());
        $this->template->title = 'トップページ/ログインページ';
        $this->template->content = View::forge('top/index', $data);
    }
}
