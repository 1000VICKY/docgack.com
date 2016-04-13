<form action="/admin/modifyCheckQuestion" method="post" name="newProjectForm">
    <input type="hidden" name="projectId" value="<?php print($projectId); ?>" />
    <input type="hidden" name="questionId" value="<?php print($questionId); ?>" />
    <input type="hidden" name="nowAnswer" value="<?php print($nowAnswer); ?>" />
    <div class="form-group">
        <p class="control-label">▼新規設問文の設問タイプ</p>
        <?php
            foreach($questionTypeList as $key => $value){
                $checked = (string)"";
                if((int)$questionData[0]->question_type === (int)$value->type_id){
                    $checked = "checked=\"checked\"";
                }
        ?>
        <div class=" radio-inline">
            <input type="radio" value="<?php print($value->type_id); ?>" name="questionType" <?php print($checked); ?> id="type_id_<?php print($value->type_id); ?>">
            <label for="type_id_<?php print($value->type_id); ?>"><pre><?php print($value->type_name); ?></pre></label>
        </div>
        <?php } ?>
    </div>
    <p>▼新規設問文のタイトル入力</p>
    <p><input type="text" class="testTextArea form-control input-lg" name="questionTitle" value="<?php print($questionData[0]->question_title); ?>" /></p>

    <p>▼新規設問文の説明文入力</p>
    <p><textarea class="testTextArea form-control input-lg" name="questionText" rows="10"><?php print($questionData[0]->question_text); ?></textarea></p>

    <p>▼新規設問文の選択肢一覧入力(※空欄は無視されます。)</p>
    <?php for($i = 0 ; $i < $defaultSelectCount; $i++){
            if(in_array($i, $questionData[0]->choice_number)){
                $nowNumber = "btn-primary";
            }else {
                $nowNumber = "";
            }
    ?>
        <p>
            <input type="hidden" value="<?php print($i); ?>" />
            <textarea class="testTextArea form-control input-lg <?php print($nowNumber); ?>" name="choiceList[]" rows="5"><?php print($questionData[0]->choice_list[$i]["choiceText"]); ?></textarea>
        </p>
    <?php } ?>
    <p><a href="/admin/modifyQuestion/<?php print($projectId); ?>/<?php print($questionId); ?>/up">▲選択肢を増やす</a></p>
    <p><a href="/admin/modifyQuestion/<?php print($projectId); ?>/<?php print($questionId); ?>/down">▼選択肢を減らす</a>

    <p>▼新規設問文の解説文入力</p>
    <p><textarea class="form-control input-lg" name="explanationText" rows="10"><?php print($questionData[0]->explanation_text); ?></textarea></p>
    <p><input type="submit" name="submitButton" class="btn btn-primary btn-group-justified" value="上記の内容で既存設問文を更新する" /></p>
</form>
<script type="text/javascript">
$(function(){
    $(window).on('beforeunload', function() {
        return "このページを離れると、入力したデータが削除されます。修正の場合には、「修正ボタン」をクリックしてください。";
    });
});
</script>