<div id="error">
    <p><?php print($errorMessage); ?>
</div>
<a id="backButton" href="#" class="btn btn-primary btn-group-justified">前ページへ戻る</a>
<script type="text/javascript">
    $(function (){
        backButton = $("#backButton");
        backButton.click(function (e){
            history.back();
        });
    });
</script>