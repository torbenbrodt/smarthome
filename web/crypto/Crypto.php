<?php
/*
[crypto]
bitcoin=2017-09-12;0.26037;1155.36
bitcoin-cash=2017-11-11;0.05143;71.24
ethereum=2017-11-25:0.23196368;115.75
*/
class CryptoCurrency {
    public $id = '';
    public $name = '';
    public $balance = 0.0;
    public $payed_usd = 0.0;
    public $value_usd = 0.0;
    public $percentage = 0.0;
    public $balance_history = [];
}

class Crypto {
    protected $owned;
    
	public function __construct($config_filename) {
	
	    // parse file
	    $ini_array = parse_ini_file($config_filename, true);
	    foreach($ini_array['crypto'] as $currency_id => $line) {
	        $currency = new CryptoCurrency();
	        $currency->id = $currency_id;
	        
	        foreach (explode(';', $line) as $payin) {
	            $payin = explode(',', $payin);
	            $currency->balance += $payin[1];
	            $currency->payed_usd += $payin[2];
	            $currency->balance_history += [$payin[0] => $payin[1]];
	        }
	     
	        $this->owned[$currency_id] = $currency;
	    }
	    
	    // parse api
	    $url = 'https://api.coinmarketcap.com/v1/ticker/';
	    $content = json_decode(file_get_contents($url));
	    foreach ($content as $line) {
	        if (isset($this->owned[$line->id])) {
	            $currency = $this->owned[$line->id];
	            $currency->name = $line->name;
	            $currency->value_usd += $currency->balance * $line->price_usd;
	            $currency->percentage = round(($currency->value_usd / $currency->payed_usd * 100) - 100, 2);
	        }
	    }
	}
	
	public function getCurrencies() {
	    return $this->owned;
	}
}

