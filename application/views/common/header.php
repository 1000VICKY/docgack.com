<!DOCTYPE html>
<html ng-app="docgack">
<head>
<meta charset="utf-8">
<meta name="viewport" content="{{message.viewport}}">
<meta name="keywords" content="{{message.keywords}}">
<meta name="description" content="{{message.description}}">
<title>{{metaMessage.title}} <?php echo($title); ?></title>
    <link type="text/css" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <link type="text/css" rel="stylesheet" href="/css/main.css?1426376435" />
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.2.js" integrity="sha256-VUCyr0ZXB5VhBibo2DkTVhdspjmxUgxDGaLQx7qb7xY=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular-route.js"></script>
    <script type="text/javascript" src="/js/app.js"></script>
    <script type="text/javascript">
    $(function (){
        var backButton = $("#backButton");
        backButton.click(function (e){
            history.back();
        }, false);
    });
    </script>
</head>
<body>
<!--//angular.jsのモジュールをアサイン-->
<div id="wrapper">
        <div id="title_header" class="">
            <h1>独学.com</h1>
        </div>
        <div id="mainHeader">
            <h2>
                <a href="/admin/index"><?php print($mainHeader); ?></a> | <a href="/admin/logout">ログアウト</a>
            </h2>
        </div><!--/#mainHeader -->