<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="resetClass">独学.comへログイン</h3>
    </div>
    <div class="panel-body">
        <div id="loginForm">
            <form action="/auth/login" method="post" name="loginForm">
                <input type="hidden" class="form-control input-lg btn-group-justified" name="token" value="<?php echo($dummyToken); ?>" />
                <p>メールアドレスの入力</p>
                <p><input type="text" class="form-control input-lg btn-group-justified" name="userMail" /></p>
                <p>パスワードの入力</p>
                <p><input type="password" class="form-control input-lg btn-group-justified" name="userPass"/></p>
                <?php if($https !== "on"){ ?>
                <p><a href="https://docgack.com/" class="btn btn-primary btn-group-justified">安全な接続に切り替える</a></p>
                <?php } ?>
                <p><input type="submit" name="submitButton" class="btn btn-primary btn-group-justified input-lg" value="ログイン" /></p>
            </form>
            <p><a href="/auth/newRegister" class="btn btn-default btn-group-justified">新規アカウント登録へ</a></p>
            <!--<p><a href="/auth/forget" class="btn btn-default btn-group-justified">アカウント紛失の場合</a></p>-->
        </div><!-- /#loginForm -->
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="resetClass">独学.comとは？問題集を登録して、いつでもどこでも資格勉強ができる!</h3>
    </div>
    <div class="panel-body">
        <div class="panel-body alert alert-warning alert-dismissible">
            <p class="resetClass">アカウント登録を行って、問題を登録しましょう。
            あとは、ランダムで出題される問題文をランダム順で表示される選択肢の中から
            解答を選択するだけ！反復解答で問題を解くことができます。</p>
        </div>
    </div>

<!-- ▼▼▼公開中の資格問題集一覧▼▼▼ -->
    <div class="panel-body">
        <h4>現在公開中のプロジェクト一覧</h4>
    </div>
    <div class="panel-body">
        <?php
        foreach($projectList as $key =>$value){
        ?>
        <h5><a class="toBlock" href="/open/index/<?php print($value->project_id); ?>" >■<?php print(htmlspecialchars($value -> project_name,ENT_QUOTES)); ?></a></h5>
        <?php } ?>
    </div>
</div>