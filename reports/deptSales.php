<?php
/*******************************************************************************

    Copyright 2007 People's Food Co-op, Portland, Oregon.

    This file is part of Fannie.

    IS4C is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    IS4C is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    in the file license.txt along with IS4C; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*********************************************************************************/

// include($_SERVER["DOCUMENT_ROOT"].'/src/functions.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/src/mysql_connect.php');

if(isset($_GET['sort'])){
	if(isset($_GET['XL'])){
		header("Content-Disposition: inline; filename=deptSales.xls");
		header("Content-Description: PHP3 Generated Data");
		header("Content-type: application/vnd.ms-excel; name='excel'");
	}
}
?>
<html>
<head>
<title>Department Movement Report</title>
</head>
<?
?>

<html>
<head>
<title>Department Movement Report</title>
</head>
<?
if(isset($_POST['submit'])){
	foreach ($_POST AS $key => $value) {
		$$key = $value;
	}	
}else{
	foreach ($_GET AS $key => $value) {
		$$key = $value;
	}
}
?>
<body>
<?php		
$today = date("F d, Y");	
$_SESSION['deptArray'] = 0;

if (isset($_GET['allDepts']) && !isset($_GET['dept'])) {
	$_SESSION['deptArray'] = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,40";
	$arrayName = "ALL DEPARTMENTS";
}
else if (isset($_GET['dept'])) {
	if(is_array($_GET['dept'])) {
		$_SESSION['deptArray'] = implode(",",$_GET['dept']);
		$arrayName = $_SESSION['deptArray'];
	} 
}
else {
	$allDepts = 0;
}

echo "Report sorted by ";
echo $sort;

if(isset($lowLimit)) {
	echo " and LOW LIMIT on ";
}
else {
	echo " on ";
}
echo "</br>";
echo $today;
echo "</br>";
echo "From ";
print $date1;
echo " to ";
print $date2;
echo "</br>";
echo "    Department range: ";
print $arrayName;
echo "</br></br>";

// if(!isset($_GET['XL'])){
// 	echo "<p><a href='deptSales.php?XL=1&sort=$sort&date1=$date1&date2=$date2&deptStart=$deptStart&deptEnd=$deptEnd&pluReport=$pluReport&order=$order'>Dump to Excel Document</a></p>";	
// } 
	
$date2a = $date2 . " 23:59:59";
$date1a = $date1 . " 00:00:00";	
$_SESSION['sort'] = $_GET['sort'];
$sort = $_SESSION['sort'];
	
if($sort == 'Department'){		
	$order = "t.department";
} elseif($sort == 'PLU') {	
	$order = "t.upc";
} elseif($sort == 'Qty') {
	$order = 'SUM(t.quantity) DESC';
} elseif($sort == 'Sales') {
	$order = 'SUM(t.total) DESC';
} elseif($sort == 'Subdepartment') {
	$order = 'p.subdept';
}

if(isset($inUse)) {
	$inUseA = "AND p.inUse = 1";
} else {
	$inUseA = "AND p.inUse IN (0,1)";
}

// $query4 - Items Below Low Limit
$query4 = "SELECT DISTINCT 
	p.upc AS PLU,
	p.description AS Description,
	p.department AS Dept,
	p.subdept AS Subdept,
	p.scale as Scale,
	p.inventory AS Qty,
	p.lowlimit AS Lowlimit
	FROM michell3_is4c_op.products p
	WHERE p.inventory <= p.lowlimit
	AND p.department IN (".$_SESSION['deptArray'].") 
	$inUseA
	GROUP BY p.upc
	ORDER BY p.lowlimit - p.inventory;";

$result4 = mysql_query($query4);

echo "<table border=1 cellpadding=3 cellspacing=3>";
	
if (!$result4) {
	$message1  = 'Invalid query: ' . mysql_error() . "\n";
	$message1 .= 'Whole query: ' . $query4;
		die($message1);
}

if (mysql_num_rows($result4) > 0) {
	echo "<p><b><u><font color=\"red\" >WARNING: Items Below Low Limit:</font></u></b></p>";
	echo "<tr><td>UPC</td><td>Description</td><td>Dept</td><td>Subdept</td><td>Scale</td><td>Qty</td><td>Limit</td></tr>";

	while ($myrow1 = mysql_fetch_row($result4)) { //create array from query
		printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>\n",$myrow1[0], $myrow1[1],$myrow1[2],$myrow1[3],$myrow1[4],$myrow1[5],$myrow1[6]);
	}

	echo "</table>\n";
}

if (isset($salesTotal)) {
	echo "<p><b><u>Total Sales:</u></b></p>";
	
	//$quesry1 - Total sales
	$query1 = "SELECT d.dept_name,ROUND(SUM(t.total),2) AS total
		FROM michell3_is4c_op.departments AS d, michell3_is4c_log.dtransactions AS t
		WHERE d.dept_no = t.department
		AND t.datetime >= '$date1a' AND t.datetime <= '$date2a'
		AND t.department IN(" . $_SESSION['deptArray'] . ")
		AND t.trans_status <> 'X'
		AND t.emp_no <> 9999
		GROUP BY t.department";
				
	$result1 = mysql_query($query1);
	
	echo "<table>\n"; //create table
	echo "<tr><td>";
	echo "<b>Department</b></td><td>";
	echo "<b>Total Sales</b></td></tr>";

	if (!$result1) {
		$message  = 'Invalid query: ' . mysql_error() . "\n";
		$message .= 'Whole query: ' . $query1;
		die($message);
	}

	while ($myrow = mysql_fetch_row($result1)) { //create array from query
		printf("<tr><td>%s</td><td>%s</td></tr>\n",$myrow[0], $myrow[1]);
	} 
			
	echo "</table>\n<br />";
}
			
if(isset($openRing)) {
	echo "<p><b><u>Open Ring Sales:</u></b></p>";
	
	//$query2 - Total open dept. ring
	$query2 = "SELECT d.dept_name AS Department,ROUND(SUM(t.total),2) AS open_dept
		FROM michell3_is4c_op.departments AS d, michell3_is4c_log.dtransactions AS t 
		WHERE t.datetime >= '$date1a' AND t.datetime <= '$date2a' 
		AND t.trans_status <> 'X' 
		AND t.trans_type = 'D' 
		AND t.emp_no <> 9999 
		AND t.department IN(".$_SESSION['deptArray'].")
		AND d.dept_no = t.department
		GROUP BY t.department";

	$result2 = mysql_query($query2);
	
	echo "<table>\n"; //create table
	echo "<tr><td>";
	echo "<b>Department</b></td><td>";
	echo "<b>Open Ring</b></td></tr>";

	if (!$result2) {
		$message  = 'Invalid query: ' . mysql_error() . "\n";
		$message .= 'Whole query: ' . $query2;
				die($message);
	}

	while ($myrow = mysql_fetch_row($result2)) { //create array from query
  		printf("<tr><td>%s</td><td>%s</td></tr>\n",$myrow[0], $myrow[1]);						
	}
	
	echo "</table>\n<br />";

} 
			
if(isset($pluReport)){
	echo "<p><b><u>Sales Per Plu:</u></b></p>";
	
	// $query3 - Sales per PLU
	$query3 = "SELECT DISTINCT 
		p.upc AS PLU,
		p.description AS Description,
		ROUND(t.unitPrice, 2) AS Price,
		p.department AS Dept,
		p.subdept AS Subdept,
		SUM(t.quantity) AS Qty,
		ROUND(SUM(t.total),2) AS Total,
		p.scale as Scale
		FROM michell3_is4c_log.dtransactions t, michell3_is4c_op.products p
		WHERE t.upc = p.upc
		AND t.department IN(".$_SESSION['deptArray'].") 
		AND t.datetime >= '$date1a' AND t.datetime <= '$date2a' 
		AND t.emp_no <> 9999
		AND t.trans_status <> 'X'
		AND t.upc NOT LIKE '%DP%'
		$inUseA
		GROUP BY t.upc
		ORDER BY $order";

	$result3 = mysql_query($query3);

	echo "<table border=1 cellpadding=3 cellspacing=3>";
	echo "<tr><td>UPC</td><td>Description</td><td>Price</td><td>Dept</td><td>Subdept</td><td>Qty</td><td>Sales</td><td>Scale</td></tr>";
	
	if (!$result3) {
		$message  = 'Invalid query: ' . mysql_error() . "\n";
		$message .= 'Whole query: ' . $query3;
			die($message);
	}

	while ($myrow = mysql_fetch_row($result3)) { //create array from query
		printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>\n",$myrow[0], $myrow[1],$myrow[2],$myrow[3],$myrow[4],$myrow[5],$myrow[6],$myrow[7]);
	}

	echo "</table>\n<br />";

}

?>
</body>
</html>
