//angularJSのモジュールを定義
var docgack = angular.module("docgack", []);

//TopControllerの定義
docgack.controller("TopController", ["$scope", "$http", function($scope, $http){
    var successCallback = function (res){
        console.dir(res.data);
        if(window.localStorage){
            console.log("localstorage対応済み");
        }
    };
    var errorCallback = function (res){
        alert(0);
        console.dir(res.message);
    };
    //HTTPリクエストの際に渡すパラメータ
    var params = { userId : "18", hoge : "文字列テスト" };
    var config = {
        method : "POST",
        data  : $.param(params),
        url : "/api/getAllDataToJson",
        headers : {"Content-Type" : "application/x-www-form-urlencoded; application/json; charset=UTF-8"}
    };
    //POSTメソッドによるHTTP通信
    $http(config)
    .success(function (res){
        console.dir(res);
    })
    .error(function (res){
        console.dir(res);
    });
}]);

//BackupControllerの定義
docgack.controller("BackupController", ["$scope", "$http", function($scope, $http){
    $scope.backupDB = function (){
        var params = $.param({ userId : "root", password : "password", dbName : "exam_system"});
        var config = { headers : {"Content-Type" : "application/x-www-form-urlencoded; application/json; charset=UTF-8"} };

        $http.post("/api/backupDB", params, config)
        .success(function (res){
            console.dir(res);
        })
        .error(function (res){
            console.dir(res);
        });
    }
}]);