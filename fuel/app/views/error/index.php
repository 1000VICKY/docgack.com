<div class="panel panel-primary projectBox">
    <div class="panel-heading">
        例外が発生しました。
    </div>
    <div class="panel-body">
        <div id="error">
            <p><?php print($errorMessage); ?>
        </div>
        <a id="backButton" href="#" class="btn btn-primary btn-group-justified">前ページへ戻る</a>
    </div>
</div>
<script type="text/javascript">
    $(function (){
        backButton = $("#backButton");
        backButton.click(function (e){
            history.back();
        });
    });
</script>