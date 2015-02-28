<div class="innerTemplate">
    <?php foreach($questionList as $key => $value) { ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            「設問No:<?php print($value["question_id"]); ?>」<?php print($value["question_title"]); ?>
        </div>
        <div class="panel-body">
            <div class="panel-body alert alert-warning alert-dismissible">
                <pre class="resetClass"><?php print($value["question_text"]); ?></pre>
            </div>
            <!--▼▼▼ここから問題の選択肢を保持-->
            <span class="selectRespond">
            <?php foreach($value["question_list_format"] as $innerKey => $innerValue){ ?>
                <?php print($innerValue); ?>
            <?php } ?>
            </span><!--//.selectRespond-->
            <!--▲▲▲-->
            <!--//▼▼▼ここから問題の回答を保持-->
            <span class="answerText">
            <?php
                if(is_array($value["choice_number"]) === true){
                    foreach($value["choice_number"] as $innerKey => $innerValue){
            ?>
                <input type="hidden" name="answerText" value="<?php print($innerValue); ?>" />
            <?php
                    }
                }
            ?>
            </span>
            <!--//▲▲▲-->
            <!--//▼▼▼ここから問題の解説文を保持-->
            <input type="hidden" class="explanation" name="explanationText" value="<?php print($value["explanation_text"]); ?>" />
            <!--//▲▲▲-->
            <form action="#" name="" method="post">
                <input type="button" class="btn btn-default btn-group-justified projectBox getRespondButton" value="回答する" />
            </form>
            <form action="#" name="" method="post">
                <input type="button" class="btn btn-default btn-group-justified projectBox getExplanationText" value="解説を見る" />
            </form>
            <div class="displayExplanationText"></div>
            <div class="clearfix"></div>
        </div>
    </div>
    <?php } ?>
</div>
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
                                    throw new Error("不正解です。");
                                }
                            }
                            alert("正解");
                        }else {
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
</script>