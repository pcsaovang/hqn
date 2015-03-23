<?php
session_start();
$message = array();
if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] == '')
{
?>
<div id='home'>
	<div id='home-left'>
		<form action='#' id='frmlogin' class='frmlogin'>
		<table cellpadding='5' cellspacing='0' border='0'><tr>
			<td colspan='2'><div id='title-login'><h1>ĐĂNG NHẬP</h1></div></td></tr><tr>
			<td><label class='lbl-login'>Tài khoản: </label></td>
			<td><input type='text' name='txtuname' id='txtuname' /></td></tr><tr>
			<td><label class='lbl-login'>Mật khẩu: </label></td>
			<td><input type='password' name='txtpw' id='txtpw' /></td></tr><tr>
			<td colspan=2 align='center'><input id='btnlogin' type='submit' value='Đăng nhập' /></td></tr>
		</table>
		</form>
		<div id='errors'></div>
	</div>
	<div id='home-right'></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	var theme = "energyblue";
	$("#btnlogin").jqxButton({theme: theme});
	$("#frmlogin").jqxValidator({
		rules: [
			{input: '#txtuname', message: ' ', action: 'keyup, blur', rule: 'required'},
			{input: '#txtpw', message: ' ', action: 'keyup, blur', rule: 'required'}
		],
		theme: theme,
		hintType: 'label',
	});
	$("#btnlogin").click(function(){
		$.ajax({
			type: 'POST',
			url: 'login.php',
			data: $("#frmlogin").serialize() + "&action=login",
			success: function(data){
				if(data.length > 0){
					$("#errors").html(data);
				}
				else
				{
					window.location.href='index.php';
				}
			}
		});
		return false;
	});
})
</script>
<?php
}
else
{
	$message['error_login'] = "Chao mung ban den voi he thong website quan ly tiem net";
}
echo $message['error_login'];
?>
<!--
<div id="wxpcamdiv" class="wxpcamdiv" style="width: 320px;">
	<div id="wxpdragcam" class="wxpdragme">Live View</div>
	<object type="application/x-shockwave-flash" width="320" height="240" data="flashMJPEG.swf">
		<param name="movie" value="flashMJPEG.swf?webcam=cam_1.jpg&refresh=50&connect=&offline=&transtype=Fade&bgcolor=#FFFFFF&txtcolor=#808080" />
		<param name="loop" value="false" />
		<param name="menu" value="false" />
		<param name="quality" value="best" />
		<param name="scale" value="noscale" />
		<param name="salign" value="lt" />
		<param name="wmode" value="opaque" />
	</object>
</div>
-->
