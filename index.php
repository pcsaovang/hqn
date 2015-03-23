<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="vi" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Quản lý hội viên CSM</title>
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />
	<link rel="stylesheet" href="css/jqx.base.css" type="text/css" />
	<link rel="stylesheet" type="text/css" href="css/jqx.energyblue.css" media="screen">
	<link rel="stylesheet" type="text/css" href="css/style.css" media="screen">

	<script type="text/javascript" src="script/jquery-1.10.2.js"></script>
	<script type="text/javascript" src="script/jqxcore.js"></script>
	<script type="text/javascript" src="script/jqxbuttons.js"></script>
	<script type="text/javascript" src="script/jqxscrollbar.js"></script>
	<script type="text/javascript" src="script/jqxmenu.js"></script>
	<script type="text/javascript" src="script/jqxgrid.js"></script>
	<script type="text/javascript" src="script/jqxgrid.selection.js"></script>
	<script type="text/javascript" src="script/jqxgrid.filter.js"></script>
	<script type="text/javascript" src="script/jqxgrid.sort.js"></script>
	<script type="text/javascript" src="script/jqxdata.js"></script>
	<script type="text/javascript" src="script/jqxlistbox.js"></script>
	<script type="text/javascript" src="script/jqxgrid.pager.js"></script>
	<script type="text/javascript" src="script/jqxdropdownlist.js"></script>
	<script type="text/javascript" src="script/jqxwindow.js"></script>
	<script type="text/javascript" src="script/jqxnumberinput.js"></script>
	<script type="text/javascript" src="script/jqxinput.js"></script>
	<script type="text/javascript" src="script/generatedata.js"></script>
	<script type="text/javascript" src="script/gettheme.js"></script>
	<script type="text/javascript" src="script/globalize.js"></script>
	<script type="text/javascript" src="script/jqxcalendar.js"></script>
	<script type="text/javascript" src="script/jqxdatetimeinput.js"></script>
	<script type="text/javascript" src="script/jqxtooltip.js"></script>
	<script type="text/javascript" src="script/jqxcombobox.js"></script>
	<script type="text/javascript" src="script/jqxgrid.edit.js"></script>
	<script type="text/javascript" src="script/jqxcheckbox.js"></script>
	<script type="text/javascript" src="script/jqxgrid.aggregates.js"></script>
	<script type="text/javascript" src="script/jqxvalidator.js"></script>
	<script type="text/javascript" src="script/jqxexpander.js"></script>
</head>
<body>
<div id="wrapper">
	<div id="head">
		<div class="hdr_abslnklst" id="menu"><?php include "menu.php"; ?></div>
	</div>
	<div class="top_outer">
		<div class="top_inner">
			<div class="top_center"></div>
		</div>
	</div>
	<div class="page_outer">
		<div class="page_inner">
			<div id="page">
				<div id="nav"><?php include "navbar.php" ?></div>
				<div class="content">
					<div id="container">
						<?php
						if(!isset($_GET['page']))
						{
							include "member.php";
						}
						else
						{
							$page = $_GET['page'].".php";
							if(is_file($page)) include "$page";
							else include "member.php";
						}
echo <<<end
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="page_foot">
		<div class="page_foot_inner">
			<div id="foot">
				<div style="width: 300px; float: left; color: #c9cacb; margin-top: 10px;">
					<i>Copyright &copy 2013-2014 by Tran Phong</i><p>Mã nguồn được share tại <a href="http://hqn.vn" target="_blank" style="color: red;">http://hqn.vn</a></p>
				</div>
				<div style="width: 640px; float: right; text-align: right; color: #c9cacb; margin-top: 10px;">
					Mọi chi tiết liên hệ YM: saovang_pc hoặc 
					<a href="http://forum.hqn.vn/member.php?6528-pcsaovang" target="_blank" style="color: red;">tại đây</a>
					<p>Cấm thương mại hóa mã nguồn website này khi chưa được sự đồng ý của tác giả.</p>
				</div>
			</div>
		</div>
	</div>
end;
	?>
</div>
</body>
</html>