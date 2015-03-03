<div>
<?php foreach($questionList as $key => $value) { ?>
    <div class="panel panel-primary">
        <div class="panel-heading">「設問No:<?php print($value["question_id"]); ?>」<?php print($value["question_title"]); ?></div>
        <div class="panel-body">
            <div class="panel-body alert alert-warning alert-dismissible">
                <pre class="resetClass"><?php print($value["question_text"]); ?></pre>
            </div>
        </div>
        <div class="panel-body">
            <form action="/admin/modifyQuestion/<?php print($projectId); ?>/<?php print($value["question_id"]); ?>" name="" method="post">
                <input type="submit" class="btn btn-default btn-group-justified projectBox" value="この設問を修正する" />
            </form>
            <p><a href="/admin/deleteQuestion/<?php print($projectId); ?>/<?php print($value["question_id"]); ?>">▼この設問を削除する▼</a></p>
        </div>
    </div>
<?php } ?>
</div>