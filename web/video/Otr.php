<?php
class OtrLine {
    public $url;
    public $title;
    public $station;
    public $text;
    public $icons;
    public $image;
    public $timestamp;
}

class Otr {
    private $email;
    private $password;
    
	public function __construct($config_filename) {
	    $ini_array = parse_ini_file($config_filename, true);
        $this->email = $ini_array['otr']['otr_email'];
        $this->password = $ini_array['otr']['otr_password'];
	}
	
	/**
	 * @return string
	 */
	protected function retrieve($otr_url) {
	    $context = stream_context_create(['http' => ['header' => 
	        'Cookie: otr_email='.$this->email.'; otr_password='.$this->password.';'
	    ]]);
	    return $content = file_get_contents($otr_url, false, $context );;
	}
	
	/**
	 * @return string
	 */
	public function getBestVideoUrl($otr_download_url) {
	    $content = $this->retrieve($otr_download_url);
	    if(!preg_match_all('/a href=\'(http.+download[^\']+)/', $content, $res)) {
	        throw new Exception('no download link');
        }

        $scores = [
        #    'HQ.avi',
        #    'HQ.cut.avi',
            'mp4',
        #    'avi',
        #    'cut.avi',
            'cut.mp4',
            'HQ.cut.mp4'
        ];

        $scoredList = [];
        foreach ($res[1] as $url) {
	        $piece = explode('.mpg.', $url);
	        $score = array_search($piece[1], $scores);
	        $map[$url] = $score === false ? -1 : $score;
        }

        arsort($map);
        return key($map);
	}

	public function getSource($filename) {
		$finished = file_get_contents(__DIR__ . '/' . $filename.'.json');
		$new = [];
		foreach (json_decode($finished) as $row) {
			$line = new OtrLine();
			foreach ($row as $field => $value) {
				$line->$field = $value;
			}
			$new[] = $line;
		}
		return $new;
	}

    /**
	 * @return OtrLine[]
	 */
	public function getFinished() {
        $content = $this->retrieve('https://www.onlinetvrecorder.com/v2/?go=list&tab=search&preset=2&saveorder=beginn%20DESC');

        if(!preg_match_all('/tdstation.+(https:\/\/static[^\']+)/', $content, $station)) {
	        throw new Exception('no station');
        }

        if(!preg_match_all('/(go=download[^\']+)[^>]+>(.+)<\/a/', $content, $url_title)) {
	        throw new Exception('no title');
        }

        if(!preg_match_all('/spanlongtext[^>]+>([^<]+)/', $content, $text)) {
	        throw new Exception('no text');
        }

        if(!preg_match_all('/tdquickformats[^>]+>(.+)<\/td>/siU', $content, $icons)) {
	        //TODO: extract just images
	        throw new Exception('no icons');
        }

        if(!preg_match_all('/listimagetd.+(https?:\/\/[^\']+)/', $content, $images)) {
	        throw new Exception('no images');
        }

        if(!preg_match_all('/>(\d{1,2}\.\d{1,2}\.\d{2,4})<\/td>/', $content, $dates)) {
	        throw new Exception('no dates');
	}
	$lines = [];
        for($i=0; $i<count($url_title[1]); $i++) {
            $line = new OtrLine();
            $line->url = 'https://www.onlinetvrecorder.com/v2/?' . $url_title[1][$i];
            $line->title = $url_title[2][$i];
            $line->text = $text[1][$i];
            $line->station = $station[1][$i];
	    $line->timestamp = strtotime(preg_replace('/(\d{2})$/', '20$1', $dates[1][$i]));
            $line->image = $images[1][$i];
            $line->icons = $icons[1][$i];
	    $lines[] = $line;
        }
        return $lines;
	}
}

