<?php
session_start();
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
{
include "db.php";
$pagenum = isset($_GET['pagenum']) ? $_GET['pagenum'] : 0;
$pagesize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 0;
$start = $pagenum * $pagesize;
$query = "SELECT SQL_CALC_FOUND_ROWS * FROM serverlogtb ORDER BY RecordDate DESC, RecordTime DESC LIMIT $start, $pagesize";
$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
$sql = "SELECT FOUND_ROWS() AS `found_rows`;";
$rows = mysql_query($sql);
$rows = mysql_fetch_assoc($rows);
$total_rows = $rows['found_rows'];

while($rows = mysql_fetch_array($result, MYSQL_ASSOC))
{
	$serverlog[] = array(
		'Status'		=>	$rows['Status'],
		'RecordDate'	=>	$rows['RecordDate'],
		'RecordTime'	=>	$rows['RecordTime'],
		'Note'			=>	$rows['Note']
	);
}
$data[] = array('TotalRows'	=>	$total_rows, 'Rows'		=>	$serverlog);
echo json_encode($data);
}
else
{
	echo "<h1 align='center'>Truy cập bất hợp pháp hả?</h1>";
}
?>