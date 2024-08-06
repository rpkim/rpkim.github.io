<?


//header( "Content-type: application/xml; charset=utf-8" ); 

include "json.php";
include "dbconn.php";

//Get data from Naver Map

$xpos = $_GET['xPos'];
$ypos = $_GET['yPos'];

$request1 = 'http://map.naver.com/common2/getRegionByPosition.nhn?xPos='.$xpos.'&yPos='.$ypos;


//create a new instance of Services_JSON
$json1 = new Services_JSON();

$input1 = file_get_contents($request1);
$value1 = $json1->decode($input1);

$doName = iconv("UTF-8", "CP949",$value1->result->region->doName);
$siName = iconv("UTF-8", "CP949",$value1->result->region->siName);
$dongName = iconv("UTF-8", "CP949",$value1->result->region->dongName);

//echo "do:".$doName."<br />";
//echo "si:".$siName."<br />";
//echo "dong:".$dongName."<br />";

$addr = $doName." ".$siName." ".$dongName;        

$request = 'http://map.naver.com/search2/searchCompanyInRadius.nhn?pageSize=100&xPos='.$xpos.'&yPos='.$ypos.'&radius=500&query=%EC%9D%8C%EC%8B%9D%EC%A0%90';

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
    echo '<name>'.iconv("UTF-8", "CP949",$item->name).'</name>';
    echo '<longitude>'.$item->longitude.'</longitude>';
    echo '<latitude>'.$item->latitude.'</latitude>';
    
    $sql = "select count(*) from badstore where addr LIKE '%".$addr."%' and storename='".iconv("UTF-8", "CP949",$item->name)."'";
    
  //  echo $sql."<br />";
    
	$result = mysql_query($sql) or die ("bad query".$result);	
	//$tot=mysql_num_rows($result);	//행의 갯수
	$rows=mysql_fetch_array($result);

	if($rows[0] == 0)
	{
		echo '<bad>0</bad>';
	}
	else
	{
		echo '<bad>1</bad>';
	}
	    
    echo '</item>';
    
}
echo '</result>';

//echo 'value'.urldecode($value->result->items->item[0]->name);
