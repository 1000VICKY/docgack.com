<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<meta name="keywords" content="独学.com,独学,資格勉強,docgack.com,webサービス">
<meta name="description" content="資格・認定試験問題作成WEBサービス">
<title>独学.com/<?php echo($title); ?></title>
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
/**
 * グーグルアナリティクス
 * トラッキングコード
 */
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-60506428-1', 'auto');
  ga('send', 'pageview');
</script>
</head>
<body>
<div id="wrapper">
        <div id="title_header" class="">
            <h1>独学.com</h1>
        </div>
        <div id="mainHeader">
            <h2>
                <a href="/admin/index"><?php print($mainHeader); ?></a> | <a href="/admin/logout">ログアウト</a>
            </h2>
        </div><!--/#mainHeader -->

        <?php echo($content); ?>

</div><!--/#wrapper -->
<div id="footer" class="centeringBox">
    <p><img src="https://www.ssl-store.jp/system/images/seal/rapidssl_seal.gif" ></p>
    <address><?php echo($footer); ?></address>
</div>
</body>
</html>