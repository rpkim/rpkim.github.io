<meta http-equiv=Content-Type content=text/html; charset=utf-8>
<?
/*

<!--
 ajou_map
PK : map_id
     map_name


 ajou_point
PK : point_id
FK : map_id
     t
	 latitude
	 longtitude
	 description


  ajou_movement
 PK : movement_id
 FK : map_id


 ajou_movement_point
 PK : movement_point_id
 FK : movement_id
      t
	  latitude
	  longtitude
	  description
-->

*/
class map
{
	public $id,$points,$movements;

	public function map($a,$b,$c)
	{
		$this->id = (int)$a;
		$this->points = $b;
		$this->movements = $c;
	}
}

//point 구조체
class ajou_point
{
	public $t,$latitude,$logtitude,$desc;
	
	public function ajou_point($a,$b,$c,$d)
	{
		$this->t = (int)$a;
		$this->latitude = (float)$b;
		$this->logtitude = (float)$c;
		$this->desc = $d;
	}
}
?>

<?
//database setup
$hostname = "mysql5.hosting.paran.com"; 
$username = "rpkim";
$password = "rpkim0514"; 
$databasename = "rpkim_db"; 
$connect = mysql_connect($hostname,$username,$password);
mysql_select_db($databasename);

//

//쿼리하기 이전에. utf8로 설정해준다.
mysql_query("SET NAMES 'utf8'");



//get data가 없으면 reture 해준다.
/*if($_GET['map_id'] == '')
{
	echo "msg get error";
	return;
}

$id =$_GET['map_id'];
*/

$map_list = array();

$sql2 = "SELECT distinct map_id FROM ajou_map";
$result2 = mysql_query($sql2) or die ("ERROR");
$tot2=mysql_num_rows($result2);	//행의 갯수

for($k = 0 ; $k < $tot2 ; $k++)
{
	$rows2 = mysql_fetch_array($result2);
	$id = $rows2[0];

	//get points data
	$sql = "SELECT t, latitude, longtitude, description FROM ajou_point WHERE map_id='".$id."'";
	$result = mysql_query($sql) or die ("ERROR");
	$tot=mysql_num_rows($result);	//행의 갯수

	$ajou_point_list = array();
	for($i = 0 ; $i < $tot ; $i++)
	{
		$rows=mysql_fetch_array($result);
		$pointData = new ajou_point($rows[0],$rows[1],$rows[2],$rows[3]);
		array_push($ajou_point_list,$pointData);
	}

	//get movement_id_list
	$sql = "select movement_id from ajou_movement where map_id='".$id."'";
	$result = mysql_query($sql) or die ("ERROR");
	$tot=mysql_num_rows($result);	//행의 갯수


	$ajou_movement_list = array();
	for($i = 0 ; $i < $tot ; $i++)
	{
		$rows=mysql_fetch_array($result);
		$movement_id=$rows[0];

	//query_2
		$sql1 = "select t,latitude,longtitude,description from ajou_movement_point where movement_id='".$movement_id."'";

		$result1 = mysql_query($sql1) or die("Error");
		$tot1=mysql_num_rows($result1);

		$ajou_movement_point_list = array();
		for($j=0;$j<$tot1;$j++)
		{
			$rows1=mysql_fetch_array($result1);
			$movement_point = new ajou_point($rows1[0],$rows1[1],$rows1[2],$rows1[3]);
			
			//echo json_encode($movement_point);

			array_push($ajou_movement_point_list,$movement_point);
		}

		array_push($ajou_movement_list,$ajou_movement_point_list);


	}

$map = new map($id,$ajou_point_list,$ajou_movement_list);

array_push($map_list ,$map);
}


echo json_encode($map_list);





//$sql = "SELECT b.t, b.latitude, b.longtitude, b.description FROM ajou_movement a ,ajou_movement_point b WHERE a.map_id ='".$id."' and a.movement_id=b.movement_id";
//$result = mysql_query($sql) or die ("ERROR");



?>

