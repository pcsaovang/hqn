<?php
session_start();
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
{
	include "db.php";
	$datefrom = $_GET['datefrom'];
	//$dateto = $_GET['dateto'];
	$result_total_money = mysql_query("SELECT SUM(Amount) FROM paymenttb WHERE VoucherDate='".$datefrom."'");
	$rows_total_money = mysql_fetch_array($result_total_money);
	$data[] = array('TotalMoneyRows' => $rows_total_money[0]);
	echo json_encode($data);
}
else
{
	echo "<h1 align='center'>Truy cập bất hợp pháp hả?</h1>";
}
?>