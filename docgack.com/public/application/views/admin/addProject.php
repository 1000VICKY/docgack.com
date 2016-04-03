<div class="panel panel-primary">
    <div class="panel-heading">
        新規プロジェクトの作成
    </div>
    <div class="panel-body">
        <form action="/admin/insertProject" method="post" name="newProjectForm">
            <p>▼新規プロジェクト名の入力</p>
            <p><textarea class="testTextArea form-control input-lg" name="projectName" rows="3"></textarea></p>
            <div class="form-group">
              <p class="control-label">▼新規プロジェクトの公開レベル</p>
              <div class="radio">
                <label for="private">
                    <input type="radio" value="2" name="projectPublicFlag" id="private"  checked="checked" ><pre>非公開</pre>
                </label>
              </div>
              <div class="radio">
                <label for="public">
                    <input type="radio" value="1" name="projectPublicFlag" id="public"  ><pre>公開</pre>
                </label>
              </div>
            </div>
            <p><input type="submit" name="submitButton" class="btn btn-primary btn-group-justified" value="上記の内容でプロジェクト作成" /></p>
        </form>
    </div>
</div>