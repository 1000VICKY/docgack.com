<form action="/admin/checkQuestion" method="POST" name="newProjectForm">
    <input type="hidden" name="projectId" value="<?php print($projectId); ?>" />
    <div class="form-group">
        <p class="control-label">▼新規設問文の設問タイプ</p>
        <?php foreach($questionTypeList as $key => $value){ ?>
        <div class="radio-inline">
            <label for="type_id_<?php print($value->type_id); ?>">
                <input type="radio" value="<?php print($value->type_id); ?>" name="questionType" id="type_id_<?php print($value->type_id); ?>" />
                <pre><?php print($value->type_name); ?></pre>
            </label>
        </div>
        <?php } ?>
    </div>
    <p>▼新規設問文のタイトル入力</p>
    <p><input type="text" class="form-control input-lg" name="questionTitle" value="以下の設問に該当するものを一つだけ選択せよ。" /></p>

    <p>▼新規設問文の説明文入力</p>
    <p><textarea class="form-control input-lg" name="questionText" rows="5"><?php print($tempData["questionText"]); ?></textarea></p>

    <p>▼新規設問文の選択肢一覧入力(※空欄は無視されます。)</p>
    <p><a id="up">▲選択肢を増やす</a></p>
    <p><a id="down">▼選択肢を減らす</a></p>
    <div id="inputQuestionBox">
        <?php for($i = 0 ; $i < $defaultSelectCount; $i++){  ?>
            <p><textarea class="form-control input-lg input-question-textarea" name="choiceList[]" rows="7"><?php print($tempData["choiceList"][$i]["choiceText"]); ?></textarea></p>
        <?php } ?>
    </div>
    <p>▼新規設問文の解説文入力</p>
    <p><textarea class="form-control input-lg" name="explanationText" rows="10"><?php print($tempData["explanationText"]); ?></textarea></p>

    <p><input type="submit" name="submitButton" class="btn btn-primary btn-group-justified" value="上記の内容で新規設問文の作成" /></p>
</form>
<script type="text/javascript">
$(function(){
    $(window).on('beforeunload', function() {
        return "このページを離れると、入力したデータが削除されます。修正の場合には、「修正ボタン」をクリックしてください。";
    });
    $("#up").eq(0).click(function (e){
        //回答欄入力ボックス
        var $inputBox = $("#inputQuestionBox p");
        var $inputBoxCount = $inputBox.length;
        $("#inputQuestionBox").append($inputBox.eq($inputBoxCount - 1).clone());
    });
    $("#down").eq(0).click(function (e){
        //回答欄入力ボックス
        var $inputBox = $("#inputQuestionBox p");
        var $inputBoxCount = $inputBox.length;
        if($inputBoxCount >1){
            $inputBox.eq($inputBoxCount - 1).remove();
        }
    });
});
</script>