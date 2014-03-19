var salesApp = angular.module('salesApp', []);

function myController($scope,$http) {

	$http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

	$scope.locks = "0";
	$scope.stocks = "0";
	$scope.barrels = "0";

	// Get existing information from database at startup
	$http({
			  	method : "POST",
			  	url : "databaseHandler.php", 
				data : "action=getInfo"
			  }).success(function(data){
				  $scope.sales = data;
				  //alert("Success "+data);
			  });

	$scope.submitSales = function(town,locks,stocks,barrels) {
		
		$http({ // Send information to database
			  	method : "POST",
			  	url : "databaseHandler.php", 
				data : "action=add&town="+town+"&numberOfLocks="+locks+"&numberOfStocks="+stocks+"&numberOfBarrels="+barrels
			  }).success(function(data){
				//Add users to the array users
		     	  $scope.sales = data; 
				//alert("Success!");
			  }); 
	
	}; 

	$scope.submitMonth = function() {
		
		$http({ // Send information to database
			  	method : "POST",
			  	url : "databaseHandler.php", 
				data : "action=submitMonth"
			  }).success(function(data){
				//Add users to the array users
		     	  $scope.report = data; 
				//alert("Success!");
			  }); 
	
	};  


};