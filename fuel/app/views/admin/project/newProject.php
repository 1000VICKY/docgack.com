<div id="">
    <div>
        <form action="/admin/addProject" method="post" name="newProjectForm">
            <p>▼新規プロジェクト名の入力</p>
            <p><textarea class="form-control input-lg" name="projectName" rows="3"></textarea></p>
            <div class="form-group">
                <p class="control-label">▼新規プロジェクトの公開レベル</p>
                <div class="radio-inline">
                    <label for="private">
                        <input type="radio" value="0" name="projectPublicLevel" id="private" ><pre>非公開</pre>
                    </label>
                  </div>
                  <div class="radio-inline">
                    <label for="public">
                        <input type="radio" value="1" name="projectPublicLevel" id="public" ><pre>公開</pre>
                    </label>
                </div>
            </div>
            <p><input type="submit" name="submitButton" class="btn btn-primary btn-group-justified" value="上記の内容で新規プロジェクト作成" /></p>
        </form>
    </div>
</div>