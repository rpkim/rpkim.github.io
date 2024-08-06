<?
include "json.php";

$xpos = $_GET['xPos'];
$ypos = $_GET['yPos'];
$xpos1 = $_GET['xPos1'];
$ypos2 = $_GET['yPos1'];

$from = $_GET['from'];
$to = $_GET['to'];

//$request = 'http://map.naver.com/findroute2/findCarRoute.nhn?via=&call=route2&output=json&car=0&mileage=12.4&start=126.7051511,37.4560537,dfsdfsf&destination=126.7249104,37.5008313,sdfasdfsf&search=2';

$request = 'http://map.naver.com/findroute2/findCarRoute.nhn?via=&call=route2&output=json&car=0&mileage=12.4&start=126.799975%2C37.480076%2C%EA%B2%BD%EA%B8%B0%EB%8F%84%20%EB%B6%80%EC%B2%9C%EC%8B%9C%20%EC%86%8C%EC%82%AC%EA%B5%AC&destination=127.046336%2C37.2591164%2C%EA%B2%BD%EA%B8%B0%EB%8F%84%20%EC%88%98%EC%9B%90%EC%8B%9C%20%EC%98%81%ED%86%B5%EA%B5%AC&search=2';


//$request = 'http://map.naver.com/findroute2/findCarRoute.nhn?via=&call=route2&output=json&car=0&mileage=12.4&start='.$xpos.','.$ypos.','.$from.'&destination='.$xpos1.','.$ypos1.','.$to.'&search=2';

//create a new instance of Services_JSON
$json = new Services_JSON();

$input = file_get_contents($request);
$value = $json->decode($input);


$total_distance;
$total_time;

$congestion0;	//미확인
$congestion1;   //원활
$congestion2;   //서행
$congestion3;    //지체

//get total information
$total_distance = $value->summary->totalDistance;
$total_time = $value->summary->totalTime;

//make list
foreach($value->route as $data)
{
	echo "<roadlist>";
	echo "<guide>".iconv("UTF-8", "CP949",$data->guide)."</guide>";
	echo "<distance>".$data->distance."</distance>";
	echo "<time>".$data->time."</time>";
	echo "</roadlist>";
	
	//make other list
	foreach($data->point as $point)
	{
		if($point->road->congestion == "0")	//human & bycicle
		{
			(int)$congestion0=intval($congestion0 + $point->road->distance);
		}
		else if($point->road->congestion == "1")
		{
			(int)$congestion1=intval($congestion1 + $point->road->distance);
		}
		else if($point->road->congestion == "2")
		{
			(int)$congestion2=intval($congestion2 + $point->road->distance);
		}
		else if($point->road->congestion == "3")
		{	
			(int)$congestion3=intval($congestion3 + $point->road->distance);
		}

	}
}

echo "<item>";
echo "<total_distance>".$total_distance."</total_distance>";
echo "<total_time>".$total_time."</total_time>";
echo "<congestion0>".$congestion0."</congestion0>";
echo "<congestion1>".$congestion1."</congestion1>";
echo "<congestion2>".$congestion2."</congestion2>";
echo "<congestion3>".$congestion3."</congestion3>";
echo "<congestion4>".$congestion4."</congestion4>";
echo "</item>";

?>