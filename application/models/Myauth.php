<?php
class MyAuth extends CI_Model {

    public function __construct()
    {
        // Model クラスのコンストラクタを呼び出す
        parent::__construct();
        /**
         * configディレクトリ内のファイルを読み込む
         */
        $this->load->library("session");
        $this->config->load("config");
        $this->salt = $this->config->item("salt");
    }

    /**
     * 新規ユーザー登録
     * @param string $userName
     * @param string $userPass
     * @param string $userPassCheck
     * @param string $userMail
     * @return bool
     */
    public function newRegister($userName = null, $userPass = null, $userPassCheck = null, $userMail = null)
    {
        try{
            $insertTime = new DateTime();
            $insertTime = $insertTime ->getTimestamp();
            if(strlen($userName) === 0){
                throw new Exception("ログインIDが入力されていません。");
            }
            if(strlen($userPass) === 0){
                throw new Exception("ログインパスワードが入力されていません。");
            }
            if($userPass !== $userPassCheck){
                throw new Exception("パスワードが一致しません。");
            }
            if(strlen($userMail) === 0){
                throw new Exception ("ユーザーメールが入力されていません。");
            }
            $this->db->where("user_mail", $userMail);
            $this->db->select("*");
            $res = $this->db->get("users");
            $res = $res->result();
            if(count($res) === 1){
                throw new Exception("ユーザー名あるいはメールアドレスが既に登録されています。");
            }else {
                $userPass = md5($this->salt . $userPass);
                $insertData = [
                    "user_name" => $userName,
                    "password" => $userPass,
                    "user_mail" => $userMail,
                    "created_at" => $insertTime,
                ];
                $res = $this->db->insert("users", $insertData);
                return $res;
            }
        }catch(Exception $e){
            throw new Exception ($e->getMessage());
            return false;
        }
    }


    /**
     * 認証処理
     * @param string $userName
     * @param string $userPass
     * @return bool
     */
    public function login($userMail = null, $userPass = null)
    {
        try{
            if(strlen($userMail) === 0 ){
                throw new Exception("ユーザー名が入力されていません。");
            }
            if(strlen($userPass) === 0 ){
                throw new Exception("パスワードが入力されていません。");
            }
            $userPass = md5($this->salt . $userPass);
            $this->db->where("user_mail", $userMail);
            $this->db->where("password", $userPass);
            $res = $this->db->get("users");
            $res = $res->result();
            $this->session->set_userdata("userData", $res);
            if(count($res) === 1){
                return true;
            }else if(count($res) === 0 ){
                throw new Exception("登録されていないユーザー情報です。");
                return false;
            }else{
                throw new Exception("ユーザーデータベース内に重複がみられます。運営者に問い合わせてください。");
                return false;
            }
        }catch(Exception $e){
            throw new Exception ($e->getMessage());
            return false;
        }
    }
}