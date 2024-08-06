<?
include "json.php";

$xpos = $_GET['xPos'];
$ypos = $_GET['yPos'];
$xpos1 = $_GET['xPos1'];
$ypos1 = $_GET['yPos1'];

$from = $_GET['from'];
$to = $_GET['to'];

$test = $_GET['test'];

$request = 'http://map.naver.com/findroute2/findCarRoute.nhn?via=&call=route2&output=json&car=0&mileage=12.4&start='.$xpos.','.$ypos.','.urlencode($from).'&destination='.$xpos1.','.$ypos1.','.urlencode($to).'&search=5';

if($test == "1")
{
	$request = 'http://map.naver.com/findroute2/findCarRoute.nhn?via=&call=route2&output=json&start=127.028513,37.263475,%EA%B2%BD%EA%B8%B0%EB%8F%84%20%EC%88%98%EC%9B%90%EC%8B%9C&destination=126.799975,37.480076,%EA%B2%BD%EA%B8%B0%EB%8F%84%20%EB%B6%80%EC%B2%9C%EC%8B%9C%20%EC%86%8C%EC%82%AC%EA%B5%AC&search=5';
}

//create a new instance of Services_JSON
$json = new Services_JSON();

$input = file_get_contents($request);
$value = $json->decode($input);

$totalDistance; //total distance
$totalTime;     //total time
$road1 = 0;  //bycicle
$road2 = 0; //human & bycicle
$road3 = 0;	//human

settype($road2,integer);

//get total information
$totalDistance = $value->summary->totalDistance;
$totalTime = $value->summary->totalTime;

//get road distance
//foreach($value->route[0]->point as $data) /
//{
	
//	if($data)
//}

if($value->summary->returnCode != 0)
{
	die('<item><total_distance>가는 길이 없습니다.</total_distance><total_time></total_time><congestion0></congestion0><congestion1></congestion1><congestion2></congestion2><congestion3></congestion3></item>');
}

foreach($value->route[0]->point as $data)
{
	if($data->road->type == "7")	//human & bycicle
	{
		(int)$road2=intval($road2 + $data->road->distance);
	}
	else if($data->road->type == "6") //bycicle
	{
		(int)$road1=(int)$road1 + (int)$data->road->distance;
	}
	else	//human
	{
		(int)$road3=(int)$road3 + (int)$data->road->distance;
	}
	
	/*
	echo 'data<br />';
	echo 'data.name:'.iconv("UTF-8", "CP949",$data->name).'<br />';
	echo 'data.key:'.$data->key.'<br />';
	echo 'data.x:'.$data->x.'<br />';
	echo 'data.y:'.$data->y.'<br />';
	
	echo 'data.guide.no:'.$data->guide->no.'<br />';
	echo 'data.guide.value:'.$data->guide->value.'<br />';
	echo 'data.guide.message:'.iconv("UTF-8", "CP949",$data->guide->message).'<br />';
	echo 'data.guide.name:'.iconv("UTF-8", "CP949",$data->guide->name).'<br />';
	
	echo 'data.road.no:'.$data->road->no.'<br />';
	echo 'data.road.type:'.$data->road->type.'<br />';
	echo 'data.road.distance:'.$data->road->distance.'<br />';
	echo 'data.road.congestion:'.$data->road->congestion.'<br />';	
	echo 'data.road.speed:'.$data->road->speed.'<br />';
	echo 'data.road.time:'.$data->road->time.'<br />';
	echo 'data.road.cctv:'.$data->road->cctv.'<br />';
	echo 'data.road.uid:'.$data->road->uid.'<br />';	
	*/
}
	
	
echo '<item>';
echo '<total_distance>'.$totalDistance.'</total_distance>';
echo '<total_time>'.$totalTime.'</total_time>';
echo '<road_1>'.$road1.'</road_1>';
echo '<road_2>'.$road2.'</road_2>';
if($road3 == "")
{
	echo '<road_3>0</road_3>';
}
else
{
	echo '<road_3>'.$road3.'</road_3>';
}
echo '</item>';

?>