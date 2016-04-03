<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<meta name="keywords" content="独学.com,独学,資格勉強,docgack.com,webサービス">
<meta name="description" content="独学で合格！資格・認定試験問題作成WEBサービス">
<title>独学.com <?php echo($title); ?></title>
    <link type="text/css" rel="stylesheet" href="/css/bootstrap.min.css?1423914270" />
    <link type="text/css" rel="stylesheet" href="/css/main.css?1426376435" />
    <script type="text/javascript" src="/js/jquery.js?1423914270"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js?1423914270"></script>
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
    </script>
    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script>
        /*
        tinymce.init({
            selector: 'textarea.testTextArea',
            height: 300,
            theme: 'modern',
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools',
                'codesample'
                ],
                toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                toolbar2: 'print preview media | forecolor backcolor emoticons | codesample',
                image_advtab: true,
                templates: [
                { title: 'Test template 1', content: 'Test 1' },
                { title: 'Test template 2', content: 'Test 2' }
            ],
            content_css: [
            '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
            '//www.tinymce.com/css/codepen.min.css'
          ]
         });
         */
    </script>
</head>
<body>
<!--//▼▼▼Google Tag Manager -->
<noscript>
    <iframe height="0" src="//www.googletagmanager.com/ns.html?id=GTM-PPL85D" style="display:none;visibility:hidden" width="0"></iframe>
</noscript>
<script>
    (function (w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
            'gtm.start': new Date().getTime(),
            event: 'gtm.js'
        });
        var f = d.getElementsByTagName(s)[0],
            j = d.createElement(s),
            dl = l != 'dataLayer'
                ? '&l=' + l
                : '';
        j.async = true;
        j.src = '//www.googletagmanager.com/gtm.js?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-PPL85D');
</script>
<!--//▲▲▲End Google Tag Manager -->
<div id="wrapper">
        <div id="title_header" class="">
            <h1>独学.com</h1>
        </div>
        <div id="mainHeader">
            <h2>
                <a href="/admin/index"><?php print($mainHeader); ?></a> | <a href="/admin/logout">ログアウト</a>
            </h2>
        </div><!--/#mainHeader -->