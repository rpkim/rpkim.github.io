<?
require("GTranslate.php");


class Movie
{
	public $title,$thumbnail,$director,$homepage,$year,$actor,$nation,$genre,$open,$grade,$story,$grade1,$grade2,$grade3;
	
	public function Movie($a,$b,$c,$d,$e,$f,$g,$h,$i,$j,$k,$l,$m,$n)
	{
		$this->title = $a;
		$this->thumbnail = $b;
		$this->director = $c;
		$this->homepage = $d;
		$this->year = $e;
		$this->actor = $f;
		$this->nation = $g;
		$this->genre = $h;
		$this->open = $i;
		$this->grade = $j;
		$this->story = $k;
		$this->grade1 = $l;
		$this->grade2 = $m;
		$this->grade3 = $n;
	}
}
class Daum
{
	public $result;
	public function Daum()
	{
		
	}
	public function MakeHomepage($hp)
	{
	    	if(substr($hp, 0 , 4) != "http")
   			{
			    return "http://".htmlspecialchars($hp);    	
			}
			else
			{
	    		return htmlspecialchars($hp);		
			}
	}
	public function GetMovie($search)
	{
		$this->result = array();
		$userkey = '0a9768d760b4efd11e5a9cad51c22f48bffba360';
		$request = 'http://apis.daum.net/contents/movie?output=rss&apikey='.$userkey.'&q='.urlencode($search);
		
		$response = file_get_contents($request);
		$phpobject = simplexml_load_string($response);
		
		if ($phpobject === false) 
		{
			die('Parsing failed');
		}
	
		$channel = $phpobject->channel;

		if(count($channel->item) == 0)
		{
			die('<item><title>no search data.</title><thumbnail></thumbnail><director></director><homepage></homepage></item></result>');
		}

		foreach($channel->item as $value) 
		{
			$a = $value->title->content;
			$b = $value->thumbnail->content;
			$c = $value->director->content;
			$d = $value->title->link;
			$e = $value->year->content;
		
			$f = "";
			foreach($value->actor->content as $actor)
			{
				$f = $f.' / '.$actor;
			}
			$f = substr($f,3,strlen($f)-3);
			
			$g = $value->nation->content;
			
			$h = "";
			foreach($value->genre->content as $genre)
			{
				$h = $h.' / '.$genre;
			}
			$h = substr($h,3,strlen($h)-3);
			
			$i = "";
			foreach($value->open_info->content as $open)
			{
				if($open == "")
				{
					$open = "없음";
				}
				$i = $i.' / '.$open;
			}
			$i = substr($i,3,strlen($i)-3);
			
			$j = $value->grades->content[0].' / '.$value->grades->content[1];
			$k = $value->story->content;
							
			$l = $value->grade1->content[0].' / '.$value->grade1->content[1].' / '.$value->grade1->content[2];
			$m = $value->grade2->content[0].' / '.$value->grade2->content[1].' / '.$value->grade2->content[2];
			$n = $value->grade3->content[0].' / '.$value->grade3->content[1].' / '.$value->grade3->content[2];
			
			$movie = new Movie($a,$b,$c,$d,$e,$f,$g,$h,$i,$j,$k,$l,$m,$n);
			array_push($this->result,$movie);
		}
	}
	public function PrintMovie()
	{
		//google translate
		$gt = new Gtranslate;

		foreach($this->result as $value)
		{
			echo '<item>';
			if($value->title != '')
			{
				echo '<title>'.$gt->ko_to_en($value->title).'</title>';
			}
			else
			{
				echo '<title></title>';
			}

			echo '<thumbnail>'.$value->thumbnail.'</thumbnail>';

			if($value->director != '')
			{
				echo '<director>'.$gt->ko_to_en($value->director).'</director>';
			}
			else
			{
				echo '<director></director>';
			}

			if($value->year != '')
			{
				echo '<year>'.$gt->ko_to_en($value->year).'</year>';
			}
			else
			{
				echo '<year></year>';
			}

			echo '<actor>'.$gt->ko_to_en($value->actor).'</actor>';
			echo '<nation>'.$gt->ko_to_en($value->nation).'</nation>';
			echo '<genre>'.$gt->ko_to_en($value->genre).'</genre>';
			echo '<open>'.$gt->ko_to_en($value->open).'</open>';
			echo '<grade>'.$gt->ko_to_en($value->grade).'</grade>';
			echo '<story></story>';

//			echo '<grade1></grade1>';
//			echo '<grade2></grade2>';
//			echo '<grade3></grade3>';
			echo '<story>'.$gt->ko_to_en($value->story).'</story>';

			echo '<grade1>'.$gt->ko_to_en($value->grade1).'</grade1>';
			echo '<grade2>'.$value->grade2.'</grade2>';
			echo '<grade3>'.$value->grade3.'</grade3>';
			echo '<homepage>'.$value->homepage.'</homepage>';
			echo '</item>';
		}
	}
}

$search = $_GET['search'];
$daum = new Daum();
$daum->GetMovie($search);
$daum->PrintMovie();
?>
