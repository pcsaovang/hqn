<?php
session_start();
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
{
include "db.php";

if(isset($_GET['update']) && $_GET['update'] == true)
{
	$userid = mysql_real_escape_string($_GET['UserId']);
	$debit = intval($_GET['Debit']);
	$moneypaid = intval($_GET['MoneyPaid']);
	$remainmoney = intval($_GET['RemainMoney']);
	$active = $_GET['Active'];

	$sql = "UPDATE usertb SET Debit=".$debit.", MoneyPaid=".$moneypaid.", RemainMoney=".$remainmoney.", Active=".$active." WHERE UserId=".$userid;
	$result = mysql_query($sql) or die("MySQL Error: ".$mysql_error());
	echo $result;
}
elseif(isset($_GET['delete']) && $_GET['delete'] == true)
{
	$userid = mysql_real_escape_string($_GET['UserId']);
	mysql_query("DELETE FROM usertb WHERE UserId=".$userid);
	mysql_query("DELETE FROM paymenttb WHERE UserId=".$userid);
	mysql_query("DELETE FROM servicedetailtb WHERE UserId=".$userid);
	mysql_query("DELETE FROM systemlogtb WHERE UserId=".$userid);
	mysql_query("DELETE FROM webhistorytb WHERE UserId=".$userid);
	echo "Deleted!";
}
else
{
	$pagenum = isset($_GET['pagenum']) ? $_GET['pagenum'] : 0;
	$pagesize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 0;
	$start = $pagenum * $pagesize;
	$query = "SELECT SQL_CALC_FOUND_ROWS * FROM usertb WHERE UserType=2 OR UserType=4 LIMIT $start, $pagesize";
	$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
	$sql = "SELECT FOUND_ROWS() AS `found_rows`;";
	$rows = mysql_query($sql);
	$rows = mysql_fetch_assoc($rows);
	$total_rows = $rows['found_rows'];
	$filterquery = "";

	// filter data.
	if (isset($_GET['filterscount']))
	{
		$filterscount = $_GET['filterscount'];
		
		if ($filterscount > 0)
		{
			$where = " WHERE (UserType=2 OR UserType=4) AND (";
			$tmpdatafield = "";
			$tmpfilteroperator = "";
			for ($i=0; $i < $filterscount; $i++)
		    {
				// get the filter's value.
				$filtervalue = $_GET["filtervalue" . $i];
				// get the filter's condition.
				$filtercondition = $_GET["filtercondition" . $i];
				// get the filter's column.
				$filterdatafield = $_GET["filterdatafield" . $i];
				// get the filter's operator.
				$filteroperator = $_GET["filteroperator" . $i];
				
				if ($tmpdatafield == "")
				{
					$tmpdatafield = $filterdatafield;
				}
				else if ($tmpdatafield <> $filterdatafield)
				{
					$where .= ")AND(";
				}
				else if ($tmpdatafield == $filterdatafield)
				{
					if ($tmpfilteroperator == 0)
					{
						$where .= " AND ";
					}
					else $where .= " OR ";
				}
				
				// build the "WHERE" clause depending on the filter's condition, value and datafield.
				switch($filtercondition)
				{
					case "NOT_EMPTY":
					case "NOT_NULL":
						$where .= " " . $filterdatafield . " NOT LIKE '" . "" ."'";
						break;
					case "EMPTY":
					case "NULL":
						$where .= " " . $filterdatafield . " LIKE '" . "" ."'";
						break;
					case "CONTAINS_CASE_SENSITIVE":
						$where .= " BINARY  " . $filterdatafield . " LIKE '%" . $filtervalue ."%'";
						break;
					case "CONTAINS":
						$where .= " " . $filterdatafield . " LIKE '%" . $filtervalue ."%'";
						break;
					case "DOES_NOT_CONTAIN_CASE_SENSITIVE":
						$where .= " BINARY " . $filterdatafield . " NOT LIKE '%" . $filtervalue ."%'";
						break;
					case "DOES_NOT_CONTAIN":
						$where .= " " . $filterdatafield . " NOT LIKE '%" . $filtervalue ."%'";
						break;
					case "EQUAL_CASE_SENSITIVE":
						$where .= " BINARY " . $filterdatafield . " = '" . $filtervalue ."'";
						break;
					case "EQUAL":
						$where .= " " . $filterdatafield . " = '" . $filtervalue ."'";
						break;
					case "NOT_EQUAL_CASE_SENSITIVE":
						$where .= " BINARY " . $filterdatafield . " <> '" . $filtervalue ."'";
						break;
					case "NOT_EQUAL":
						$where .= " " . $filterdatafield . " <> '" . $filtervalue ."'";
						break;
					case "GREATER_THAN":
						$where .= " " . $filterdatafield . " > '" . $filtervalue ."'";
						break;
					case "LESS_THAN":
						$where .= " " . $filterdatafield . " < '" . $filtervalue ."'";
						break;
					case "GREATER_THAN_OR_EQUAL":
						$where .= " " . $filterdatafield . " >= '" . $filtervalue ."'";
						break;
					case "LESS_THAN_OR_EQUAL":
						$where .= " " . $filterdatafield . " <= '" . $filtervalue ."'";
						break;
					case "STARTS_WITH_CASE_SENSITIVE":
						$where .= " BINARY " . $filterdatafield . " LIKE '" . $filtervalue ."%'";
						break;
					case "STARTS_WITH":
						$where .= " " . $filterdatafield . " LIKE '" . $filtervalue ."%'";
						break;
					case "ENDS_WITH_CASE_SENSITIVE":
						$where .= " BINARY " . $filterdatafield . " LIKE '%" . $filtervalue ."'";
						break;
					case "ENDS_WITH":
						$where .= " " . $filterdatafield . " LIKE '%" . $filtervalue ."'";
						break;
				}
								
				if ($i == $filterscount - 1)
				{
					$where .= ")";
				}
				
				$tmpfilteroperator = $filteroperator;
				$tmpdatafield = $filterdatafield;			
			}
			// build the query.
			$query = "SELECT * FROM usertb ".$where;
			$filterquery = $query;
			$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
			$sql = "SELECT FOUND_ROWS() AS `found_rows`;";
			$rows = mysql_query($sql);
			$rows = mysql_fetch_assoc($rows);
			$new_total_rows = $rows['found_rows'];		
			$query = "SELECT * FROM usertb ".$where." LIMIT $start, $pagesize";		
			$total_rows = $new_total_rows;	
		}
	}

	if (isset($_GET['sortdatafield']))
	{
		$sortfield = $_GET['sortdatafield'];
		$sortorder = $_GET['sortorder'];
		
		if ($sortorder != '')
		{
			if ($_GET['filterscount'] == 0)
			{
				if ($sortorder == "desc")
				{
					$query = "SELECT * FROM usertb WHERE UserType=2 ORDER BY" . " " . $sortfield . " DESC LIMIT $start, $pagesize";
				}
				else if ($sortorder == "asc")
				{
					$query = "SELECT * FROM usertb WHERE UserType=2 ORDER BY" . " " . $sortfield . " ASC LIMIT $start, $pagesize";
				}
			}
			else
			{
				if ($sortorder == "desc")
				{
					$filterquery .= " ORDER BY" . " " . $sortfield . " DESC LIMIT $start, $pagesize";
				}
				else if ($sortorder == "asc")	
				{
					$filterquery .= " ORDER BY" . " " . $sortfield . " ASC LIMIT $start, $pagesize";
				}
				$query = $filterquery;
			}		
		}
	}

	$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
	$orders = null;
	$Active = "";
	// get data and store in a json array
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$result_payment = mysql_query("SELECT MAX(VoucherDate) AS OldPay FROM paymenttb WHERE UserId=".$row['UserId']) or die("Loi: ".mysql_error());
		$row_payment = mysql_fetch_array($result_payment);
		$n1 = strtotime($row_payment[0]);
		$n2 = strtotime(date('Y-m-d'));
		$n3 = $n2 - $n1;
		$n3 = round($n3/60/60/24, PHP_ROUND_HALF_DOWN);
		if($n3 > 90) $c = "Rất lâu";
		else $c = $n3." ngày";

		$orders[] = array(
			'UserId'		=>	$row['UserId'],
			'FirstName'		=>	$row['FirstName'],
			'Debit'			=>	$row['Debit'],
			'MoneyPaid'		=>	$row['MoneyPaid'],
			'RemainMoney'	=>	$row['RemainMoney'],
			'PayOld'		=>	$c,
			'Active'		=>	$row['Active'],
			'RecordDate'	=>	$row['RecordDate']
		  );
	}
	$result_sum = mysql_query("SELECT SUM(Debit) AS TD, SUM(RemainMoney) AS TM FROM usertb");
	$row_total = mysql_fetch_array($result_sum);
	$data[] = array(
	   'TotalRows' => $total_rows,
	   'TotalDebit' => number_format($row_total['TD']),
	   'TotalMoney' => number_format($row_total['TM']),
	   'Rows' => $orders
	);
	echo json_encode($data);
}
}
else
{
	echo "<h1 align='center'>Truy cập bất hợp pháp hả?</h1>";
}
?>