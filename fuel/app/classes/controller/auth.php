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
        $this->template->footer = "独学.com Copyright 独学.com All Rights Reserved.";
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
                if(is_numeric($res) === true){
                    \Package::load("email");
                    $mailObj = Email::forge();
                    $mailObj->from("info@docgack.com", "アカウント新規登録");
                    $mailObj->to($input["mail"]);
                    $mailObj->subject("アカウント新規追加が完了しました。");
                    $mailObj->body("独学.com 新規アカウント完了致しました。" . PHP_EOL . "あなたのアカウント名は{$input["username"]}です。");
                    $mailObj->send();
                    Response::redirect("/");
                    exit();
                } else {
                    throw new Exception("アカウントの新規追加に失敗しました。");
                }
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

    //アカウントまたはパスワードを忘れた場合
    public function action_forget()
    {
        try{
            $data = array();
            $this->template->title = "認証処理/新規アカウント再発行ページ";
            $this->template->content = View::forge("auth/forget", $data);
        }catch(Exception $e){
            $data["errorMessage"] = $e->getMessage();
            $this->template->title = "アカウント再発行/エラー発生中。";
            $this->template->content = View::forge("error/index", $data);
        }
    }
    public function action_reissue()
    {
        try{
            \Package::load("MyAuth");
            $mailAddress = Input::post("mail");
            $res = MyAuth::validate_user($mailAddress);
            var_dump($res);
            exit();
        }catch(Exception $e){
            $data["errorMessage"] = $e->getMessage();
            $this->template->title = "アカウント再発行/エラー発生中。";
            $this->template->content = View::forge("error/index", $data);
        }

    }
}

