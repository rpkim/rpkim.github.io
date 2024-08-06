<?
//$hostname = "mysql5.hosting.paran.com"; 
//$username = "rpkim";
//$password = "rpkim0514"; 
//$databasename = "rpkim_db"; 

//$connect = mysql_connect($hostname,$username,$password);
//mysql_select_db($databasename);
?>

<?
    $filename = "1.csv";
    $handle = fopen($filename,"r");
    while (($data = fgetcsv($handle,100000,",")) != FALSE)
	{
		
		$sql = "INSERT INTO badstore (`id`,`storename`,`addr`,`context`,`link`,`year`,`title` ) VALUES (".$data[0].",'".$data[1]."','".$data[2]."','".$data[3]."','".$data[4]."','".$data[5]."','".$data[6]."')";
		
        $result = mysql_query($sql) or die ("잘못된 질의".$result);
	}
?>