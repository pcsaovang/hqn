<?php //session_start(); ?>
<div style='border-bottom: 1px solid #E9EDEA; padding-bottom: 5px; min-height: 20px; font-style: italic;'>
	<div id='lbltime' style="float: left"></div>
	<script type='text/javascript'>
		function refrClock()
		{
			var d=new Date();
			var s=d.getSeconds();
			var m=d.getMinutes();
			var h=d.getHours();
			var day=d.getDay();
			var date=d.getDate();
			var month=d.getMonth();
			var year=d.getFullYear();
			var days=new Array('Chủ nhật,','Thứ 2,','Thứ 3,','Thứ 4,','Thứ 5,','Thứ 6,','Thứ 7,');
			var months=new Array('1','2','3','4','5','6','7','8','9','10','11','12');
			var am_pm;
			if (s<10) {s='0' + s}
			if (m<10) {m='0' + m}
			if (h>12) 
			{
			h-=12;am_pm = 'PM';
			}
			else
			{
			am_pm='AM';
			}
			if (h<10) {h='0' + h}
			$('#lbltime').html(days[day] + ' ' + date + '-' + months[month] + '-' + year + ', ' + h + ':' + m + ':' + s + ' ' + am_pm);
			setTimeout('refrClock()',1000);
		}
		refrClock();
	</script>
</div>
<?php
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
{
?>
<div style="text-align: center; margin-top: 20px; font-size: 20px; font-style: bold; color: red;">
	<?php
	if(isset($_GET['page']) && $_GET['page'] == 'payments') echo "NHẬT KÝ GIAO DỊCH";
	elseif(isset($_GET['page']) && $_GET['page'] == 'webhistorys') echo "NHẬT KÝ WEBSITE";
	elseif(isset($_GET['page']) && $_GET['page'] == 'serverlog') echo "NHẬT KÝ MÁY CHỦ";
	elseif(isset($_GET['page']) && $_GET['page'] == 'sysclient') echo "QUẢN LÝ MÁY TRẠM";
	else echo "DANH SÁCH HỘI VIÊN";
	?>
</div>
<?php }else{ ?>
<div style="text-align: center; margin-top: 20px; font-size: 20px; font-style: bold; color: red;">
	WEBSITE QUẢN LÝ HỘI VIÊN
</div>
<?php } ?>