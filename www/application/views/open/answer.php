<div class="innerTemplate">
    <div class="panel panel-primary">
        <div class="panel-heading">
            「設問No:<?php print($questionList->question_id); ?>」<?php print($questionList->question_title); ?>
        </div>
        <div class="panel-body">
            <div class="panel-body alert alert-warning alert-dismissible">
                <pre class="resetClass"><?php print(htmlspecialchars($questionList->question_text, ENT_QUOTES)); ?></pre>
            </div>
            <!--▼▼▼ここから問題の選択肢を保持-->
            <span class="selectRespond">
            <?php foreach($questionList->choice_list as $innerKey => $innerValue){ ?>
                <div class="<?php print ($questionList->type_attr); ?>">
                    <label for="answerList0_<?php print($innerKey); ?>">
                        <input class="" id="answerList0_<?php print($innerKey); ?>" name="answerList" value="<?php print(htmlspecialchars($innerValue["choiceNumber"])); ?>" type="<?php print ($questionList->type_attr); ?>">
                        <pre class="inputClick"><?php print(htmlspecialchars($innerValue["choiceText"], ENT_QUOTES, "UTF-8")); ?></pre>
                    </label>
                </div>
            <?php } ?>
            </span><!--//.selectRespond-->
            <!--▲▲▲-->
            <!--//▼▼▼ここから問題の回答を保持-->
            <span class="answerText">
            <?php
                if(is_array($questionList->choice_number) === true){
                    foreach($questionList->choice_number as $innerKey => $innerValue){ ?>
                        <input type="hidden" name="answerText" value="<?php print(htmlspecialchars($innerValue)); ?>" />
            <?php
                    }
                } ?>
            </span>
            <!--//▲▲▲-->
            <!--//▼▼▼ここから問題の解説文を保持-->
            <input type="hidden" class="explanation" name="explanationText" value="<?php print(htmlspecialchars($questionList->explanation_text)); ?>" />
            <!--//▲▲▲-->
            <form action="#" name="" method="post">
                <input type="button" class="btn btn-default btn-group-justified projectBox getRespondButton" value="回答する" id="respondBox" />
            </form>
            <form action="#" name="" method="post">
                <input type="button" class="btn btn-default btn-group-justified projectBox getExplanationText" value="解説を見る" />
            </form>
            <div class="displayExplanationText"></div>
        </div>
    </div>
</div>
<div class="col-md-6 margin-bottom margin-top">
    <?php if((int)$page > 0){ ?>
    <a href="/open/index/<?php print($projectId); ?>/<?php print($page - 1); ?>" class="btn btn-primary btn-group-justified"><<前へ</a>
    <?php } ?>
</div>
<div class="col-md-6 margin-bottom margin-top">
    <?php if($page + 1 <=  count($questionIdList) -1) { ?>
    <a href="/open/index/<?php print($projectId); ?>/<?php print($page + 1); ?>/" class="btn btn-primary btn-group-justified">次へ>></a>
    <?php } ?>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
    //<!--
    $(function (){
        //即時関数で実行
        (function (){
            window.onerror = function (errorMessage, errorUrl, errorLine){
                var error = null;
                error += errorMessage + "\r\n";
                error += errorUrl + "\r\n";
                error += errorLine + "\r\n";
                alert(error);
            }
            var explantionList = $(".getExplanationText");
            explantionList.each(function (number){
                //explantionList.eq(number)
                $(this).click((function (){
                    var flag = 1;
                    return function (e){
                        if(flag == 0){
                            $(".displayExplanationText").eq(number).text("");
                            explantionList.eq(number).val("解説を見る");
                            flag = 1;
                        }else{
                            $(".displayExplanationText").eq(number).append($("<pre />").text($(".explanation").eq(number).val()));
                            explantionList.eq(number).val("解説を閉じる");
                            flag = 0;
                        }
                    }
                }()));
            });
            var getRespondButton = $(".getRespondButton");
            getRespondButton.each(function (number, link){
                $(link).click(function (e){
                    try{
                        var answerList = $(".selectRespond").eq(number).find("input[name=answerList]:checked");
                        //選択した回答リスト
                        var selectRespond = new Array();
                        answerList.each(function (number){
                            selectRespond.push($(this).val());
                        });
                        //実際の回答リスト
                        var answerText= $(".answerText").eq(number).find("input[name=answerText]");
                        var resArray = new Array();
                        answerText.each(function (number){
                            resArray.push($(this).val());
                        });
                        /**
                         * 選択した回答と実際の回答リストが
                         * マッチするかどうかをチェックする。
                         */
                        if(resArray.length === selectRespond.length){
                            for(var key in resArray){
                                var match = 0;
                                for(var innerKey in selectRespond){
                                    if(resArray[key] == selectRespond[innerKey]){
                                        match = 1;
                                        break;
                                    }
                                }
                                if(match == 0){
                                    $("#respondBox").val("✕不正解!!");
                                    throw new Error("不正解です。");
                                }
                            }
                            $("#respondBox").val("正解!!");
                            alert("正解");
                        }else {
                            $("#respondBox").val("✕不正解!!");
                            throw new Error("不正解です。");
                        }
                    }catch(e){
                        alert(e.message);
                    }
                });
            });
            var inputList = $(".inputClick");
            var inputNumber = undefined;
            inputList.each(function (i, self){
                $(self).click(function (e){
                    if(inputNumber >= 0) {
                        if(i != inputNumber){
                            $(this).css({"background-color" : "#BBBBBB"});
                            inputList.eq(inputNumber).css({"background-color" : "#F5F5F5"});
                        }
                        inputNumber = i;
                    }else{
                        $(this).css({"background-color" : "#BBBBBB"});
                        inputNumber = i;
                    }
                });
            });
        }());
    });
    //-->
</script>