<?
$sql = "SELECT count(*) FROM `statistic` WHERE date=CURDATE()";
$result = mysql_query($sql) or die ("�߸��� ����");
$tot=mysql_num_rows($result);	//���� ����
$rows=mysql_fetch_array($result);

//�ѹ��� ���� ������ ���� ������
if($rows[0] == 0)
{
	$sql = "INSERT INTO `rpkim_db`.`statistic` (`count` ,`date` ) VALUES ( '0', CURDATE( ) )";
}
else
{
	$sql = "UPDATE `rpkim_db`.`statistic` SET `count` = (`count`+1) WHERE `statistic`.`date` = CURDATE( )";
}
$result = mysql_query($sql) or die ("�߸��� ����");

	$ip = getenv("REMOTE_ADDR"); 
	$sql1 = "INSERT INTO `rpkim_db`.`visiter` (`ip`,`date`) VALUES ('$ip',CURDATE())";
	mysql_query($sql1) or die ("�߸��� ����");


?>