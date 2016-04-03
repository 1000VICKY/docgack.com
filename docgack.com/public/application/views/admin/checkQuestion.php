<form action="/admin/completeQuestion" method="post" name="newProjectForm">
    <input type="hidden" name="projectId" value="<?php print($inputData["projectId"]); ?>" />
    <input type="hidden" name="inputData" value="<?php print($serializeData); ?>" />
    <div class="form-group">
        <p class="control-label">▼新規設問文の設問タイプ</p>
        <input type="hidden" name="questionType" value="<?php print($inputData["questionType"]); ?>" />
        <pre><?php print($inputData["questionTypeName"]); ?></pre>
    </div>
    <p>▼新規設問文のタイトル入力</p>
    <pre><?php print(htmlspecialchars($inputData["questionTitle"], ENT_QUOTES)); ?></pre>

    <p>▼新規設問文の説明文入力</p>
    <pre><?php print(htmlspecialchars($inputData["questionText"], ENT_QUOTES)); ?></pre>

    <p>▼新規設問文の回答一覧入力</p>
    <?php
        foreach($inputData["choiceList"] as $key => $value){
    ?>
            <div class="<?php print($inputData["questionTypeAttr"]);?>">
                <label>
                    <input type="<?php print($inputData["questionTypeAttr"]);?>" name="selectAnswer[]" value="<?php print($value["choiceNumber"]); ?>" />
                    <pre><?php print(htmlspecialchars($value["choiceText"], ENT_QUOTES)); ?></pre>
                </label>
            </div>
    <?php
        }
    ?>
    <p>▼新規設問文の解説文の入力</p>
    <pre><?php print(htmlspecialchars($inputData["explanationText"], ENT_QUOTES)); ?></pre>
    <p><input type="submit" name="submitButton" class="btn btn-primary btn-group-justified" value="上記内容で登録をする" /></p>
</form>
