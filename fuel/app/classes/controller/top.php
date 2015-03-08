<?php

class Controller_Top extends Controller_Template
{
    public $template = "template";
    public function before()
    {
        parent::before();
        $this->template->mainStyle = "main.css";
        $this->template->mainJs = "jquery.php";
        $this->template->footer = "独学.com Copyright 1000_VICKY All Rights Reserved.";
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
