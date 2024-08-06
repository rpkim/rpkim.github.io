<?
include "json.php";

//Get data from Naver Map

$search = $_GET['search'];

$request = 'http://map.naver.com/search2/local.nhn?query='.urlencode($search).'&menu=location';

//create a new instance of Services_JSON
$json = new Services_JSON();

//get data
$input = file_get_contents($request);
$value = $json->decode($input);

//echo $input;

//$count = $value->result->totalCount;

//if($count == 0)
//{    die('<result><item><name>주변의 음식점이 없습니다.</name><longitude></longitude><latitude></latitude></item></result>');}

if($value->result->site->sort == "")
{
	if($value->result->region->x == "")
	{
		die('<result><item><name>'.iconv('UTF-8', 'CP949','검색 결과가 없습니다.').'</name><longitude></longitude><latitude></latitude></item></result>');
	}
	else
	{
		echo '<result>';
    	echo '<item>';
	    echo '<name>'.iconv("UTF-8", "CP949",$value->result->region->name).'</name>';
   		echo '<longitude>'.$value->result->region->x.'</longitude>';
    	echo '<latitude>'.$value->result->region->y.'</latitude>';
    	echo '</item>';  
		echo '</result>';	
	}
}
else
{
	echo '<result>';
	foreach($value->result->site->list as $item)
	{
   		echo '<item>';
    	echo '<name>'.iconv("UTF-8", "CP949",$item->name).'</name>';
    	echo '<longitude>'.$item->x.'</longitude>';
    	echo '<latitude>'.$item->y.'</latitude>';
    	echo '</item>';  
	}
	echo '</result>';
}

//echo 'value'.urldecode($value->result->items->item[0]->name);

//rpkim.net/bada/goornot.php?search=수원
