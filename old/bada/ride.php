<?
  header ('Content-Type: text/xml; charset=utf-8'); 
  echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>";
?>
<?

include "NaverMap.php";
include "SeoulBus.php";

$type = $_GET["type"];
$pubtype = $_GET["pubtype"];

$naverMap = new NaverMap();
$seoulBus = new SeoulBus();
$BusList = array();

//1.주변의 버스 정류장을 찾는다.
if($type == "1")	
{
	$x = $_GET["x"];
	$y = $_GET["y"];

	$naverMap->FindBusStop($x,$y,500);	
	$naverMap->printBusStop();
}
else if($type == "2")
{
	$x = $_GET["x"];
	$y = $_GET["y"];
	$x1 = $_GET["x1"];
	$y1 = $_GET["y1"];
	$from = $_GET["from"];
	$to = $_GET["to"];
	
	//버스의 종류 및 걸리는 시간을 저장한다.
	$pub = $naverMap->FindPub($x,$y,$x1,$y1,$from,$to);
	
	if(count($pub) == 0)
	{
		die('no bus');
	}
	
	
	$path = $naverMap->GetBetterPath($pub,$pubtype);
	$subPath = $naverMap->MakeSubPath($path,$x,$y);
	$naverMap->PrintSubPathData($subPath);
}

?>