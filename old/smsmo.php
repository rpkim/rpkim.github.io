<meta http-equiv=Content-Type content=text/html; charset=utf-8>
<?

$hostname = "222.122.140.136"; 
$username = "mbs6116";
$password = "mbs6116@naver"; 

$databasename = "mbs6116"; 


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

