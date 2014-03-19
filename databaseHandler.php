<?php
//Connect to database 
  $link_id = @mysql_connect("localhost","root","mysql") or die ("No connection to database.");
  $database = mysql_select_db("commission_program");

  if($_POST['action'] == "add"){
	$locks = $_POST['numberOfLocks'];
	$town = $_POST['town'];
	$stocks = $_POST['numberOfStocks'];
	$barrels = $_POST['numberOfBarrels'];
	echo addSales($town,$locks,$stocks,$barrels);

  } else if ($_POST['action'] == "getInfo"){
  	echo getInfo();
  } else if ($_POST['action'] == "submitMonth") {
  	echo getMonthlyReport();
  }

function getInfo(){
	$qry = mysql_query("SELECT salesID, id, townID, SUM( numberOfLocks ) AS numberOfLocks, SUM( numberOfStocks ) AS numberOfStocks, SUM( numberOfBarrels ) AS numberOfBarrels
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
	return json_encode($data);

}

function getMonthlyReport(){
	$qry = mysql_query("SELECT salesID, id, townID, SUM( numberOfLocks ) AS numberOfLocks, SUM( numberOfStocks ) AS numberOfStocks, SUM( numberOfBarrels ) AS numberOfBarrels
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
	return json_encode($data);

}

function addSales($town,$locks,$stocks,$barrels){
	$town = mysql_real_escape_string($town);
	$locks = mysql_real_escape_string($locks);
	$stocks = mysql_real_escape_string($stocks);
	$barrels = mysql_real_escape_string($barrels);
//	$id = 0;
	$qry = mysql_query("INSERT INTO sales(salesID, id, townID, numberOfLocks, numberOfStocks, numberOfBarrels, month)VALUES(NULL, 1, '$town', '$locks', '$stocks', '$barrels', 0)")or die(mysql_error());	
/*	$id = mysql_insert_id();
	return json_encode(array("salesID" => $id,
		"id" => 1,
		"townID" => 1,
		"numberOfLocks" => $locks,
		"numberOfStocks" => 0,
		"numberOfBarrels" => 0));*/
	$qry = mysql_query("SELECT salesID, id, townID, SUM( numberOfLocks ) AS numberOfLocks, SUM( numberOfStocks ) AS numberOfStocks, SUM( numberOfBarrels ) AS numberOfBarrels
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
	return json_encode($data);

}