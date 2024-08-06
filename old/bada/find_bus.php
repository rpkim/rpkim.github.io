<?
include "json.php";

$xpos = $_GET['xPos'];
$ypos = $_GET['yPos'];
$xpos1 = $_GET['xPos1'];
$ypos2 = $_GET['yPos1'];

$from = $_GET['from'];
$to = $_GET['to'];


$request = 'http://map.naver.com/findroute2/findPubTransRoute.nhn?start=127.0508980,37.2474128,%EB%94%94%EC%A7%80%ED%84%B8%EC%97%A0%ED%8C%8C%EC%9D%B4%EC%96%B42&destination=127.2731851,37.3396137,%ED%95%9C%EA%B5%AD%EC%99%B8%EA%B5%AD%EC%96%B4%EB%8C%80%ED%95%99%EA%B5%90%20%EC%9A%A9%EC%9D%B8%EC%BA%A0%ED%8D%BC%EC%8A%A4&direct=1';

//$request = 'http://map.naver.com/findroute2/findCarRoute.nhn?via=&call=route2&output=json&car=0&mileage=12.4&start='.$xpos.','.$ypos.','.$from.'&destination='.$xpos1.','.$ypos1.','.$to.'&search=2';

//create a new instance of Services_JSON
$json = new Services_JSON();

$input = file_get_contents($request);
$value = $json->decode($input);

$payment = $value->result->path[0]->info->payment;
$bustransitCount = $value->result->path[0]->info->busTransitCount;
$subwaytransitCount = $value->result->path[0]->info->subwayTransitCount;
$busstationCount = $value->result->path[0]->info->busStationCount;
$subwaystationCount = $value->result->path[0]->info->subwayStationCount;
$totalstationCount = $value->result->path[0]->info->totalStationCount;
$totalTime = $value->result->path[0]->info->totalTime;
$totalWalk = $value->result->path[0]->info->totalWalk;
$trafficDistance = $value->result->path[0]->info->trafficDistance;
$totalDistance = $value->result->path[0]->info->totalDistance;


echo '<item>';
echo '<payment>'.$payment.'</payment>';
echo '<bustransitcount>'.$bustransitCount.'</bustransitcount>';
echo '<subwaytransitcount>'.$subwaytransitCount.'</subwaytransitcount>';
echo '<totalstationcount>'.$totalstationCount.'</totalstationcount>';
echo '<totaltime>'.$totalTime.'</totaltime>';
echo '<totalwalk>'.$totalWalk.'</totalwalk>';
echo '<trafficdistance>'.$trafficDistance.'</trafficdistance>';
echo '<totaldistance>'.$totalDistance.'</totaldistance>';
echo '</item>';

?>