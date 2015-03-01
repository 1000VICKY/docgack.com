<div id="loginForm">
    <div>
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
    </div>
</div>