<?
include "json.php";

$xpos = $_GET['xPos'];
$ypos = $_GET['yPos'];
$xpos1 = $_GET['xPos1'];
$ypos2 = $_GET['yPos1'];

$from = $_GET['from'];
$to = $_GET['to'];


$request = 'http://map.naver.com/findroute2/findCarRoute.nhn?via=&call=route2&output=json&car=0&mileage=12.4&start=126.7051511,37.4560537,dfsdfsf&destination=126.7249104,37.5008313,sdfasdfsf&search=2';

//$request = 'http://map.naver.com/findroute2/findCarRoute.nhn?via=&call=route2&output=json&car=0&mileage=12.4&start='.$xpos.','.$ypos.','.$from.'&destination='.$xpos1.','.$ypos1.','.$to.'&search=2';

//create a new instance of Services_JSON
$json = new Services_JSON();

$input = file_get_contents($request);
$value = $json->decode($input);


$name = $value->summary->road->name;

echo $value->summary->totalDistance.'<br />';
echo $value->summary->startPoint->x.'<br />';
echo $value->route->distance.'<br />';
echo $value->route[0]->distance.'<br />';
echo $value->route[0]->point[0]->name.'<br />';
echo $value->route[0]->point[0]->road->speed.'<br />';
echo $value->route[0]->point[1]->road->speed.'<br />';
echo $value->route[0]->point[2]->road->speed.'<br />';
echo $value->route[0]->point[3]->road->speed.'<br />';
echo $value->route[0]->point[4]->road->speed.'<br />';
echo $value->route[0]->point[0]->road->name.'<br />';
echo $value->route[0]->point[1]->road->name.'<br />';
echo $value->route[0]->point[2]->road->name.'<br />';


echo 'name:'.$name;


?>