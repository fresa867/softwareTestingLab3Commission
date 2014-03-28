<?php
//Connect to database 
  $link_id = @mysql_connect("localhost","root","mysql") or die ("No connection to database.");
  $database = mysql_select_db("commission_program");

  $monthStrings = array("January","February","March",
  	"April","May","June",
  	"July","August", "September",
  	"October","November","December");

  if($_POST['action'] == "add"){
	$locks = $_POST['numberOfLocks'];
	$town = $_POST['town'];
	$stocks = $_POST['numberOfStocks'];
	$barrels = $_POST['numberOfBarrels'];
	addSales($town,$locks,$stocks,$barrels);
	echo getInfo();

  } else if ($_POST['action'] == "getInfo"){
  	echo getInfo();
  } else if ($_POST['action'] == "submitMonth") {
  	echo submitMonth();
  } else if ($_POST['action'] == "getReport") {
  	echo getMonthlyReport();
  }

function getInfo(){

	global $monthStrings;

	$currentMonth = date("m");

	$qry = mysql_query("SELECT salesID, id, townID, SUM( numberOfLocks ) AS numberOfLocks, SUM( numberOfStocks ) AS numberOfStocks, SUM( numberOfBarrels ) AS numberOfBarrels
FROM sales WHERE month = '$currentMonth'") or die(mysql_error());
	$data = array();
	while($rows = mysql_fetch_array($qry)){
		$id = $rows['id'];
		$townID = $rows['townID'];
		$numberOfLocks = $rows['numberOfLocks'];
		$numberOfStocks = $rows['numberOfStocks'];
		$numberOfBarrels = $rows['numberOfBarrels'];

	}

	$qry = mysql_query("SELECT totalAmount, productID
FROM products") or die(mysql_error());
	while($rows = mysql_fetch_array($qry)){
		if($rows['productID'] == 1) {
		$totalAmountOfLocks = $rows['totalAmount'];
		} else if($rows['productID'] == 2){
		$totalAmountOfStocks = $rows['totalAmount'];
		} else if($rows['productID'] == 3) {
		$totalAmountOfBarrels = $rows['totalAmount'];
		}

	}

	$locksLeft = $totalAmountOfLocks - $numberOfLocks;
	$stocksLeft = $totalAmountOfStocks - $numberOfStocks;
	$barrelsLeft = $totalAmountOfBarrels - $numberOfBarrels;

	$data[] = array("currentMonth" => $monthStrings[$currentMonth-1],
		"salesID" => $id,
		"id" => $id,
		"townID" => $townID,
		"numberOfLocks" => $numberOfLocks,
		"numberOfStocks" => $numberOfStocks,
		"numberOfBarrels" => $numberOfBarrels,
		"locksLeft" => $locksLeft,
		"stocksLeft" => $stocksLeft,
		"barrelsLeft" => $barrelsLeft);
	return json_encode($data);

}

function getMonthlyReport(){

	global $monthStrings;

	$qry = mysql_query("SELECT sum( s.numberOfLocks * (
		SELECT productPrice
		FROM products
		WHERE productID =1 ) ) + sum( s.numberOfStocks * (
		SELECT productPrice
		FROM products
		WHERE productID =2 ) ) + sum( s.numberOfBarrels * (
		SELECT productPrice
		FROM products
		WHERE productID =3 ) ) AS totalSales, sum( s.numberOfLocks ) AS totalLocksSold, sum( s.numberOfStocks ) AS totalStocksSold, sum( s.numberOfBarrels ) AS totalBarrelsSold, s.month AS
	month , s.year AS year
	FROM sales AS s, reportedMonths AS r
	WHERE r.finished =
	TRUE AND r.month = s.month
	AND r.year = s.year
	GROUP BY month , year") or die(mysql_error());
	
	$data = array();
	while($rows = mysql_fetch_array($qry)){
		$totalSales = $rows['totalSales'];
		$totalLocksSold = $rows['totalLocksSold'];
		$totalStocksSold = $rows['totalStocksSold'];
		$totalBarrelsSold = $rows['totalBarrelsSold'];
		$month = $rows['month'];
		$year = $rows['year'];
	

		if($totalSales >= 1000) {

			if($totalSales > 1800) {
				$upperIntervalAmount = $totalSales - 1800;
				$upperIntervalCommission = $upperIntervalAmount*0.2;
				$totalLowerIntervalCommission = 1000*0.1;
				$totalMidIntervalCommission = 800*0.15;
							
	$commission = $upperIntervalCommission + $totalMidIntervalCommission + $totalLowerIntervalCommission;

			} else {
				$midIntervalAmount = $totalSales - 1000;
				$midIntervalCommission = $midIntervalCommission*0.15;
				$totalLowerIntervalCommission = 1000*0.1;
				$commission = $totalLowerIntervalCommission + $midIntervalCommission;

			}

		} else {
			$commission = $totalSales*0.1;
		}

	$data[] = array("month" => $monthStrings[$month-1],
		"year" => $year,
		"totalSales" => $totalSales,
		"totalLocksSold" => $totalLocksSold,
		"totalStocksSold" => $totalStocksSold,
		"totalBarrelsSold" => $totalBarrelsSold,
		"commission" => $commission);

	}

	return json_encode($data);

}

function addSales($town,$locks,$stocks,$barrels){
	$town = mysql_real_escape_string($town);
	$locks = mysql_real_escape_string($locks);
	$stocks = mysql_real_escape_string($stocks);
	$barrels = mysql_real_escape_string($barrels);

	$currentYear = date("Y");
	$currentMonth = date("m");
//	$id = 0;
	$qry = mysql_query("INSERT INTO sales(salesID, id, townID, numberOfLocks, numberOfStocks, numberOfBarrels, month, year)VALUES(NULL, 1, '$town', '$locks', '$stocks', '$barrels', '$currentMonth','$currentYear')")or die(mysql_error());	
/*	$id = mysql_insert_id();
	return json_encode(array("salesID" => $id,
		"id" => 1,
		"townID" => 1,
		"numberOfLocks" => $locks,
		"numberOfStocks" => 0,
		"numberOfBarrels" => 0));*/
/*	$qry = mysql_query("SELECT salesID, id, townID, SUM( numberOfLocks ) AS numberOfLocks, SUM( numberOfStocks ) AS numberOfStocks, SUM( numberOfBarrels ) AS numberOfBarrels
FROM sales") or die(mysql_error());
	$data = array();
	while($rows = mysql_fetch_array($qry)){
	$data[] = array("salesID" => $rows['salesID'],
		"id" => $rows['id'],
		"townID" => $rows['townID'],
		"numberOfLocks" => $rows['numberOfLocks'],
		"numberOfStocks" => $rows['numberOfStocks'],
		"numberOfBarrels" => $rows['numberOfBarrels']);
	}
	return json_encode($data);*/


}

function submitMonth(){

	$currentYear = date("Y");
	$currentMonth = date("m");

	$qry = mysql_query("INSERT INTO reportedMonths (month,year,finished) 
values('$currentMonth','$currentYear',true)") or die(mysql_error());

 }

