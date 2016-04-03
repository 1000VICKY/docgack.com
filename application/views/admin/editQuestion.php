<?php foreach($questionList as $key => $value) { ?>
    <div class="panel panel-primary" id="<?php print($value->question_id); ?>">
        <div class="panel-heading">「設問No:<?php print($value->question_id); ?>」<?php print($value->question_title); ?></div>
        <div class="panel-body">
            <div class="panel-body alert alert-warning alert-dismissible">
                <pre class="resetClass"><?php print(htmlspecialchars($value->question_text, ENT_QUOTES)); ?></pre>
            </div>
            <p>以下、選択肢</p>
            <?php
                foreach($value->choice_list as $tempK => $tempV){
                    print("<pre>");print(htmlspecialchars($tempV["choiceText"], ENT_QUOTES));print("</pre>");
                }
            ?>
            <p>以下、解説</p>
            <div class="panel-body alert alert-warning alert-dismissible">
                <pre class="resetClass"><?php print(htmlspecialchars($value->explanation_text,ENT_QUOTES)); ?></pre>
            </div>
            <form action="/admin/modifyQuestion/<?php print($projectId); ?>/<?php print($value->question_id); ?>" name="" method="get">
                <input type="submit" class="btn btn-default btn-group-justified projectBox" value="この設問を修正する" />
            </form>
            <p><a href="/admin/deleteQuestion/<?php print($projectId); ?>/<?php print($value->question_id); ?>">▼この設問を削除する▼</a></p>
        </div>
    </div>
<?php } ?>