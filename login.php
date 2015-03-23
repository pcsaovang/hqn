<?php
session_start();
if(isset($_POST['action']) && $_POST['action'] == 'login')
{
	$errors = "";
	include "db.php";
	$uname = mysql_real_escape_string($_POST['txtuname']);
	$upw = mysql_real_escape_string($_POST['txtpw']);
	if(!empty($uname) && !empty($upw))
	{
		$result_uname = mysql_query("SELECT UserId, UserName, Password FROM usertb WHERE UserName='".$uname."' AND UserType = 3");
		if(mysql_num_rows($result_uname) == 1)
		{
			$rows_uname = mysql_fetch_array($result_uname);
			$result_upw = mysql_query("SELECT OLD_PASSWORD('".$upw."')");
			$rows_upw = mysql_fetch_array($result_upw);
			if($rows_uname['Password'] == $rows_upw[0])
			{
				$_SESSION['user_id'] = $rows_uname['UserId'];
				$_SESSION['user_name'] = $rows_uname['UserName'];
				//header("Location: index.php");
				//$errors .= "Dang nhap thanh cong";
			}
			else
			{
				$errors .= "Đăng nhập thất bại, sai mật khẩu.";
			}
		}
		else
		{
			$errors .= "Đăng nhập thất bại, tài khoản không tồn tại.";
		}
	}
	elseif(empty($uname))
	{
		$errors .= "Tài khoản không được để trống.";
	}
	elseif(empty($upw))
	{
		$errors .= "Mật khẩu không được để trống.";
	}
	echo $errors;
}
?>