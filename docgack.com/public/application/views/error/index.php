<div class="panel panel-primary projectBox">
    <div class="panel-heading">
        システムに問題が発生しました。
    </div>
    <div class="panel-body">
        <div id="error">
            <p><?php print($errorMessage); ?>
        </div>
        <input type="button" id="backButton" class="btn btn-primary btn-group-justified input-lg" value="前ページへ戻る" >
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