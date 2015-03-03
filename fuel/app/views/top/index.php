    <div class="panel-body">
        <h3 class="resetClass">問題集を登録して、いつでもどこでも資格勉強ができる!</h3>
        <p class="resetClass">アカウント登録を行って、問題を登録しましょう。
        あとは、ランダムで出題される問題文をランダム順で表示される選択肢の中から
        解答を選択するだけ！反復解答で問題を解くことができます。</p>
    </div>
    <div id="loginForm">
        <form action="/auth/login" method="post" name="loginForm">
            <input type="hidden" class="form-control input-lg btn-group-justified" name="token" value="<?php echo($dummyToken); ?>" />
            <p>ユーザーIDの入力</p>
            <p><input type="text" class="form-control input-lg btn-group-justified" name="userName" /></p>
            <p>パスワードの入力</p>
            <p><input type="password" class="form-control input-lg btn-group-justified" name="userPass"/></p>
            <p><input type="submit" name="submitButton" class="btn btn-primary btn-group-justified" value="ログイン" /></p>
        </form>
        <p><a href="/auth/new" class="btn btn-default btn-group-justified">新規アカウント登録へ</a></p>
        <!--<p><a href="/auth/forget" class="btn btn-default btn-group-justified">アカウント紛失の場合</a></p>-->
    </div><!-- /#loginForm -->