<?php

class Chromecast {
    private $email;
    private $password;
    
	public function __construct($config_filename) {
	    $ini_array = parse_ini_file($config_filename, true);
        $this->api_user = $ini_array['transmitter']['api_user'];
        $this->api_pass = $ini_array['transmitter']['api_pass'];
        $this->api_url = $ini_array['transmitter']['api_url'];
	}
	
	public function stream($url) {
	    $this->post("/chromecast/stream?url=" . $url);
	}

	public function post($url) {
        $auth = base64_encode($this->api_user.':'.$this->api_pass);
        $context = stream_context_create(['http' => ['header' => "Authorization: Basic $auth", 'method' => 'POST']]);
        file_get_contents($this->api_url . $url, false, $context );
	}
}

