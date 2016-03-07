var myApp = angular.module('myApp', []);

myApp.controller('ctrl', function ($scope, $http) {

	
    $http.get('dept.json').success(function (sdata) {
        $scope.extra = sdata;
    });

    $http.get('emp.php').success(function (data) {
        $scope.emp = data;
    });

  	$scope.reverseSort = false;


});