<?php
function getidbyname($name)
{
	$result = mysql_query("SELECT UserId, FirstName FROM usertb WHERE FirstName='".$name."'");
	$rows = mysql_fetch_array($result);
	return $rows[0];
}
?>