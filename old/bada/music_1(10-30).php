<?
class Song
{
	public $title,$time,$link,$description;
	
	public function Song($a,$b,$c,$d)
	{
		$this->title = $a;
		$this->time = $b;
		$this->link = $c;
		$this->description = $d;
	}
}
class Album
{
	public $title,$release,$link,$image,$description;
	
	public function Album($a, $b, $c, $d, $e)
	{
		$this->title = $a;
		$this->release = $b;
		$this->link = $c;
		$this->image = $d;
		$this->description = $e;
	}
}
class Artist
{
	public $title,$reference,$demographic,$period,$link,$image,$decription;
	
	public function Artist($a, $b, $c, $d, $e, $f, $g)
	{
		$this->title = $a;
		$this->reference = $b;
		$this->demographic = $c;
		$this->period = $d;
		$this->link = $e;
		$this->image = $f;
		$this->description = $g;
	}
}
class Mania_db
{
	public $userkey = '3a7ae5b618fffe1766bdb55cf6ed5de0';

	public $result;
	public function Mania_db()
	{
	}
	public function getArtistInfo($search)
	{
		$this->result = array();
		
		$url = 'http://www.maniadb.com/api/search.asp?key='.$this->userkey.'&target=music&itemtype=artist&option=artist&query='.urlencode($search);
		$response = file_get_contents($url);
		$phpobject = simplexml_load_string($response);
		
		$channel = $phpobject->channel;

		if(count($channel->item) == 0)
		{
			return;
			//die('<item><title>검색 결과가 없습니다.</title><thumbnail></thumbnail><director></director><homepage></homepage></item>');
		}
		
		foreach($channel->item as $value)
		{
			$a = $value->title;
			$b = $value->reference;
			$c = $value->demographic;
			$d = $value->period;
			$e = $value->link;
			$f = $value->image;
			$g = $value->description;
			
			$artist = new Artist($a,$b,$c,$d,$e,$f,$g);
			
			array_push($this->result,$artist);
		}
	}
	public function printArtistInfo()
	{
	
		if(count($this->result) == 0)
		{
			echo '<item><title>검색결과가 없습니다.</title><reference></reference><demographic></demographic><period></period><link></link><image></image><description></description></item></result>';
		}
		else
		{
			foreach($this->result as $artist)
			{
				echo '<item>';
				echo '<title>a</title>';
				echo '<reference>a</reference>';
				echo '<demographic>a</demographic>';
				echo '<period>a</period>';
				echo '<link>a</link>';
				echo '<image>a</image>';
				echo '<description>a</description>';
				echo '</item>';

			/*
				echo '<item>';
				echo '<title>'.htmlspecialchars($artist->title).'</title>';
				echo '<reference>'.htmlspecialchars($artist->reference).'</reference>';
				echo '<demographic>'.htmlspecialchars($artist->demographic).'</demographic>';
				echo '<period>'.htmlspecialchars($artist->period).'</period>';
				echo '<link>'.htmlspecialchars($artist->link).'</link>';
				echo '<image>'.htmlspecialchars($artist->image).'</image>';
				echo '<description>'.htmlspecialchars($artist->description).'</description>';
				echo '</item>';
			*/
			}
		}
	}
	
	
	public function getSongInfo($search)
	{
		$this->result = array();
		
		$url = 'http://www.maniadb.com/api/search.asp?key='.$this->userkey.'&target=music&itemtype=song&option=song&query='.urlencode($search);
		$response = file_get_contents($url);
		$phpobject = simplexml_load_string($response);
		
		$channel = $phpobject->channel;

		if(count($channel->item) == 0)
		{
			return;
			//die('<item><title>검색 결과가 없습니다.</title><thumbnail></thumbnail><director></director><homepage></homepage></item>');
		}
		
		foreach($channel->item as $value)
		{
			$a = $value->title;
			$b = $value->time;
			$c = $value->link;
			$d = $value->description;
			
			$song = new Song($a,$b,$c,$d);
			
			array_push($this->result,$song);
		}
	}
	public function printSongInfo()
	{
		if(count($this->result) == 0)
		{
			echo '<item><title>검색결과가 없습니다.</title><time></time><link></link><description></description></item></result>';
		}
		else
		{
			foreach($this->result as $song)
			{
				echo '<item>';
				echo '<title>'.htmlspecialchars($song->title).'</title>';
				echo '<time>'.htmlspecialchars($song->time).'</time>';
				echo '<link>'.htmlspecialchars($song->link).'</link>';
				echo '<description>'.htmlspecialchars($song->description).'</description>';
				echo '</item>';
			}
		}
	}
	public function getAlbumInfo($search)
	{
		$this->result = array();
		
		$url = 'http://www.maniadb.com/api/search.asp?key='.$this->userkey.'&target=music&itemtype=album&option=album&query='.urlencode($search);
		$response = file_get_contents($url);
		$phpobject = simplexml_load_string($response);
		
		$channel = $phpobject->channel;

		if(count($channel->item) == 0)
		{
			return;
			//die('<item><title>검색 결과가 없습니다.</title><thumbnail></thumbnail><director></director><homepage></homepage></item>');
		}
		
		foreach($channel->item as $value)
		{
			$a = $value->title;
			$b = $value->release;
			$c = $value->link;
			$d = $value->image;
			$e = $value->description;
			
			$album = new Album($a,$b,$c,$d,$e);
			
			array_push($this->result,$album);
		}
	}
	public function printAlbumInfo()
	{
		if(count($this->result) == 0)
		{
			echo '<item><title>검색결과가 없습니다.</title><release></release><link></link><image></image><description></description></item>';
		}
		else
		{
			foreach($this->result as $album)
			{
				echo '<item>';
				echo '<title>'.$album->title.'</title>';
				echo '<release>'.$album->release.'</release>';
				echo '<link>'.$album->link.'</link>';
				echo '<image>'.$album->image.'</image>';
				echo '<description>'.$album->description.'</description>';
				echo '</item>';
			}
		}
	}
	
}

//main code
$music_db = new Mania_db();

//set request address
$search = $_GET['search'];
$type = $_GET['type'];

if($type == 'song')
{
	$music_db->getSongInfo($search);
	$music_db->printSongInfo();
}
else if($type == 'album')
{
	$music_db->getAlbumInfo($search);
	$music_db->printAlbumInfo();
}
else if($type == 'artist')
{
	$music_db->getArtistInfo($search);
	$music_db->printArtistInfo();	
}

?>