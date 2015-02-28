<?php

class Controller_Auth extends Controller_Template
{
    public $template = "template";
    public function before()
    {
        parent::before();
        $this->template->mainStyle = "main.css";
        $this->template->mainJs = "jquery.php";
        $this->template->footer = "";
        $this->template->title = "認証処理/イニシャライズ";
    }
    /**
     * /auth/indexへのアクセスは
     * 強制的に/indexへとリダイレクトさせる。
     */
    public function action_index()
    {
        return Response::redirect("/top/index");
        exit();
    }
    /**
     * アカウント登録ページ
     * @return void
     */
    public function action_new()
    {
        $data = array();
        if(Input::method() === "POST"){
            try{
                $input = Input::post();
                $res = Auth::create_user($input["username"], $input["password"], $input["mail"]);
                Response::redirect("/");
                exit();
                $this->template->title = "認証処理/新規アカウント登録完了！";
                $this->template->content = View::forge("auth/complete", $data);
            }catch(Exception $e){
                $data["errorMessage"] = $e->getMessage();
                $this->template->title = "認証処理/エラー発生!";
                $this->template->content = View::forge("error/index", $data);
            }
        }else{
            $data = array();
            $this->template->title = "認証処理/新規アカウント登録ページ";
            $this->template->content = View::forge("auth/new", $data);
        }
    }
    public function action_login()
    {
        try{
            $data = array();
            $input = Input::post();
            //ログイン処理
            $res = Auth::login($input["userName"], $input["userPass"]);
            if($res === true){
                /**
                 * ユーザーのログイン情報を
                 * セッションに保存する。
                 */
                $userId = Auth::get_user_id();
                $userMail = Auth::get_email();
                Session::set("userId", $userId[1]);
                Session::set("userMail", $userMail);
                Response::redirect("/admin/index");
                exit();
            }
            $dateObj = new DateTime();
            $data["dummyToken"] = md5($dateObj->getTimestamp());
            $this->template->title = "認証処理/ログイン処理に失敗しました。";
            $this->template->content = View::forge("top/index", $data);
        }catch(Exception $e){
            $data["errorMessage"] = $e->getMessage();
            $this->template->title = "認証処理/エラー発生中。";
            $this->template->content = View::forge("error/index", $data);
        }
    }
    public function action_logout()
    {
        return Response::forge(Presenter::forge("top/404"), 404);
    }
}

