<?
//Daum shopping
//set request address

$userkey = '89a9be126e8e23712df18feefa427286241484f9';
$request = 'http://apis.daum.net/shopping/search?apikey='.$userkey.'&q='.urlencode($_GET['search']);

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

if(count($channel->item) == 0)
{
	die('<item><title>검색 결과가 없습니다.</title><thumbnail></thumbnail><director></director><homepage></homepage></item></result>');
}

foreach($channel->item as $value) 
{
    echo "<item>";
    echo "<title>".htmlspecialchars($value->title)."</title>";
    echo "<store>Daum</store>";
    echo "<imageurl>".htmlspecialchars($value->image_url)."</imageurl>";
    echo "<price_min>".htmlspecialchars($value->price_min)."</price_min>";
    echo "<price_max>".htmlspecialchars($value->price_max)."</price_max>";
    echo "<link>".htmlspecialchars($value->link)."</link>";
    echo "</item>";
}
?>