<?php
$mysql_hostname = "localhost";  	// host MySQL
$mysql_user = "root"; 				// username MySQL
$mysql_password = "";	// password MySQL
$mysql_database = "ddm";		// database name

// KẾT NỐI & CHỌN DATABASE
$db = mysql_connect($mysql_hostname, $mysql_user, $mysql_password) or die("CAN NOT CONNECT DATABASE");
mysql_select_db($mysql_database, $db) or die("CAN NOT SELECT DATABASE");

// THIẾT LẬP UTF8 CHO DB
mysql_query("SET NAMES 'utf8'", $db);
?>