<?php
session_start();
if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
{
	include "db.php";
	$errors = "";
	$old_pass = mysql_real_escape_string($_POST['txtoldpass']);
	$new_pass = mysql_real_escape_string($_POST['txtnewpass']);
	$very_pass = mysql_real_escape_string($_POST['txtverypass']);
	if(!empty($old_pass) && !empty($new_pass) && !empty($very_pass))
	{
		if($new_pass == $very_pass)
		{
			$result = mysql_query("SELECT UserId, FirstName, Password FROM usertb WHERE UserId='".$_SESSION['user_id']."'");
			if(mysql_num_rows($result) == 1)
			{
				$rows = mysql_fetch_array($result);
				$result1 = mysql_query("SELECT OLD_PASSWORD('".$old_pass."')");
				$rows1 = mysql_fetch_array($result1);
				if($rows['Password'] == $rows1[0])
				{
					$result2 = mysql_query("SELECT OLD_PASSWORD('".$new_pass."')");
					$rows2 = mysql_fetch_array($result2);
					mysql_query("UPDATE usertb SET Password='".$rows2[0]."' WHERE UserId=".$_SESSION['user_id']);
					session_destroy();
				}
				else
				{
					$errors .= "Mật khẩu củ không đúng.";
				}
			}
			else
			{
				$errors .= "Tài khoản không tồn tại.";
			}
		}
		else
		{
			$errors .= "Mật khẩu mới hai lần không giống nhau.";
		}
	}
	else
	{
		$errors .= "Không được để trống các dữ liệu.";
	}
	echo $errors;
}
?>