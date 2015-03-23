<?php
use \Model\Clean;
use \Model\QuestionFormat;
class Controller_Top extends Controller_Template
{
    public $template = "template";
    public function before()
    {
        parent::before();
        $this->template->mainStyle = "main.css";
        $this->template->mainJs = "jquery.php";
        $this->template->footer = "独学.com Copyright 独学.com All Rights Reserved.";
    }
    public function action_index()
    {
        $data = array();
        //公開中の資格問題集を表示する
        $publicFlag = 1;
        $deleteFlag = 0;
        $projectList = Model_Project::find("all",
            array("where" =>
                array(
                    "project_public_flag" => $publicFlag,
                    "delete_flag" => $deleteFlag,
                )
            )
        );
        $res = Model_Project::toArray($projectList);
        $data["projectList"] = $res;
        $dateObj = new DateTime("now");
        $data["dummyToken"] = md5($dateObj->getTimestamp());
        $this->template->title = "";
        $this->template->content = View::forge('top/index', $data);
    }
}
