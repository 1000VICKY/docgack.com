<form action="/admin/modifyCompleteQuestion" method="post" name="newProjectForm">
    <input type="hidden" name="projectId" value="<?php print($projectId); ?>" />
    <input type="hidden" name="questionId" value="<?php print($questionId); ?>" />
    <input type="hidden" name="inputData" value="<?php print($serializeData); ?>" />
    <div class="form-group">
        <p class="control-label">▼新規設問文の設問タイプ</p>
        <?php
            foreach( $questionTypeList as $key => $value){
                if((int)$value->type_id === (int)$questionType){
        ?>
            <input type="hidden" name="questionType" value="<?php print($questionType); ?>" />
            <pre><?php print(htmlspecialchars($value->type_name, ENT_QUOTES)); ?></pre>
        <?php
                }
            }
        ?>
    </div>
    <p>▼新規設問文のタイトル入力</p>
    <pre><?php print($questionTitle); ?></pre>

    <p>▼新規設問文の説明文入力</p>
    <pre><?php print(htmlspecialchars($questionText, ENT_QUOTES)); ?></pre>

    <p>▼新規設問文の回答一覧入力</p>
    <?php foreach($choiceList as $key => $value){
            $checked = (string)"";
            if(in_array($value["choiceNumber"], $nowAnswer) === true){
                $checked = 'checked="checked"';
            }
    ?>
    <div class="<?php print($typeAttr); ?>">
        <label>
            <input type="<?php print($typeAttr); ?>" name="selectAnswer[]" value="<?php print($value["choiceNumber"]); ?>" <?php print($checked); ?> />
            <pre><?php print(htmlspecialchars($value["choiceText"], ENT_QUOTES)); ?></pre>
        </label>
    </div>
    <?php } ?>
    <p>▼新規設問文の解説文の入力</p>
    <pre><?php print(htmlspecialchars($explanationText, ENT_QUOTES)); ?></pre>
    <p><input type="submit" name="submitButton" class="btn btn-primary btn-group-justified" value="上記内容で更新をする" /></p>
</form>