<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<meta name="keywords" content="独学,資格勉強,docgack.com,webサービス">
<meta name="description" content="資格・認定試験問題作成WEBサービス">
<title>独学.com|<?php echo($title); ?>|docgack.com</title>
<?php echo Asset::css("bootstrap.min.css"); ?>
<?php echo Asset::css("main.css"); ?>
<?php echo Asset::js("jquery.js"); ?>
<?php echo Asset::js("bootstrap.min.js"); ?>
<script type="text/javascript">
//<!--
//onloadイベントにイベントタンドラを付加
$(function (){
    var backButton = $("#backButton");
    backButton.click(function (e){
        history.back();
    }, false);
});
<?php
/*
document.onkeydown = cancel_tab;
function cancel_tab(e) {
    if(e == undefined){
        if(event.keyCode==9){
            event.returnValue=false;
            return false;
        }
    } else {
    if(e.which==9)
        return false;
    }
}
*/
?>
</script>
</head>
<body>
<div id="wrapper">
        <div class="btn btn-success btn-group-justified projectBox">
            <h1>独学.com|<?php print($subHeader); ?>|docgack.com</h1>
        </div>
        <div id="mainHeader">
            <h2>
                <a href="/admin/index"><?php print($mainHeader); ?></a> | <a href="/admin/logout">ログアウト</a>
            </h2>
        </div><!--/#mainHeader -->

        <?php echo($content); ?>

</div><!--/#wrapper -->
<div id="footer"><?php echo($footer); ?></div>
</body>
</html>