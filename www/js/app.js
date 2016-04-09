


var docgackApp = andular.module("docgack", ["ngRoute"]);

var IndexController = function ($scope){
    $scope.metaMessage = {
        title : "独学.com",
        viewport : "width=device-width, initial-scale=1, user-scalable=no",
        keyword : "独学.com,独学,資格勉強,docgack.com,webサービス",
        description : "独学で合格！資格・認定試験問題作成WEBサービス"
    }
}
docgackApp . controller("IndexController", IndexController);