<?
include "json.php";

//x y 좌표 클래스
class Loc
{
	public $x,$y;
	function Loc($x_,$y_)
	{
		$this->x = $x_;
		$this->y = $y_;
	}
}

//버스 번호와 해당 선택 정류장 까지 걸리는 시간을 저장하는 객체
class Bus
{
	public $busNum,$time;
	function Bus($busNum_,$time_)
	{
		$this->busNum = $busNum_;
		$this->time = $time_;
	}
}
//버스 정류장 이름, 버스 정류장 실제 디스플레이 되는 이름, 위도 경도 값을 저장하는 객체
class BusStop
{
	public $stationName,$stationDisplayName,$x,$y;
	
	function BusStop($stationName_,$stationDisplayName_,$x_,$y_)
	{
		$this->stationName = $stationName_;
		$this->stationDisplayName = $stationDisplayName_;
		$this->x = $x_;
		$this->y = $y_;
	}
}

//버스 정류장 경로를 저장하는 객체
class BusRoute
{
	public $stationName,$stationDisplayName,$x,$y,$order;
	
	function BusRoute($stationName_,$stationDisplayName_,$x_,$y_,$order_)
	{
		$this->stationName = $stationName_;
		$this->stationDisplayName = $stationDisplayName_;
		$this->x = $x_;
		$this->y = $y_;
		$this->order = $order_;
	}
}

//2010 - new
class PubData
{
	public $payment,$totalDistance,$totaltime,$subPath;
	
	public function PubData($a,$b,$c,$d)
	{
		$this->subPath = array();
		$this->payment = $a;
		$this->totalDistance = $b;
		$this->totaltime = $c;
		$this->subPath = $d;
	}	
}
//2010 - new
class SubPathData
{
	public $distance,$sectiontime,$traffic,$guide,$startX,$startY;
	
	public function SubPathData($a,$b,$c,$d,$e,$f)
	{
		$this->distance = $a;
		$this->sectiontime = $b;
		$this->traffic = $c;
		$this->guide = $d;
		$this->startX = $e;
		$this->startY = $f;
	}
}

//네이버 맵과 관련된 객체
class NaverMap
{
	//여러가지 값들을 리턴할때 사용함.
	public $result;
	private $json;

	//생성자	
	public function NaverMap()
	{
		//json초기화
		$this->json = new Services_JSON();
	}
	
	//웹에서 데이터를 가져와 json형태로 변환해 준다.
	private function GetDataFromJson($url)
	{
		$input = file_get_contents($url);
//		$input = iconv("UTF-8", "CP949",$input);
		$value = $this->json->decode($input);

		return $value;
	}

	//가져온 결과 값에서 지하철역을 몇개 사용하는지 리턴해준다.
	private function GetSubwayStationCount($path)
	{
		return $path->info->subwayStationCount;
	}
	
	//가져온 결과 값에서 버스 환승을 몇번 하는지 리턴해준다.
	private function GetBusTransitCount($path)
	{
		return $path->info->busTransitCount;
	}
	
	//총 버스 노선의 갯수를 구해서 리턴해 준다.
	private function GetBusCount($value)
	{
		return $value->result->busCount;
	}
	
	//실제 버스 타입만 골라서 저장하기 위해 사용한다.
	private function CheckIsBusType($subpath)
	{
		//2 is bus
		if($subpath->trafficType == 2)
		{
			return true;
		}
		else
		{
			//3 is walking
			return false;
		}
	}
	
	//에러 체크
	private function CheckError($value)
	{
		if($value->error->code == "-99")
		{
			die('대중교통 길 찾기 결과가 없습니다.');
			return false;
		}
		else if($value->error->code == "-9")
		{
			return false;
		}
		else if($value->error->code == "-98")
		{
			die($value->error->walkTime.'분 걸리오니 걸어가세요');
			return false;
			//return false;
		}
		else
		{
			return true;
		}
	}
	
	//알짜배기 정보만 저장하기 위해 이름( 옆에 붙은 (~ 부분을 제거해준다.
	private function TrimRight($str)
	{
		$loc = strpos($str,"(");
		if($loc != "")
		{
			$str = substr($str,0,$loc);
		}
		return $str;
	}
	
	//특정 버스가 하나의 위치에서 다른 한편으로 가는 위치까지의 시간을 구한다.
	public function GetBusTime($xpos,$ypos,$xpos1,$ypos1,$from,$to,$busNum)
	{
		$this->FindBus($xpos,$ypos,$xpos1,$ypos1,$from,$to);
		foreach($this->result as $bus)
		{
			if($bus->busNum == $busNum)
			{
				return $bus->time;
			}
		}	
	}
	
	//20100818 - new
	public function FindPub($x,$y,$x1,$y1,$f,$t)
	{
		$url = 'http://map.naver.com/findroute2/findPubTransRoute.nhn?start='.$x.','.$y.','.urlencode($f).'&destination='.$x1.','.$y1.','.urlencode($t).'$direct=1';
		
		$value = $this->GetDataFromJson($url);
		
		$reVal = array();
		if(count($value->result->path) != 0)
		{
		//	print_r($value->result->path);
			foreach($value->result->path as $path)
			{
				$a = $path->info->payment;
				$b = $path->info->totalDistance;
				$c = $path->info->totalTime;
				$d = $path->subPath;
				
				$pubData = new PubData($a,$b,$c,$d);
				array_push($reVal,$pubData);	
			}
			
			return $reVal;
		}
		else
		{
			die('do not have pub');
		}
	}
	//2010 - new
	public function GetBetterPath($pubData,$type)
	{	
			$count = count($pubData);

			$BetterPub = $pubData[0];
			//print_r($BetterPub);
			for($i = 1; $i < $count ; $i++)
			{
				if($type == '0')	//low payment
				{
					if($pubData[$i]->payment < $BetterPub->payment)
					{
						$BetterPub = $pubData[$i];
					}
				}
				else if($type == '1')	//low distance
				{
					if($pubData[$i]->totalDistance < $BetterPub->totalDistance)
					{
						$BetterPub = $pubData[$i];
					}				
				}
				else if($type == '2')
				{
					if($pubData[$i]->totaltime < $BetterPub->totaltime)
					{
						$BetterPub = $pubData[$i];
					}
				}
			}
			
			return $BetterPub;
	}
	//2010 - new
	public function MakeSubPath($Path,$startX,$startY)
	{
		$reVal = array();
				
		for($i = 0 ; $i < count($Path->subPath); $i++)
		{
			$subPath = $Path->subPath[$i];
			
			$a = $subPath->distance;
			$b = $subPath->sectionTime;

			if($subPath->trafficType == '1')
			{
				$c = '지하철';
				$d = '['.$subPath->lane->name.' '.$subPath->door.'번 문]'.$subPath->guide;
			}
			else if($subPath->trafficType == '2')
			{	
				$c = '버스';
				$d = '['.$subPath->lane->busNo.'번 버스]'.$subPath->guide;
			}
			else if($subPath->trafficType == '3')
			{
				$c = '걷기';
				$d = $subPath->guide;
			}
			
			if($i == 0)
			{
				$e = $startX;
				$f = $startY;
			}
			else
			{
				if($Path->subPath[$i]->trafficType == '3')
				{
					$e = $Path->subPath[$i-1]->endX;
					$f = $Path->subPath[$i-1]->endY;
				}
				else
				{
					$e = $subPath->startX;
					$f = $subPath->startY;
				}
			}

			$subPathData = new SubPathData($a,$b,$c,$d,$e,$f);
			
			array_push($reVal,$subPathData);
		}
		return $reVal;
	}
	//2010 - new
	public function PrintSubPathData($spList)
	{
		echo '<result>';
		foreach($spList as $spData)
		{
			echo '<item>';
			echo '<distance>'.$spData->distance.'</distance>';
			echo '<time>'.$spData->sectiontime.'</time>';
			echo '<trafficType>'.$spData->traffic.'</trafficType>';
			$temp = str_replace('$direct=1',' ',$spData->guide);
//			echo '<guide>'.$spData->guide.'</guide>';
			echo '<guide>'.$temp.'</guide>';
			echo '<startX>'.$spData->startX.'</startX>';
			echo '<startY>'.$spData->startY.'</startY>';
			echo '</item>';
		}
		echo '</result>';
	}
	
	//시작과 끝을 입력하여 실제 갈 수 있는 버스를 검색한 후, 해당 버스의 번호와 걸리는 시간 데이터를 저장한다.
	public function FindBus($xpos,$ypos,$xpos1,$ypos1,$from,$to)
	{
		$url = 'http://map.naver.com/findroute2/findPubTransRoute.nhn?start='.$xpos.','.$ypos.','.urlencode($from).'&destination='.$xpos1.','.$ypos1.','.urlencode($to).'&direct=1';
		
//		echo $url;
		
		$value = $this->GetDataFromJson($url);
		
		
		$this->result = array();
		
		//print_r($value);
	
		if($this->CheckError($value))
		{
			foreach($value->result->path as $path)
			{
				//지하철을 사용하지 않는 것에 한해서 구현한다.
				if($this->GetSubwayStationCount($path) == 0)
				{
					//오직 한번의 버스만 사용할 때에 한해서 구현한다.
					if($this->GetBusTransitCount($path) == 1)
					{
						foreach($path->subPath as $subpath)
						{
							//걷는 부분의 데이터 들을 제거하고 오직 버스 데이터만 추출한다.
							if($this->CheckIsBusType($subpath))
							{
								//번호와 걸리는 시간을 저장한다.
								$busNum = $this->TrimRight($subpath->lane->busNo);
								$bus = new Bus($busNum,$subpath->sectionTime);
								echo($busNum);
								echo($subpath->sectionTime);
								array_push($this->result,$bus);
								return;
							}
						}
					}
					else
					{
						die('have more two bus');
						//have more two bus.
						
					}
				}
				else
				{
					die('have sub');
					//have subway
				}
			}
			
			return $this->result;
		}
		else
		{
			//have error
		}
	}
	
	//주변의 버스 정류장을 찾는다.
	public function FindBusStop($xPos,$yPos,$radius)
	{
		$this->result = array();
		$url = 'http://map.naver.com/pubtrans/searchSpotRadius.nhn?x='.$xPos.'&y='.$yPos.'&radius='.$radius;
		$input = file_get_contents($url);
		$input = iconv("UTF-8", "CP949",$input);

		$loc1 = 0;	
		while(true)
		{
			$loc = strpos($input,"stationName",$loc1);

			if($loc == "")
			{
				break;
			}
			
			$loc = strpos($input,":",$loc);
			$loc1 = strpos($input,",",$loc);
			$a = substr($input,$loc+3,$loc1-$loc-3-1);

			$loc = strpos($input,"stationDisplayName",$loc1);
			$loc = strpos($input,":",$loc);
			$loc1 = strpos($input,",",$loc);			
			$b = substr($input,$loc+3,$loc1-$loc-3-1);

			$loc = strpos($input,"x",$loc1);
			$loc = strpos($input,":",$loc);
			$loc1 = strpos($input,",",$loc);
			$c = substr($input,$loc+3,$loc1-$loc-3-1);

			$loc = strpos($input,"y",$loc1);
			$loc = strpos($input,":",$loc);
			$loc1 = strpos($input,",",$loc);
			$d = substr($input,$loc+3,$loc1-$loc-3-1);
						
			$busstop= new BusStop($a,$b,$c,$d);
		 	array_push($this->result,$busstop);
		}

		
		//get count
		$count = $value->result->count;
		
		$this->result = array();
				
		if($count != 0)	
		{
			foreach($value->result->station as $station)
			{
				//버스정류장의 이름, 실제 버스정류장의 디스플레이 이름, x, y 값을 저장한다.
			 	$stationName = $station->stationName;
			 	$stationDisplayName = $station->stationDisplayName;
			 	$x = $station->x;
			 	$y = $station->y;
			 	
			 	$busstop= new BusStop($stationName,$stationDisplayName,$x,$y);
		 		array_push($this->result,$busstop);
			}
		}
		
	}
	public function printBusStop()
	{
		if (count($this->result) == 0)
		{
			die('<result><item><stationName>No data</stationName><stationDisplayName></stationDisplayName><x></x><y></y></item></result>');
		}
		else
		{
			echo '<result>';
			foreach($this->result as $busstop)
			{
				echo '<item>';
				echo '<stationName>'.$busstop->stationName.'</stationName>';
				echo '<stationDisplayName>'.$busstop->stationDisplayName.'</stationDisplayName>';
				echo '<x>'.$busstop->x.'</x>';
				echo '<y>'.$busstop->y.'</y>';
				echo '</item>';
			}
			echo '</result>';
		}
	}
	//네이버에서 사용하는 버스ID를 구한다. (실제 버스 번호 입력 시, 버스ID 리턴)
	public function GetNaverBusID($busNum)
	{
		$url = 'http://traffic.map.naver.com/Bus/Search_HiddenFrame.asp?CID=1000&LMenu=2&BusNO='.$busNum;
		
		$input = file_get_contents($url);
		
		$loc = strpos($input,"&BLID");
		
		if($loc == 0)
		{
			die(iconv("UTF-8", "CP949","서울 버스가 아닙니다."));
		}

		$loc1 = strpos($input,";",$loc);
		$str = substr($input,$loc+6,$loc1-$loc-6-1);

		return $str;
	}
	
		//버스의 경로 데이터를 가져온다.
	public function GetBusXY($busNum,$station)
	{
		$busID = $this->GetNaverBusID($busNum);
		
		$url = 'http://map.naver.com/pubtrans/getBusRouteInfo.nhn?busID='.$busID;
		
		$input = file_get_contents($url);
		$input = iconv("UTF-8", "CP949",$input);
		
		$loc1 = 0;	
		$e=0;
		while(true)
		{
			$loc = strpos($input,"stationName",$loc1);
			
			if($loc == "")
			{
				break;
			}
			
			$loc = strpos($input,":",$loc);
			$loc1 = strpos($input,",",$loc);
			$a = substr($input,$loc+3,$loc1-$loc-3-1);
			
			$loc = strpos($input,"stationDisplayName",$loc1);
			$loc = strpos($input,":",$loc);
			$loc1 = strpos($input,",",$loc);			
			$b = substr($input,$loc+3,$loc1-$loc-3-1);
		
			$loc = strpos($input,"x",$loc1);
			$loc = strpos($input,":",$loc);
			$loc1 = strpos($input,",",$loc);
			$c = substr($input,$loc+3,$loc1-$loc-3-1);
			
			$loc = strpos($input,"y",$loc1);
			$loc = strpos($input,":",$loc);
			$loc1 = strpos($input,",",$loc);
			$d = substr($input,$loc+3,$loc1-$loc-3-1);
			
			if($a == $station)
			{
				$xy = new Loc($c,$d);
				return $xy;
			}
			if($b == $station)
			{
				$xy = new Loc($c,$d);
				return $xy;
			}
		}
	}


	//버스의 경로 데이터를 가져온다.
	public function GetBusRoute($busID)
	{
		$this->result = array();
		
		$url = 'http://map.naver.com/pubtrans/getBusRouteInfo.nhn?busID='.$busID;
		
		$input = file_get_contents($url);
		$input = iconv("UTF-8", "CP949",$input);
		
		$loc1 = 0;	
		$e=0;
		while(true)
		{
			$loc = strpos($input,"stationName",$loc1);
			
			if($loc == "")
			{
				break;
			}
			
			$loc = strpos($input,":",$loc);
			$loc1 = strpos($input,",",$loc);
			$a = substr($input,$loc+3,$loc1-$loc-3-1);
			
			$loc = strpos($input,"stationDisplayName",$loc1);
			$loc = strpos($input,":",$loc);
			$loc1 = strpos($input,",",$loc);			
			$b = substr($input,$loc+3,$loc1-$loc-3-1);
		
			$loc = strpos($input,"x",$loc1);
			$loc = strpos($input,":",$loc);
			$loc1 = strpos($input,",",$loc);
			$c = substr($input,$loc+3,$loc1-$loc-3-1);
			
			$loc = strpos($input,"y",$loc1);
			$loc = strpos($input,":",$loc);
			$loc1 = strpos($input,",",$loc);
			$d = substr($input,$loc+3,$loc1-$loc-3-1);
			
			$e++;			

			$busRoute = new BusRoute($a,$b,$c,$d,$e);
			array_push($this->result,$busRoute);
		}
	}
	//테스트용 결과 갯수 출력 함수	
	function GetResultCount()
	{
		echo "count:".count($this->result);
	}
	function GetResult()
	{
		return $this->result;
	}
}


//test code
//$np = new NaverMap();
//$np->FindBusStop(127.0221772,37.6584681,500);
//$np->GetBusRoute($np->GetNaverBusID(271));
//$np->GetResultCount();

?>