<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

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
//-->
</script>
</head>
<body>
    <div id="wrapper">
        <div class="btn btn-success btn-group-justified projectBox"><?php print($subHeader); ?></div>
        <div id="mainHeader">
            <h1><a href="/admin/index"><?php print($mainHeader); ?></a></h1>
        </div>
        <?php echo($content); ?>
    </div>
    <div id="footer">
        <?php echo($footer); ?>
    </div>
</body>
</html>