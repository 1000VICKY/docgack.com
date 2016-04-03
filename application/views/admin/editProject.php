<div class="panel panel-primary">
    <div class="panel-heading">
        Project No.<?php print($projectData[0]->project_id); ?> プロジェクトの編集
    </div>
    <div class="panel-body">
        <a href="/admin/addQuestion/<?php print($projectData[0]->project_id); ?>" class="btn btn-primary btn-group-justified projectBox">新規設問追加</a>
        <a href="/admin/editQuestion/<?php print($projectData[0]->project_id); ?>" class="btn btn-primary btn-group-justified projectBox">既存設問編集</a>
        <form action="/admin/updateProject" method="post" name="newProjectForm">
            <input type="hidden" name="projectId" value="<?php print($projectData[0]->project_id); ?>" />
            <p>▼既存プロジェクト名の編集</p>
            <p><textarea class="testTextArea form-control input-lg" name="projectName" rows="3"><?php print($projectData[0]->project_name); ?></textarea></p>
            <div class="form-group">
              <p class="control-label">▼既存プロジェクトの公開レベル</p>
              <div class="radio">
                <label for="private">
                    <input type="radio" value="2" name="projectPublicFlag" id="private" <?php print($projectData[0]->private_check); ?> ><pre>非公開</pre>
                </label>
              </div>
              <div class="radio">
                <label for="public">
                    <input type="radio" value="1" name="projectPublicFlag" id="public" <?php print($projectData[0]->public_check); ?> ><pre>公開</pre>
                </label>
              </div>
            </div>
            <p><input type="submit" name="submitButton" class="btn btn-primary btn-group-justified" value="上記の内容でプロジェクト更新" /></p>
        </form>
        <p><a href="/admin/deleteProject/<?php print($projectData[0]->project_id); ?>">▼このプロジェクトを削除する▼</a></p>
    </div>
</div>