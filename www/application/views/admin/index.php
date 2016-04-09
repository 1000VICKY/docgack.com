<div class="panel panel-primary projectBox">
    <div class="panel-heading">プロジェクト一覧/問題一覧へ</div>
    <div class="panel-body">
        <?php foreach($projectList as $key =>$value){ ?>
        <p><a class="toBlock" href="/admin/oneQuestion/<?php print($value->project_id); ?>" >■<?php print(htmlspecialchars($value->project_name, ENT_QUOTES)); ?></a></p>
        <?php } ?>
    </div>
</div>
<div class="panel panel-primary projectBox">
    <div class="panel-heading">プロジェクト編集/編集画面へ</div>
    <div class="panel-body">
        <?php foreach($projectList as $key =>$value){ ?>
        <p ><a class="toBlock" href="/admin/editProject/<?php print($value->project_id); ?>" >■<?php print(htmlspecialchars($value->project_name, ENT_QUOTES)); ?></a></p>
        <?php } ?>
    </div>
</div>
<p><a href="/admin/addProject" class="btn btn-primary btn-group-justified">プロジェクトの新規作成</a></p>
<p ng-controller="BackupController"><a ng-click="backupDB()" class="btn btn-warning btn-group-justified">現在のDBをバックアップ</a></p>
