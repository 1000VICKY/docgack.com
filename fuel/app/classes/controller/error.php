<?php

class Controller_Error extends Controller_Template
{
    public $template = "template";
    public function before()
    {
        parent::before();
        $this->template->mainStyle = "main.css";
        $this->template->mainJs = "jquery.php";
        $this->template->footer = "";
        $this->template->title = '404エラー';
    }
    public function action_404()
    {
        $data = array();
        $data["errorMessage"] = "指定したページが見つかりません。";
        $this->template->content = View::forge("error/index", $data);
    }
}

