<?php

//버스 실시간 위치 정보를 저장하기 위한 클래스
class BusLocInfo
{
	public $type,$data;
	
	public function BusLocInfo($type_,$data_)
	{
		$this->type=$type_;
		$this->data=$data_;
	}
}
//서울 버스와 관련된 클래스
class SeoulBus
{
	public $result;
	
	//생성자
	public function SeoulBus()
	{
	}
	
	private function getUniqIDFromBusNum($busNum)
	{
		$url = 'http://210.96.13.90/nweb/innerhtml.jsp?setnemu=A1&setname='.$busNum.'&setcode=1&setnobj=1';
		$input = file_get_contents($url);

		$loc = strpos($input,"setname");
		$loc1 = strpos($input,"&",$loc);
		
		if($loc != "")
		{
			$str = substr($input,$loc+8,$loc1-$loc-8);
		}
		
		return $str;
	}

	//delete ( right value
	private function TrimRight($str)
	{
		$loc = strpos($str,"(");
		if($loc != "")
		{
			$str = substr($str,0,$loc);
		}
		return $str;
	}
	
	//Curl을 이용하여 소스를 가져온다.
	private function getSourceUsingCurl($url)
	{
		$curl_handle=curl_init();
		curl_setopt($curl_handle, CURLOPT_URL,$url);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_handle, CURLOPT_USERAGENT, 'rpkim');
		$value = curl_exec($curl_handle);
		curl_close($curl_handle);
		
		return $value;
	
	}

	//버스 번호를 입력하면, 실시간 버스의 위치를 가져온다.
	public function getNowBusLocation($busNum)
	{
		//결과값 초기화
		$this->result = array();
		
		//버스의 uniq ID값을 가져온다.
		$UniqID = $this->getUniqIDFromBusNum($busNum);
		
		$url = 'http://210.96.13.82/bms/web/realtime_bus/bus_stations.jsp?routeId='.$UniqID.'&routeName='.$busNum.'&routeType=1&flashHeight=1000';
		//소스를 가져온다.
		$value = $this->getSourceUsingCurl($url);
		
		//데이터 부분을 찾아 데이터를 생성한다.
		$loc = strpos($value,"one-cell-table");
		$loc1 = $loc;
		
		while(true)
		{
			$loc = strpos($value,"<td>",$loc1);
		
			if($loc == "")
			{
				break;
			}
			$loc1 = strpos($value,"</td>",$loc);

			$str = substr($value,$loc+4,$loc1-$loc-4);
			
			
			//버스가 있다면 bus 를 넣어준다.
			if(substr($str,0,7) == '<a href')
			{
				$busloc = new BusLocInfo('bus','bus');	
			}
			else
			{
				$busloc = new BusLocInfo('stop',$str);
			}
			
			//결과 값을 return해준다.
			array_push($this->result,$busloc);
		}
	}

	//결과값을 출력하는 것을 테스트 할때 사용한다.
	public function printResult()
	{
		if($this->result != null)
		{
			foreach($this->result as $value)
			{
				echo "type:".$value->type."<br />";
				echo "data:".$value->data."<br />";
			}
		}
	}
	
	//결과값의 갯수를 출력하는 것을 테스트 할때 사용한다.
	public function printResultCount()
	{
		echo "count:".count($this->result)."<br />";
	}
	
	//[]목적지 전의 버스가 와 있는 위치를 구하여 리턴해준다.
	public function getBeforeBus($data)
	{
		$k = count($this->result);
		//$beforeCount = 0;
		for($i = 0 ; $i < $k ; $i++)
		{
			$value = $this->TrimRight($this->result[$i]->data);
			$value = trim($value);
			
			echo $value;
			
			if($value == $data)
			{
				for($j = $i ; $j > 0; $j--)
				{
					if($this->result[$j]->data == "bus")
					{
						return $this->result[$j-1];
					}
					//$beforeCount++;
				}
			}
		}
	}
}



//TEST CODE
//$seoulBus = new SeoulBus();
//$seoulBus->getNowBusLocation(271);
//$seoulBus->printResultCount();
//$seoulBus->printResult();
//$bb = $seoulBus->getBeforeBus(iconv("UTF-8", "CP949","중랑초등학교"));


?>