<div id="">
    <div>
        <form action="/admin/modifyCompleteQuestion" method="post" name="newProjectForm">
            <input type="hidden" name="projectId" value="<?php print($projectId); ?>" />
            <input type="hidden" name="inputData" value="<?php print($serializeData); ?>" />
            <div class="form-group">
                <p class="control-label">▼新規設問文の設問タイプ</p>
                <?php
                    foreach( $questionTypeList as $key => $value){
                        if((int)$value["type_id"] === (int)$questionType){
                ?>
                    <input type="hidden" name="questionType" value="<?php print($questionType); ?>" />
                    <pre><?php print($value["type_name"]); ?></pre>
                <?php
                        }
                    }
                ?>
            </div>
            <p>▼新規設問文のタイトル入力</p>
            <pre><?php print($questionTitle); ?></pre>

            <p>▼新規設問文の説明文入力</p>
            <pre><?php print($questionText); ?></pre>

            <p>▼新規設問文の回答一覧入力</p>
            <?php foreach($choiceList as $key => $value){ ?>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="selectAnswer[]" value="<?php print($value["choiceNumber"]); ?>" />
                    <pre><?php print($value["choiceText"]); ?></pre>
                </label>
            </div>
            <?php } ?>
            <p>▼新規設問文の解説文の入力</p>
            <pre><?php print($explanationText); ?></pre>
            <p><input type="submit" name="submitButton" class="btn btn-primary btn-group-justified" value="上記内容で登録をする" /></p>
        </form>
    </div>
</div>