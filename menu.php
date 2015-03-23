<?php //session_start();
if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
{
//$wcadmin = $_SESSION['user_name'];
?>
<ul class='linklist'>
	<li class='first'><span class='abstxt'><strong style='color: #CDCBCE;'>Welcome: </strong> <?php echo $_SESSION['user_name']; ?></span></li>
	<li class='first'><a href='index.php'>Trang chủ</a></li>
	<li class='first'><a href='?page=payments' class='member'>Nhật ký giao dịch</a></li>
	<li class='first'><a href='?page=webhistorys' class='webhistory'>Nhật ký web</a></li>
	<li class='first'><a href='?page=serverlog' class='serverlog'>Nhật ký máy chủ</a></li>
	<li class='first'><a href='?page=sysclient' class='sysclient'>Quản lý máy trạm</a></li>
	<li class='first'><a href='#' class='changepass'>Đổi mật khẩu</a></li>
	<li class='last'><a href='?page=logout'>Thoát</a></li>
</ul>
<script type="text/javascript">
$(document).ready(function () {
	var theme = "energyblue";
	$(".changepass").click(function(event){
		$("#popupChangepass").jqxWindow({position: 'center'});
        $("#popupChangepass").jqxWindow('show');
		return false;
	});

	$("#btnchange").jqxButton({width: 150, height: 25, theme: theme});
	$("#btncancel").jqxButton({width: 150, height: 25, theme: theme});
	$("#frmchange").jqxValidator({
		hintType: 'label',
		rules: [
			{input: '#txtoldpass', message: ' ', action: 'keyup, blur', rule: 'required'},
			{input: '#txtnewpass', message: ' ', action: 'keyup, blur', rule: 'required'},
			{input: '#txtverypass', message: ' ', action: 'keyup, blur', rule: 'required'},
			{input: '#txtverypass', message: ' ', action: 'keyup, focus', rule: function(input, commit){
				if(input.val() == $('#txtnewpass').val()) return true;
				return false;
			}}
		]
	});
	$("#btnchange").click(function(){
		$.ajax({
			type: 'POST',
			url: 'changepass.php',
			data: $("#frmchange").serialize(),
			success: function(data){
				if(data.length > 0){
					$("#status").html(data);
				}
				else
				{
					$("#popupChangepass").jqxWindow('closeAll');
					window.location.href='index.php';
				}
			}
		});
	});
	$("#popupChangepass").jqxWindow({width:400, height: 220, resizable: false, theme: theme, isModal: true, autoOpen: false, cancelButton: $("#btncancel"), modalOpacity: 0.9});
})
</script>
<div id="popupChangepass">
     <div>Đổi mật khẩu Administrator</div>
     <div style="overflow: hidden; text-align: left; margin-top:10px;">
		<form id="frmchange" action="#">
			<table width="100%" border="0" cellpadding="3">
				<tr>
					<td>Tài khoản:</td>
					<td><div style="font-weight:bold; color: red;"><?php echo $_SESSION['user_name'] ?></div></td>
				</tr>
				<tr>
					<td>Mật khẩu củ: </td>
					<td><input name="txtoldpass" type="password" id="txtoldpass" size="30" /></td>
				</tr>
				<tr>
					<td>Mật khẩu: </td>
					<td><input name="txtnewpass" type="password" id="txtnewpass" size="30" /></td>
				</tr>
				<tr>
					<td>Xác nhận mật khẩu: </td>
					<td><input name="txtverypass" type="password" id="txtverypass" size="30" /></td>
				</tr>
				<tr>
					<td colspan=2><div style='min-height: 15px; color: red;' id='status'></div></td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input type="button" id="btnchange" value="Đổi mật khẩu" />
						<input type="button" id="btncancel" value="Hủy bỏ" />
					</td>
				</tr>
			</table>
		</form>
     </div>
</div>
<?php } ?>