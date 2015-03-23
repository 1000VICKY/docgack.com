<div class="panel panel-primary">
    <div class="panel-heading">
        プロジェクトの削除確認
    </div>
    <div class="panel-body">
        <p>指定した、プロジェクトを削除しますか？</p>
        <form action="/admin/deleteProject" method="post" id="deleteProjectForm" name="deleteProjectForm">
            <input type="hidden" name="projectId" value="<?php print($projectId); ?>" />
            <p><input type="button" id="submitButton" name="submitButton" class="btn btn-danger btn-group-justified" value="このプロジェクトを削除する" /></p>
        </form>
    </div>
</div>
<script type="text/javascript">
//<!--
    $(function (e){
        var deleteButton = $("#submitButton");
        deleteButton.click(function(e){
            res = confirm("本当に削除しますか？");
            if(res == true){
                $("#deleteProjectForm").submit();
            }
        });
    });
//-->
</script>