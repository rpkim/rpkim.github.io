<?
//Daum Book
//set request address
$userkey = '6df75f87f031335090a0e814766e13ae1b1489aa';
$search = $_GET['search'];

$request = 'http://apis.daum.net/search/book?searchtype=all&apikey='.$userkey.'&q='.urlencode($search);

//get response(get source)
$response = file_get_contents($request);

$phpobject = simplexml_load_string($response);

if ($phpobject === false) {
	die('Parsing failed');
}

/*
if($phpobject->totalCount == "0")
{
	die('<item><title>검색 결과가 없습니다.</title><link></link><cover_s_url></cover_s_url><author></author><description></description><category></category></item>');
}
*/
// Output the data
$i = 0;
foreach($phpobject->item as $value)
{
	$i++;
    echo "<item>";
    echo "<title>".$value->title."</title>";
    echo "<link>".$value->link."</link>";
    echo "<cover_s_url>".$value->cover_s_url."</cover_s_url>";
    echo "<author>".$value->author."</author>";
    echo "<description>".$value->description."</description>";
    echo "<pub_nm>".$value->pub_nm."</pub_nm>";
    echo "<pub_date>".$value->pub_date."</pub_date>";
    echo "<category>".$value->category."</category>";
    echo "<price>".$value->list_price."</price>";
    echo "</item>";
}
if ($i == 0)
	die('<item><title>검색 결과가 없습니다.</title><link></link><cover_s_url></cover_s_url><author></author><description></description><category></category></item>');
?>
