<?
include "json.php";

//Get data from Naver Map

$xpos = $_GET['xPos'];
$ypos = $_GET['yPos'];

//$request = 'http://map.naver.com/search2/searchCompanyInRadius.nhn?pageSize=100&xPos='.$xpos.'&yPos='.$ypos.'&radius=500&query=음식점';
$request = 'http://map.naver.com/search2/searchCompanyInRadius.nhn?pageSize=100&xPos='.$xpos.'&yPos='.$ypos.'&radius=500&query=%EC%9D%8C%EC%8B%9D%EC%A0%90';

//echo $request;

//create a new instance of Services_JSON
$json = new Services_JSON();

//get data
$input = file_get_contents($request);
$value = $json->decode($input);

//echo $input;

$count = $value->result->totalCount;

if($count == 0)
{
    die('<result><item><name>주변의 음식점이 없습니다.</name><longitude></longitude><latitude></latitude></item></result>');
}

echo '<result>';
foreach($value->result->items->item as $item)
{
    echo '<item>';
    echo '<name>'.urldecode($item->name).'</name>';
    echo '<longitude>'.$item->longitude.'</longitude>';
    echo '<latitude>'.$item->latitude.'</latitude>';
    echo '</item>';
    
}
echo '</result>';

//echo 'value'.urldecode($value->result->items->item[0]->name);
