<?
//
require("GTranslate.php");
//google translate
$gt = new Gtranslate;


//Daum Movie
//set request address
$userkey = '0a9768d760b4efd11e5a9cad51c22f48bffba360';
$search = $_GET['search'];

$request = 'http://apis.daum.net/contents/movie?output=rss&apikey='.$userkey.'&q='.urlencode($search);


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
    echo "<title>".$gt->it_to_en(htmlspecialchars($value->title->content))."</title>";
    echo "<thumbnail>".htmlspecialchars($value->thumbnail->content)."</thumbnail>";
    echo "<director>".htmlspecialchars($value->director->content)."</director>";
    if($value->homepage->link != "")
    {
    	if(substr($value->homepage->link, 0 , 4) != "http")
    	{
		    echo "<homepage>http://".htmlspecialchars($value->homepage->link)."</homepage>";    	
		}
		else
		{
		    echo "<homepage>".htmlspecialchars($value->homepage->link)."</homepage>";		
		}
    }
    else
    {
	    echo "<homepage>홈페이지 없음</homepage>";
	}
    echo "</item>";
}
?>
