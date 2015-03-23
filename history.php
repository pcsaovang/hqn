<?php
session_start();
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
{
include "db.php";
function calc_date($a)
{
	$tmp = mysql_query("SELECT '".date('Y-m-d')."' - INTERVAL ".$a." DAY");
	$r = mysql_fetch_array($tmp);
	return $r[0];
}
if(isset($_GET['action']) && $_GET['action'] == 'addurl')
{
	$value = $_GET['v'];

	$dataurl = array();
	$file = fopen('url.txt', 'r');
	if($file){
		while(!feof($file)){
			$dataurl[] = trim(fgets($file));
		}
		fclose($file);
	}
	if(in_array($value, $dataurl))
	{
		echo "Địa chỉ ".$value." đã có.";
	}
	else
	{
		$value = "\r\n".$value;
		$file = fopen("url.txt", "a+");
		if($file){
			fwrite($file, $value);
			fclose($file);
			echo "Thêm thành công.";
		}
	}
}
if(isset($_GET['action']) && $_GET['action'] == 'deleteurl')
{
	$numdate = mysql_real_escape_string($_GET['d']);
	if($numdate == 0)
	{
		$where = " WHERE (DATE(RecordDate) = '".date('Y-m-d')."')";
	}
	elseif($numdate == 1)
	{
		$df = calc_date(7);
		$where = " WHERE (DATE(RecordDate) BETWEEN '".$df."' AND '".date('Y-m-d')."')";
	}
	elseif($numdate == 2)
	{
		$df = calc_date(30);
		$where = " WHERE (DATE(RecordDate) BETWEEN '".$df."' AND '".date('Y-m-d')."')";
	}
	else
	{
		$where = "";
	}

	$dataurl = array();
	$file = fopen('url.txt', 'r');
	if($file){
		while(!feof($file)){
			$dataurl[] = "'%".trim(fgets($file))."%'";
		}
		fclose($file);
	}
	$str1 = implode(' AND URL NOT LIKE ', $dataurl);
	if(empty($where))
	{
		$where .= " WHERE ((URL NOT LIKE {$str1}) OR (TIME(RecordDate) >= '22:00:00' OR TIME(RecordDate) <= '08:00:00'))";
	}
	else
	{
		$where .= " AND ((URL NOT LIKE {$str1}) OR (TIME(RecordDate) >= '22:00:00' OR TIME(RecordDate) <= '08:00:00'))";
	}

	$result = mysql_query("SELECT URLId FROM webhistorytb".$where);
	$j = 0;
	while($rows = mysql_fetch_array($result))
	{
		$j++;
		$query = "DELETE FROM webhistorytb WHERE URLId=".$rows[0];
		mysql_query($query);
	}
	echo $j;
}

if(isset($_GET['action']) && $_GET['action'] == 'web')
{
	$pagenum = $_GET['pagenum'];
	$pagesize = $_GET['pagesize'];
	$start = $pagenum * $pagesize;

	$query = "SELECT SQL_CALC_FOUND_ROWS * FROM webhistorytb WHERE DATE(RecordDate) = '".date('Y-m-d')."' ORDER BY RecordDate DESC LIMIT $start, $pagesize";
	$result = mysql_query($query);

	$result_total = mysql_query("SELECT FOUND_ROWS() AS found_rows");
	$rows_total = mysql_fetch_assoc($result_total);
	$total_rows = $rows_total['found_rows'];

	if(isset($_GET['filterscount']))
	{
		$filterscount = mysql_real_escape_string($_GET['filterscount']);
		if($filterscount > 0)
		{
			$numdate = mysql_real_escape_string($_GET['filtervalue0']);
			$d = date('Y-m-d');
			if($numdate == 0)
			{
				$where = " WHERE (DATE(RecordDate) ='".$d."')";
			}
			elseif($numdate == 1)
			{
				$df = calc_date(7);
				$where = " WHERE (DATE(RecordDate) BETWEEN '".$df."' AND '".$d."')";
			}
			elseif($numdate == 2)
			{
				$df = calc_date(30);
				$where = " WHERE (DATE(RecordDate) BETWEEN '".$df."' AND '".$d."')";
			}
			else
			{
				$where = "";
			}
			$query = "SELECT * FROM webhistorytb".$where;
			$result = mysql_query($query);
			$result_total = mysql_query("SELECT FOUND_ROWS() AS found_rows");
			$rows_total = mysql_fetch_array($result_total);
			$new_total_rows = $rows_total['found_rows'];

			$query = "SELECT * FROM webhistorytb ".$where." ORDER BY RecordDate DESC LIMIT $start, $pagesize";
			$total_rows = $new_total_rows;
		}
	}

	$result = mysql_query($query);

	while($rows = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$result_user = mysql_query("SELECT UserId, FirstName FROM usertb WHERE UserId=".$rows['UserId']);
		$rows_user = mysql_fetch_array($result_user);
		if(empty($rows_user['FirstName'])) $use = $rows['Machine'];
		else $use = $rows_user['FirstName'];
		$history[] = array(
			'URLId'			=>	$rows['URLId'],
			'URL'			=>	$rows['URL'],
			'RecordDate'	=>	$rows['RecordDate'],
			'UserId'		=>	$use,
			'Machine'		=>	$rows['Machine']
		);
	}
	$data[] = array('TotalRows' => $total_rows, 'Rows' => $history);
	echo json_encode($data);
}
}
else
{
	echo "<h1 align='center'>Truy cập bất hợp pháp hả?</h1>";
}
?>