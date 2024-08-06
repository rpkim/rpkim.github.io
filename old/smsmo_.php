<meta http-equiv=Content-Type content=text/html; charset=utf-8>
<?

$hostname = "mysql3.hosting.paran.com"; 
$username = "mbs0485";
$password = "b12345"; 

$databasename = "mbs0485_db"; 


$connect = mysql_connect($hostname,$username,$password);

mysql_select_db($databasename);

 //쿼리하기 이전에. utf8로 설정해준다.
mysql_query("SET NAMES 'utf8'");


if($_GET['msg'] == '')
{
	echo "msg get error";
	return;
}
if($_GET['from'] == '')
{
	echo "from get error";
	return;
}

$sql = "insert into smsmo(receive_time,sms_from,msg) values (NOW(),'".$_GET['from']."','".$_GET['msg']."')";
$result = mysql_query($sql) or die ("ERROR");
echo "OK";
?>

