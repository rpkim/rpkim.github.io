<?
//header( "Content-type: application/xml; charset=utf-8" ); 

include "json.php";
include "dbconn.php";

class FoodStore
{
	public $name,$x,$y,$comID,$bad;
	
	public function FoodStore($name_,$x_,$y_,$comID_,$bad_)
	{
		$this->name = $name_;
		$this->x = $x_;
		$this->y = $y_;
		$this->comID = $comID_;
		$this->bad = $bad_;
	}
}

class FoodStoreInfo
{
	public $addr,$phone,$category,$des,$review;
	public function FoodStoreInfo($addr_,$phone_,$category_,$des_,$review_)
	{
		$this->addr = $addr_;
		$this->phone = $phone_;
		$this->category = $category_;
		$this->des = $des_;
		$this->review = $review_;
	}
}

class NaverMap
{
	public $json;
	public function NaverMap()
	{
		$this->json = new Services_JSON();	
	}
	public function getSiteInfo($id)
	{
		$request = 'http://map.naver.com/search2/getSiteInfo.nhn?id='.$id;
		$input = file_get_contents($request);
		$value = $this->json->decode($input);
		
		$a = $value->result->address;
		$b = $value->result->phone;
		$c = $value->result->category[0].$value->result->category[1];
		$d = $value->result->description;
		$e = $value->result->reviewCount;
		
		$foodStoreInfo = new FoodStoreInfo($a,$b,$c,$d,$e);
		return $foodStoreInfo;
	}
	public function getAddress($xP,$yP)
	{
		$url = 'http://map.naver.com/common2/getRegionByPosition.nhn?xPos='.$xP.'&yPos='.$yP;
		$input = file_get_contents($url);
		$value = $this->json->decode($input);
		
		$do = $value->result->region->doName;
		$si = $value->result->region->siName;
		$dong = $value->result->region->dongName;

		$addr = $do." ".$si." ".$dong;        
		return $addr;
	}
	public function getFoodStore($xP,$yP,$addr)
	{
		$this->result = array();
		
		$url = 'http://map.naver.com/search2/searchCompanyInRadius.nhn?pageSize=100&xPos='.$xP.'&yPos='.$yP.'&radius=100&query=%EC%9D%8C%EC%8B%9D%EC%A0%90';

		//get data
		$input = file_get_contents($url);
		//$value = $this->json->decode($input);
		$value = json_decode($input);

		//echo $input;
		$count = $value->result->totalCount;
		
		if($count == 0)
		{
			return;
//    		die('<item><name>주변의 음식점이 없습니다.</name><longitude></longitude><latitude></latitude><bad></bad></item>');
		}

		foreach($value->result->items->item as $item)
		{
			$a = $item->name;
			$b = $item->longitude;
			$c = $item->latitude;
			$d = $item->comID;
			
			$sql = "select count(*) from badstore where addr LIKE '%".iconv("UTF-8","CP949",$addr)."%' and storename LIKE '%".iconv("UTF-8", "CP949",$item->name)."%'";
    
			$result = mysql_query($sql) or die ("bad query".$result);	
			//$tot=mysql_num_rows($result);	//행의 갯수
			$rows=mysql_fetch_array($result);

			if($rows[0] == 0)
			{
				$e = 0;
			}
			else
			{
				$e = 1;
			}
						
			$foodStore = new FoodStore($a,$b,$c,$d,$e);
			array_push($this->result,$foodStore);
		}
	}
	public function printFoodStore()
	{
		if(count($this->result) == 0)
		{
	   		die('<item><name>주변의 음식점이 없습니다.</name><longitude></longitude><latitude></latitude><addr></addr><phone></phone><category></category><des></des><id></id><bad></bad></item></result>');
		}
		foreach($this->result as $item)
		{
		    echo '<item>';
		    echo '<name>'.htmlspecialchars($item->name).'</name>';
 		    echo '<longitude>'.$item->x.'</longitude>';
		    echo '<latitude>'.$item->y.'</latitude>';		    
		    $foodStoreInfo = $this->getSiteInfo($item->comID);
			echo '<addr>'.$foodStoreInfo->addr.'</addr>';
			echo '<phone>'.$foodStoreInfo->phone.'</phone>';
			echo '<category>'.$foodStoreInfo->category.'</category>';
			echo '<des>'.$foodStoreInfo->des.'</des>';
		    echo '<bad>'.$item->bad.'</bad>';
    		echo '</item>';
    	}

	}
}

$naverMap = new NaverMap();

//Get data from Naver Map

$xpos = $_GET['xPos'];
$ypos = $_GET['yPos'];

$addr = $naverMap->GetAddress($xpos,$ypos);
$naverMap->GetFoodStore($xpos,$ypos,$addr);
$naverMap->printFoodStore();
