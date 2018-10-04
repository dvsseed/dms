<?php
	// global $dbh, $dbm, $dbt;
	// global $dbh;

	// $dbh = mysql_connect("127.0.0.1", "dmsuser", "W9xye3#2");
	// mysql_select_db("dms");
	// mysql_query("SET NAMES 'utf8'");

	// $dbm = mysql_connect("127.0.0.1", "root", "sql@root");
	// mysql_query("SET NAMES 'big5'");

	// $dbt = mysql_connect("127.0.0.1", "sa", "trtc2036");
	// mysql_query("SET NAMES 'big5'");

	global $dbh;

	$dbh = new mysqli('127.0.0.1', 'dmsuser', 'W9xye3#2', 'dms');
	if ($dbh->connect_errno) {
		echo "Sorry, this website is experiencing problems.";
		echo "Error: Failed to make a MySQL connection, here is why: \n";
		echo "Errno: " . $dbh->connect_errno . "\n";
		echo "Error: " . $dbh->connect_error . "\n";
		exit;
	}
	$sql = "SET NAMES 'utf8'"; // 使用Big5編碼存入MySQL
	if (!$result = $dbh->query($sql)) {
		echo "Sorry, the website is experiencing problems.";
		echo "Error: Our query failed to execute and here is why: \n";
		echo "Query: " . $sql . "\n";
		echo "Errno: " . $dbh->errno . "\n";
		echo "Error: " . $dbh->error . "\n";
		exit;
	}
