<?php
session_start();
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
{
	include "db.php";
	$pagenum = $_GET['pagenum'];
	$pagesize = $_GET['pagesize'];
	$start = $pagenum * $pagesize;
	$query = "SELECT SQL_CALC_FOUND_ROWS * FROM clientsystb LIMIT $start, $pagesize";
	$result = mysql_query($query);
	$result_total = mysql_query("SELECT FOUND_ROWS() AS found_rows");
	$rows_total = mysql_fetch_assoc($result_total);
	$total_rows = $rows_total['found_rows'];

	while($rows = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$sysclient[] = array(
			'cpu'		=>	$rows['CPU'],
			'ram'		=>	$rows['RAM'],
			'vga'		=>	$rows['CardName'],
			'vgachip'	=>	$rows['ChipType'],
			'vgamem'	=>	$rows['VGAMem'],
			'lannic'	=>	$rows['NIC'],
			'cpname'	=>	$rows['CPName']
		);
	}
	$data[] = array('TotalRows' => $total_rows, 'Rows' => $sysclient);
	echo json_encode($data);
}
else
{
	echo "<h1 align='center'>Truy cập bất hợp pháp hả?</h1>";
}
?>