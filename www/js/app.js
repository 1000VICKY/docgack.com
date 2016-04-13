//angularJSのモジュールを定義
var docgack = angular.module("docgack", []);

/**
 * TopControllerの定義
 * トップのHTMLタグにng-controllerを設定しているため、常に実行される
 */
docgack.controller("TopController", ["$scope", "$http", function($scope, $http){
    /**
     * angularJSの初期化処理
     * $scopeに全コントローラーにわたって必要な要素を付加
     */
    $scope.init = { headers : {"Content-Type" : "application/x-www-form-urlencoded; charset=UTF-8"} }

    /**
     * HTTPリクエストの際に渡すパラメータ
     * およびJSによるHTTPリクエスト
     */
    var params = { userId : "18", hoge : "文字列テスト" };
    var config = {
        method : "POST",
        data  : $.param(params),
        url : "/api/getAllDataToJson",
        headers : $scope.init.headers
    };
    $http(config)
    .success(function (res){
        localStorage.setItem("questionList", JSON.stringify(res.message));
        //POSTリクエストが成功した際の処理
    })
    .error(function (res){
        //POSTリクエストが失敗した際の処理
    });
}]);

//BackupControllerの定義
docgack.controller("BackupController", ["$scope", "$http", function($scope, $http){

    /**
     * BackupControllerのメソッド定義
     */
    $scope.dblClick =function (){
        console.dir($scope);
        var params = $.param({ userId : "root", password : "password", dbName : "exam_system"});
        $http.post("/api/backupDB", params, $scope.init)
        .success(function (res){
            if(res.status == 200){
                alert("dbl:DBのバックアップに成功しました。");
            }else {
                alert("dbl:DBのバックアップに失敗しました。");
            }
        })
        .error(function (res){
            console.dir(res);
        });
    }
}]);