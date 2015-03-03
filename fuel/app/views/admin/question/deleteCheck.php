<div id="">
    <div>
        <p>指定した、設問を削除しますか？</p>
        <form action="/admin/deleteQuestion" method="post" name="newProjectForm">
            <input type="hidden" name="projectId" value="<?php print($projectId); ?>" />
            <input type="hidden" name="questionId" value="<?php print($questionId); ?>" />
            <p><input type="submit" name="submitButton" class="btn btn-danger btn-group-justified" value="この設問を削除する" /></p>
        </form>
    </div>
</div>