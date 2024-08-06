<?
$sql = "SELECT count(*) FROM `statistic` WHERE date=CURDATE()";
$result = mysql_query($sql) or die ("잘못된 질의");
$tot=mysql_num_rows($result);	//행의 갯수
$rows=mysql_fetch_array($result);

//한번도 오늘 접속한 적이 없으면
if($rows[0] == 0)
{
	$sql = "INSERT INTO `rpkim_db`.`statistic` (`count` ,`date` ) VALUES ( '0', CURDATE( ) )";
}
else
{
	$sql = "UPDATE `rpkim_db`.`statistic` SET `count` = (`count`+1) WHERE `statistic`.`date` = CURDATE( )";
}
$result = mysql_query($sql) or die ("잘못된 질의");

	$ip = getenv("REMOTE_ADDR"); 
	$sql1 = "INSERT INTO `rpkim_db`.`visiter` (`ip`,`date`) VALUES ('$ip',CURDATE())";
	mysql_query($sql1) or die ("잘못된 질의");


?>