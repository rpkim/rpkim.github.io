<?
include "json.php";

$xpos = $_GET['xPos'];
$ypos = $_GET['yPos'];

$request = 'http://map.naver.com/common2/getRegionByPosition.nhn?xPos='.$xpos.'&yPos='.$ypos;

//create a new instance of Services_JSON
$json = new Services_JSON();

$input = file_get_contents($request);
$value = $json->decode($input);

$weather = $value->result->weather->weatherText;
$temperature = $value->result->weather->temperature;

if($weather == "")
{
	echo '<item>';
	echo '<weather>do not support location.</weather>';
	echo '<temperature></temperature>';
	echo '</item>';

}
else
{
	echo '<item>';
	echo '<weather>'.$weather.'</weather>';
	echo '<temperature>'.$temperature.'</temperature>';
	echo '</item>';
}

?>