<?
//gmarket shopping

//set request address
$request = 'http://www.gmarket.co.kr/challenge/neo_rss/rss.asp?MODE=KEYWORD&KEYWORD='.$_GET['search'].'&GDLC_CD=&GDMC_CD=&GDSC_CD=&TRAD_WAY=&PRICE_UNIT=';

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
foreach($channel->item as $value) 
{
   echo "<Item>";
   echo "<title>".htmlspecialchars($value->title)."</title>";
   echo "<store>(G마켓)</store>";
   echo "<categori></categori>";
   echo "<imageurl></imageurl>";
   echo "<price_min>".htmlspecialchars($value->author)."</price_min>";
   echo "<price_max>".htmlspecialchars($value->author)."</price_max>";
   echo "<link>".htmlspecialchars($value->link)."</link>";
   echo "</Item>";
}
?>
