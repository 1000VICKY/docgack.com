
<div class="innerTemplate">
    <div class="panel panel-primary">
        <div class="panel-heading">
            「設問No:<?php print($questionData->question_id); ?>」<?php print(htmlspecialchars($questionData->question_title, ENT_QUOTES)); ?>
        </div>
        <div class="panel-body">
            <div class="panel-body alert alert-warning alert-dismissible">
                <pre class="resetClass"><?php print(htmlspecialchars($questionData->question_text, ENT_QUOTES)); ?></pre>
            </div>
            <!--▼▼▼ここから問題の選択肢を保持-->
            <span class="selectRespond">
            <?php foreach($questionData->choice_list as $innerKey => $innerValue){ ?>
                <div class="<?php print($questionData->type_attr); ?>">
                <label for="answerList0_<?php print($innerKey); ?>">
                    <input class="" id="answerList0_<?php print($innerKey); ?>" name="answerList" value="<?php print($innerValue["choiceNumber"]); ?>" type="<?php print($questionData->type_attr); ?>">
                        <pre class="inputClick"><?php print(htmlspecialchars($innerValue["choiceText"], ENT_QUOTES)); ?></pre>
                </label>
                </div>
            <?php } ?>
            </span><!--//.selectRespond-->
            <!--▲▲▲-->
            <!--//▼▼▼ここから問題の回答を保持-->
            <span class="answerText">
            <?php
                if(is_array($questionData->choice_number) === true){
                    foreach($questionData->choice_number as $innerKey => $innerValue){ ?>
                        <input type="hidden" name="answerText" value="<?php print(htmlspecialchars($innerValue, ENT_QUOTES)); ?>" />
            <?php
                    }
                } ?>
            </span>
            <!--//▲▲▲-->
            <!--//▼▼▼ここから問題の解説文を保持-->
            <input type="hidden" class="explanation" name="explanationText" value="<?php print(htmlspecialchars($questionData->explanation_text, ENT_QUOTES)); ?>" />
            <!--//▲▲▲-->
            <form action="#" name="" method="post">
                <input type="button" class="btn btn-default btn-group-justified projectBox getRespondButton" value="回答する" />
            </form>
            <pre id="answer"></pre>
            <form action="#" name="" method="post">
                <input type="button" class="btn btn-default btn-group-justified projectBox getExplanationText" value="解説を見る" />
            </form>
            <div class="displayExplanationText"></div>
            <a class="btn btn-default btn-group-justified projectBox" href="/admin/modifyQuestion/<?php print($projectId); ?>/<?php print($questionData->question_id); ?>" >この設問を編集する</a>
        </div>
    </div>
</div>
<div class="col-md-6 margin-bottom margin-top">
    <?php if((int)$page > 0){ ?>
    <a href="/admin/oneQuestion/<?php print($projectId); ?>/<?php print($page - 1); ?>" class="btn btn-primary btn-group-justified"><<前へ</a>
    <?php } ?>
</div>
<div class="col-md-6 margin-bottom margin-top">
    <?php if($page + 1 <=  count($questionNumberList) -1) { ?>
    <a href="/admin/oneQuestion/<?php print($projectId); ?>/<?php print($page + 1); ?>" class="btn btn-primary btn-group-justified">次へ>></a>
    <?php } ?>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
    //<!--
    $(function (){
        $("#answer").hide();
        window.onerror = function (errorMessage, errorUrl, errorLine){
            var error = null;
            error += errorMessage + "\r\n";
            error += errorUrl + "\r\n";
            error += errorLine + "\r\n";
            alert(error);
        }
        //設問の解説文の表示スクリプト
        var explantionList = $(".getExplanationText");
        $(".displayExplanationText").eq(0).hide();
        $(".displayExplanationText").eq(0).append($("<pre />").text($(".explanation").eq(0).val()));
        explantionList.each(function (number){
            $(this).click((function (){
                var flag = 1;
                return function (e){
                    if(flag == 0){
                        $(".displayExplanationText").slideUp("fast");
                        explantionList.eq(number).val("解説を見る");
                        flag = 1;
                    }else{
                        $(".displayExplanationText").slideDown("fast");
                        explantionList.eq(number).val("解説を閉じる");
                        flag = 0;
                    }
                }
            }()));
        });
        //正解／不正解の表示スクリプト
        var getRespondButton = $(".getRespondButton");
        var answer = $("#answer");
        getRespondButton.each(function (number, link){
            $(link).click(function (e){
                answer.slideUp("slow", function (){
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
                                    throw new Error("不正解");
                                }
                            }
                            answer.html("正解");
                            answer.slideDown("fast");
                        }else {
                            throw new Error("不正解");
                        }
                    }catch(e){
                        answer.html(e.message);
                        answer.slideDown("fast");
                    }
                });
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
    });
    //-->
</script>