<div class="panel panel-info projectBox">
    <div class="panel-heading">プロジェクト一覧/問題一覧へ</div>
    <div class="panel-body">
        <?php
        foreach($projectList as $key =>$value){
        ?>
        <!--<p><a class="toBlock" href="/admin/viewQuestion/<?php print($value["project_id"]); ?>" >■<?php print($value["project_name"]); ?>(一括)</a></p>-->
        <p><a class="toBlock" href="/admin/oneQuestion/<?php print($value["project_id"]); ?>" >■<?php print($value["project_name"]); ?></a></p>
        <?php } ?>
    </div>
</div>
<div class="panel panel-info projectBox">
    <div class="panel-heading">プロジェクト編集/編集画面へ</div>
    <div class="panel-body">
        <?php
        foreach($projectList as $key =>$value){
        ?>
        <p ><a class="toBlock" href="/admin/editProject/<?php print($value["project_id"]); ?>" >■<?php print($value["project_name"]); ?></a></p>
        <?php } ?>
    </div>
</div>
<a href="/admin/newProject" class="btn btn-primary btn-group-justified">プロジェクトの新規作成</a>