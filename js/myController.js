var salesApp = angular.module('salesApp', [ 'ngRoute']);

salesApp.config(['$routeProvider',
	function($routeProvider) {
		$routeProvider.
		when('/submitSales', {
			templateUrl: 'partials/submitSales.html',
			controller: 'myController'
		}).
		when('/salesReport', {
			templateUrl: 'partials/salesReport.html',
			controller: 'myController'
		}).
		otherwise({
			redirectTo: '/submitSales'
		});
	}]);

function myController($scope,$http) {

	$http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

	// Get existing information from database at startup
	$http({
			  	method : "POST",
			  	url : "databaseHandler.php", 
				data : "action=getInfo"
			  }).success(function(data){

				  $scope.sales = data;
				    console.log($scope.sales);
				  //alert("Success "+data);
				  	  $scope.numberOfLocksLeft = [];
				  	  for (var i=0; i<$scope.sales[0].locksLeft+1; i++) 
				  	  	$scope.numberOfLocksLeft.push(i);

				  	  $scope.numberOfStocksLeft = [];
				  	  for (var i=0; i<$scope.sales[0].stocksLeft+1; i++) 
				  	  	$scope.numberOfStocksLeft.push(i);

				  	  $scope.numberOfBarrelsLeft = [];
				  	  for (var i=0; i<$scope.sales[0].barrelsLeft+1; i++) 
				  	  	$scope.numberOfBarrelsLeft.push(i);

				  	
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
					  	  $scope.numberOfLocksLeft = [];
				  	  for (var i=0; i<$scope.sales[0].locksLeft+1; i++) 
				  	  	$scope.numberOfLocksLeft.push(i);

				  	  $scope.numberOfStocksLeft = [];
				  	  for (var i=0; i<$scope.sales[0].stocksLeft+1; i++) 
				  	  	$scope.numberOfStocksLeft.push(i);

				  	  $scope.numberOfBarrelsLeft = [];
				  	  for (var i=0; i<$scope.sales[0].barrelsLeft+1; i++) 
				  	  	$scope.numberOfBarrelsLeft.push(i);
			  }); 
	
	}; 

	$scope.submitMonth = function() {
		
		$http({ // Send information to database
			  	method : "POST",
			  	url : "databaseHandler.php", 
				data : "action=submitMonth"
			  }).success(function(data){
				//Add users to the array users
		     	  $scope.submitAnswer = data; 
				//alert("Success!");
			  }); 
	
	};  

	$scope.getReport = function() {
		
		$http({ // Send information to database
			  	method : "POST",
			  	url : "databaseHandler.php", 
				data : "action=getReport"
			  }).success(function(data){
				//Add users to the array users
		     	  $scope.report = data; 
				//alert("Success!");
			  }); 
	
	}; 


};