<?php
session_start();
//SELECT sum(`Amount`) FROM `paymenttb` WHERE ((`VoucherDate`>= '2013-11-20') and (`VoucherDate` <= '2013-11-21') and (`PaymentType` <> 6)) and `VoucherId` not in (select `VoucherId`  from paymenttb where (`VoucherDate` = '2013-11-20' and `VoucherTime` <= '13:00:00') or (`VoucherDate` = '2013-11-21' and `VoucherTime` >= '09:00:00'))
//SELECT sum(`Amount`) FROM `paymenttb` WHERE ((`VoucherDate` between '2013-11-21' and  '2013-11-21') and (`PaymentType` <> 6)) and `VoucherId` not in (select `VoucherId`  from paymenttb where (`VoucherDate` = '2013-11-21' and `VoucherTime` <= '11:50:00') or (`VoucherDate` = '2013-11-21' and `VoucherTime` >= '20:05:00'))
//session_start();
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
{
include "db.php";

function get_staffid($id)
{
	$result = mysql_query("SELECT UserId, UserName FROM usertb WHERE UserId=".$id);
	$rows = mysql_fetch_array($result);
	return $rows['UserName'];
}

function convert_username_to_id($username)
{
	$rs = mysql_query("SELECT UserId, FirstName FROM usertb WHERE FirstName='".$username."'");
	$rows = mysql_fetch_array($rs);
	return $rows[0];
}
if(isset($_GET['action']) && $_GET['action'] == 'viewpay')
{
	$uid = mysql_real_escape_string($_GET['uid']);
	$result = mysql_query("SELECT * FROM paymenttb WHERE UserId=".$uid." ORDER BY VoucherDate DESC, VoucherTime DESC");
	while($rows = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$data[] = array(
			'UserId'		=>	$rows['UserId'],
			'VoucherDate'	=>	$rows['VoucherDate'],
			'VoucherTime'	=>	$rows['VoucherTime'],
			'Amount'		=>	$rows['Amount'],
			'Note'			=>	$rows['Note']
		);
	}
	echo json_encode($data);
}

if(isset($_GET['action']) && $_GET['action'] == 'viewservice')
{
	$a = mysql_real_escape_string($_GET['fromdate']);
	$b = mysql_real_escape_string($_GET['todate']);
	$c = mysql_real_escape_string($_GET['emp']);
	$dfrom = trim(substr($a, 0, 11));
	$dto = trim(substr($b, 0, 11));
	$tfrom = trim(substr($a, 11));
	$tto = trim(substr($b, 11));
	if($c <> 1) $emp = " AND (StaffId=".$c.")";
	else $emp = "";
	$where = "SELECT ServiceDetailId FROM servicedetailtb WHERE (ServiceDate = '".$dfrom."' AND ServiceTime <= '".$tfrom."') OR (ServiceDate = '".$dto."' AND ServiceTime >= '".$tto."')";
	//$result = mysql_query("SELECT * FROM servicedetailtb WHERE (ServiceDate BETWEEN '".$dfrom."' AND '".$dto."')".$emp." AND (ServiceDetailId NOT IN (".$where.") AND ServicePaid=1) ORDER BY ServiceDate DESC, ServiceTime DESC");
	$query = "
		SELECT 
		servicetb.ServiceId,
		servicetb.ServiceName,
		servicetb.ServicePrice,
		servicedetailtb.ServiceDetailId,
		servicedetailtb.UserId,
		servicedetailtb.ServiceId,
		servicedetailtb.ServiceDate,
		servicedetailtb.ServiceTime,
		sum(servicedetailtb.ServiceQuantity) as TServiceQuantity,
		sum(servicedetailtb.ServiceAmount) as TServiceAmount,
		servicedetailtb.ServicePaid,
		servicedetailtb.StaffId,
		usertb.UserId,
		usertb.FirstName 
		FROM ((servicedetailtb inner join servicetb ON servicetb.ServiceId = servicedetailtb.ServiceId) INNER JOIN usertb ON servicedetailtb.StaffId = usertb.UserId) 
		WHERE (servicedetailtb.ServiceDate BETWEEN '".$dfrom."' AND '".$dto."') AND (ServicePaid = 1) AND (ServiceDetailId NOT IN (".$where."))".$emp."
		GROUP BY servicedetailtb.ServiceId
	";
	$result = mysql_query($query);
	while($rows = mysql_fetch_array($result))
	{
		//$result1 = mysql_query("SELECT ServiceId, ServiceName, ServicePrice FROM servicetb WHERE ServiceId=".$rows['ServiceId']);
		//$rows1 = mysql_fetch_array($result1);
		//$result2 = mysql_query("SELECT UserId, FirstName FROM usertb WHERE UserId=".$rows['StaffId']);
		//$rows2 = mysql_fetch_array($result2);
		$data[] = array(
			'ServiceDetailId'	=>	$rows['ServiceDetailId'],
			'ServiceDate'		=>	$rows['ServiceDate'],
			'ServiceTime'		=>	$rows['ServiceTime'],
			'ServiceQuantity'	=>	$rows['TServiceQuantity'],
			'ServiceAmount'		=>	$rows['TServiceAmount'],
			'ServiceName'		=>	$rows['ServiceName'],
			'ServicePrice'		=>	$rows['ServicePrice'],
			'FirstName'			=>	$rows['FirstName'],
		);
	}
	echo json_encode($data);
}

if(isset($_GET['action']) && $_GET['action'] == 'del22h')
{
	$f = mysql_real_escape_string($_GET['f']);
	$t = mysql_real_escape_string($_GET['t']);
	$dfrom = trim(substr($f, 0, 11));
	$dto = trim(substr($t, 0, 11));
	$j = 0;
	$query = "SELECT VoucherId, VoucherDate, VoucherTime FROM paymenttb WHERE (VoucherDate BETWEEN '".$dfrom."' AND '".$dto."') AND (VoucherTime >= '22:00:00' OR VoucherTime < '08:00:00')";
	$result = mysql_query($query);
	while($rows = mysql_fetch_array($result))
	{
		$h = rand(8, 21);
		$i = rand(0, 59);
		$s = rand(0, 59);
		if($h < 10) $h = '0'.$h;
		if($i < 10) $i = '0'.$i;
		if($s < 10) $s = '0'.$s;
		$time_rand = $h.":".$i.":".$s;
		mysql_query("UPDATE paymenttb SET VoucherTime = '".$time_rand."' WHERE VoucherId=".$rows['VoucherId']);
		$j++;
	}
	echo "Đã xử lý thành công ".$j." nhật ký";
}

if(isset($_GET['action']) && $_GET['action'] == 'viewreport')
{
	$pagenum = $_GET['pagenum'];
	$pagesize = $_GET['pagesize'];
	$start = $pagenum * $pagesize;

	$query = "SELECT SQL_CALC_FOUND_ROWS".
		" paymenttb.UserId, paymenttb.VoucherDate, paymenttb.VoucherTime, paymenttb.Amount, paymenttb.StaffId, paymenttb.Note,".
		" usertb.FirstName, usertb.UserName".
		" FROM paymenttb INNER JOIN usertb".
		" ON paymenttb.UserId = usertb.UserId".
		" WHERE (VoucherDate = '".date('Y-m-d')."') AND ServicePaid=1".
		" ORDER BY VoucherDate DESC, VoucherTime DESC".
		" LIMIT $start, $pagesize";

	$result = mysql_query($query) or die("MySQL Error: ".mysql_error());

	$result_total = mysql_query("SELECT FOUND_ROWS() AS found_rows");
	$rows_total = mysql_fetch_assoc($result_total);
	$total_rows = $rows_total['found_rows'];

	$query_total = "SELECT PaymentType, Amount, TimeTotal FROM paymenttb WHERE (VoucherDate ='".date('Y-m-d')."') AND ServicePaid=1";

	if(isset($_GET['filterscount']))
	{
		$filterscount = mysql_real_escape_string($_GET['filterscount']);
		if($filterscount > 0)
		{
			$from_value = mysql_real_escape_string($_GET['filtervalue0']);
			$to_value = mysql_real_escape_string($_GET['filtervalue1']);
			$safft_value = mysql_real_escape_string($_GET['filtervalue2']);
			$member_value = mysql_real_escape_string($_GET['filtervalue3']);

			if($safft_value <> 1) $saff = " AND (StaffId=".$safft_value.")";
			else $saff = "";

			if(!empty($member_value)) $name = " AND (FirstName = '".$member_value."')";
			else $name = "";

			$dfrom = trim(substr($from_value, 0, 11));
			$dto = trim(substr($to_value, 0, 11));
			$tfrom = trim(substr($from_value, 11));
			$tto = trim(substr($to_value, 11));

			$where = "SELECT VoucherId FROM paymenttb WHERE".
				" (VoucherDate = '".$dfrom."' AND VoucherTime <= '".$tfrom."') OR".
				" (VoucherDate = '".$dto."' AND VoucherTime >= '".$tto."')";

			$query = "SELECT SQL_CALC_FOUND_ROWS".
				" paymenttb.UserId, paymenttb.VoucherDate, paymenttb.VoucherTime, paymenttb.Amount, paymenttb.StaffId, paymenttb.Note,".
				" usertb.FirstName, usertb.UserName".
				" FROM paymenttb INNER JOIN usertb".
				" ON paymenttb.UserId = usertb.UserId".
				" WHERE".
				" (VoucherDate BETWEEN '".$dfrom."' AND '".$dto."')".
				$saff.$name.
				" AND (VoucherId NOT IN (".$where.")) AND ServicePaid=1";

			$result = mysql_query($query) or die("MySQL Error: ".mysql_error());
			$result_total = mysql_query("SELECT FOUND_ROWS() AS found_rows");
			$rows_total = mysql_fetch_assoc($result_total);
			$new_total_rows = $rows_total['found_rows'];

			$query_total = "SELECT".
				" paymenttb.UserId, paymenttb.PaymentType, paymenttb.Amount, paymenttb.TimeTotal, usertb.FirstName".
				" FROM paymenttb INNER JOIN usertb".
				" ON paymenttb.UserId = usertb.UserId".
				" WHERE".
				" (VoucherDate BETWEEN '".$dfrom."' AND '".$dto."')".
				$saff.$name.
				" AND (VoucherId NOT IN (".$where.")) AND ServicePaid=1";

			$query = "SELECT".
				" paymenttb.UserId, paymenttb.VoucherDate, paymenttb.VoucherTime, paymenttb.Amount, paymenttb.StaffId, paymenttb.Note,".
				" usertb.FirstName, usertb.UserName".
				" FROM paymenttb INNER JOIN usertb".
				" ON paymenttb.UserId = usertb.UserId".
				" WHERE".
				" (VoucherDate BETWEEN '".$dfrom."' AND '".$dto."')".
				$saff.$name.
				" AND (VoucherId NOT IN (".$where.")) AND ServicePaid=1".
				" ORDER BY VoucherDate DESC, VoucherTime DESC LIMIT $start, $pagesize";

			$total_rows = $new_total_rows;
		}
	}

	$result = mysql_query($query) or die("MySQL Error: ".mysql_error());

	$payment = null;
	$total_money = 0;
	$total_money_service = 0;
	$total_money_debit = 0;
	$total_money_undebit = 0;
	$total_hours = 0;

	while($rows = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$fn = $rows['FirstName'];
		if(empty($rows['FirstName'])) $fn = $rows['UserName'];
		$payment[] = array(
			'UserId'		=>	$fn,
			'VoucherDate'	=>	$rows['VoucherDate'],
			'VoucherTime'	=>	$rows['VoucherTime'],
			'Amount'		=>	$rows['Amount'],
			'SafftId'		=>	get_staffid($rows['StaffId']),
			'Note'			=>	$rows['Note'],
		);
	}

	$result_sum = mysql_query($query_total);
	while($rows_sum = mysql_fetch_array($result_sum))
	{
		$paytype = $rows_sum['PaymentType'];
		if($paytype == 4 || $paytype == 5 || $paytype == 10) $total_money = $total_money + $rows_sum['Amount'];
		if($paytype == 1 || $paytype == 2) $total_money_service = $total_money_service + $rows_sum['Amount'];
		if($paytype == 6) $total_money_debit = $total_money_debit + $rows_sum['Amount'];
		if($paytype == 5) $total_money_undebit = $total_money_undebit + $rows_sum['Amount'];
	}

	$data[] = array(
		'TotalRows'			=>	$total_rows,
		'TotalMoney'		=>	number_format($total_money),
		'TotalService'		=>	number_format($total_money_service),
		'TotalDebit'		=>	number_format($total_money_debit),
		'TotalUnDebit'		=>	number_format($total_money_undebit),
		'Rows'				=>	$payment
	);
	echo json_encode($data);
}
}
else
{
	echo "<h1 align='center'>Truy cập bất hợp pháp hả?</h1>";
}
?>