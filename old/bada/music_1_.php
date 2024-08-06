<?
//Music

//set request address
$userkey = '3a7ae5b618fffe1766bdb55cf6ed5de0';
$search = $_GET['search'];
$type = $_GET['type'];

$request = '';

if($type == "song" || $type == "album" || $type == "artist")
{
	$request = 'http://www.maniadb.com/api/search.asp?key='.$userkey.'&target=music&itemtype='.$type.'&option='.$type.'&query='.urlencode($search);
}
else
{
        die('address do not have type. please enter type');
}
//echo $request;

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
	die('<item><title>검색 결과가 없습니다.</title><thumbnail></thumbnail><director></director><homepage></homepage></item>');
}

foreach($channel->item as $value)
{
    echo "<item>";
    echo "<title>".htmlspecialchars($value->title)."</title>";
    echo "<link>".htmlspecialchars($value->link)."</link>";

    if($type != "song")
    {
        echo "<image>".htmlspecialchars($value->image)."</image>";
    }
    else
    {
        echo "<image>이미지없음</image>";
    }
    echo "</item>";
}
?>