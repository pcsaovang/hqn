<?php
session_start();
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
{
	include "db.php";
	$result = mysql_query("SELECT UserId, FirstName FROM usertb WHERE (UserType=3 OR UserType=4)");
	while($rows = mysql_fetch_array($result))
	{
		if($rows['UserId'] == 1) $uname = 'Tất cả';
		else $uname = $rows['FirstName'];
		$data[] = array('employeeId' => $rows['UserId'], 'employeeName' => $uname);
	}
	echo json_encode($data);
}
else
{
	echo "<h1 align='center'>Truy cập bất hợp pháp hả?</h1>";
}
?>