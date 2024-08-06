<?
$hostname = "mysql5.hosting.paran.com"; 
$username = "rpkim";
$password = "rpkim0514"; 
$databasename = "rpkim_db"; 

$connect = mysql_connect($hostname,$username,$password);
mysql_select_db($databasename);
?>