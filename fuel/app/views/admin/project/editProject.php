<a href="/admin/newQuestion/<?php print($projectData[0]["project_id"]); ?>" class="btn btn-primary btn-group-justified projectBox">Project No.<?php print($projectData[0]["project_id"]); ?>の新規問題追加</a>
<a href="/admin/editQuestion/<?php print($projectData[0]["project_id"]); ?>" class="btn btn-primary btn-group-justified projectBox">Project No.<?php print($projectData[0]["project_id"]); ?>の既存問題編集</a>
<div id="">
    <div>
        <form action="/admin/updateProject" method="post" name="newProjectForm">
            <input type="hidden" name="projectId" value="<?php print($projectData[0]["project_id"]); ?>" />
            <p>▼既存プロジェクト名の編集</p>
            <p><textarea class="form-control input-lg" name="projectName" rows="3"><?php print($projectData[0]["project_name"]); ?></textarea></p>
            <div class="form-group">
              <p class="control-label">▼既存プロジェクトの公開レベル</p>
              <div class="radio">
                <label for="private">
                    <input type="radio" value="0" name="projectPublicLevel" id="private" <?php print($privateCheck); ?> ><pre>非公開</pre>
                </label>
              </div>
              <div class="radio">
                <label for="public">
                    <input type="radio" value="1" name="projectPublicLevel" id="public" <?php print($publicCheck); ?> ><pre>公開</pre>
                </label>
              </div>
            </div>
            <p><input type="submit" name="submitButton" class="btn btn-primary btn-group-justified" value="上記の内容でプロジェクト編集" /></p>
        </form>
    </div>
</div>