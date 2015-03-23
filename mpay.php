<?php
include "db.php";
date_default_timezone_set("Asia/Ho_Chi_Minh");
if($_POST['getdata'] == 'list')
{
	$result = mysql_query("SELECT UserId, FirstName, UserType FROM usertb WHERE UserType <> 0 AND UserType <> 1 AND UserType <> 3 AND UserType <> 4");

	$data['uname'] = array();
	while($rows = mysql_fetch_array($result))
	{
		$data1['uid'] = $rows['UserId'];
		$data1['fname'] = $rows['FirstName'];
		array_push($data['uname'], $data1);
	}

	echo json_encode($data);
}
if($_POST['senddata'] == 'payment')
{
	//$uid = addslashes($_POST['uid']);
	$uname = addslashes($_POST['user']);
	$money = addslashes($_POST['money']);
	$debit = addslashes($_POST['debit']);
	
	$query = "
		SELECT 
		usertb.UserId, 
		usertb.FirstName, 
		usertb.Debit, 
		usertb.CreditLimit, 
		usertb.UserType, 
		usertb.TimePaid, 
		usertb.MoneyPaid, 
		usertb.RemainTime, 
		usertb.RemainMoney, 
		pricemachinetb.PriceId, 
		pricemachinetb.Price 
		FROM usertb INNER JOIN pricemachinetb ON usertb.UserType = pricemachinetb.PriceId 
		WHERE FirstName='".$uname."'";
	$result = mysql_query($query);

	$totalrows = mysql_num_rows($result);
	if($totalrows == 1)
	{
		if(filter_var($money, FILTER_VALIDATE_INT, array('options'=>array('max_range'=>400000))))
		{
			$rows = mysql_fetch_array($result);
			$m = $rows['Price'] / 60;
			$totalminute = round($money / $m);

			$totaltimepaid = $rows['TimePaid'] + $totalminute;
			$totalmoneypaid = $rows['MoneyPaid'] + $money;
			$totalremaintime = $rows['RemainTime'] + $totalminute;
			$totalremainmoney = $rows['RemainMoney'] + $money;
			$totaldebit = $rows['Debit'] + $money;
			$VDate = date('Y-m-d');
			$VTime = date('H:i:s');
			$uid = $rows['UserId'];

			if($debit == 0)
			{
				if(mysql_query("UPDATE usertb SET TimePaid=".$totaltimepaid.", MoneyPaid=".$totalmoneypaid.", RemainTime=".$totalremaintime.", RemainMoney=".$totalremainmoney." WHERE FirstName='".$uname."'"))
				{
					$sql1 = "INSERT INTO paymenttb 
						(UserId, VoucherDate, VoucherTime, Amount, AutoAmount, TimeTotal, Note, ServicePaid, StaffId, PaymentType) 
						VALUE (".$uid.", '".$VDate."', '".$VTime."', ".$money.", ".$money.", ".$totalminute.", 'Thời gian phí', 1, 2, 4)";

					mysql_query($sql1);

					$msg['result'] = 1;
					$msg['msg'] = 'Nạp tiền thành công';
				}
				else
				{
					$msg['result'] = 0;
					$msg['msg'] = 'Nạp tiền thất bại';
				}
			}
			elseif($debit == 1)
			{
				if($totaldebit <= $rows['CreditLimit'])
				{
					if(mysql_query("UPDATE usertb SET Debit=".$totaldebit.", TimePaid=".$totaltimepaid.", MoneyPaid=".$totalmoneypaid.", RemainTime=".$totalremaintime.", RemainMoney=".$totalremainmoney." WHERE FirstName='".$uname."'"))
					{
						$sql1 = "INSERT INTO paymenttb 
							(UserId, VoucherDate, VoucherTime, Amount, AutoAmount, TimeTotal, Note, ServicePaid, StaffId, PaymentType) 
							VALUE (".$uid.", '".$VDate."', '".$VTime."', ".$money.", ".$money.", ".$totalminute.", 'Số tiền mượn', 1, 2, 6)";

						mysql_query($sql1);

						$msg['result'] = 1;
						$msg['msg'] = 'Nạp tiền thành công';
					}
					else
					{
						$msg['result'] = 0;
						$msg['msg'] = 'Nạp tiền thất bại';
					}
				}
				else
				{
					$msg['result'] = 0;
					$msg['msg'] = 'Quá giới hạn nợ';
				}
			}
			else
			{
				$msg['result'] = 0;
				$msg['msg'] = 'Có lổi xẩy ra';
			}

		}
		else
		{
			$msg['result'] = 0;
			$msg['msg'] = 'Số tiền sai hoặc quá lớn';
		}
	}
	else
	{
		$msg['result'] = 0;
		$msg['msg'] = 'Sai tên hội viên hoặc trùng tên';
	}
	echo json_encode($msg);
}
/*
if($_POST['senddata'] == 'payment')
{
	$uname = $_POST['user'];
	$money = $_POST['money'];

	$query = mysql_query("SELECT FirstName, RemainMoney FROM usertb WHERE FirstName='".$uname."'");
	$r = mysql_fetch_array($query);
	$old_RemainMoney = $r['RemainMoney'] + $money;

	
	$query = mysql_query("UPDATE usertb SET RemainMoney=".$old_RemainMoney." WHERE FirstName='".$uname."'");
	if(@mysql_num_rows($query) == 1)
	{
		$msg['result'] = 1;
		$msg['msg'] = 'Da nap tien thanh cong';
	}
	else
	{
		$msg['result'] = 0;
		$msg['msg'] = 'Nap tien that bai';
	}
	echo json_encode($msg);
}
*/
?>