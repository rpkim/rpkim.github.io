<?
//Naver shopping
//set request address
$userkey = '19381aa2ac8a27229444f8bd3ee0911c';

$request = 'http://openapi.naver.com/search?key='.$userkey.'&display=10&start=3&target=shop&sort=sim&query='.urlencode($_GET['search']);
//get response(get source)
$response = file_get_contents($request);
$phpobject = simplexml_load_string($response);
if ($phpobject === false) {
   die('Parsing failed');
}
// Output the data
// SimpleXML returns the data as a SimpleXML object
//get channel -> item
$channel = $phpobject->channel;
//productId
if(count($channel->item) == 0)
{
	die('<item><title>검색 결과가 없습니다.</title><thumbnail></thumbnail><director></director><homepage></homepage></item></result>');
}

foreach($channel->item as $value) 
{
   echo "<item>";
   echo "<title>".htmlspecialchars($value->title)."</title>";
   echo "<store>Naver</store>";
   echo "<imageurl>".htmlspecialchars($value->image)."</imageurl>";
   echo "<price_min>".htmlspecialchars($value->lprice)."</price_min>";
   echo "<price_max>".htmlspecialchars($value->hprice)."</price_max>";
   echo "<link>".htmlspecialchars($value->link)."</link>";
   echo "</item>";
}
?>