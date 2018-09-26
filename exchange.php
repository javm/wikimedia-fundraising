<?php
// connection info

class exchange {

    const HOST = "localhost";
    const USERNAME = "wikimedia";
    const PASSWORD = "dLMsdVVN1";

    const DB = "exchange";

    // PDO
    private $conn;

    function __construct(){

        $username = self::USERNAME;
        $password = self::PASSWORD;
        $host = self::HOST;
        $db = self::DB;
        $connect_uri = "mysql:host=$host;dbname=$db";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        
        $this->conn = new PDO(
            $connect_uri,
            $username,
            $password,
            $options
        );
    }
    
    function setRates($currency, $rate){
        try {
            $stmt =
                $this->conn->prepare("INSERT INTO exchange_rates(currency, rate) VALUES(:currency, :rate) ON DUPLICATE KEY UPDATE rate=VALUES(rate), ts=CURRENT_TIMESTAMP");
                
            $stmt->bindparam(":currency",$currency);
            $stmt->bindparam(":rate",floatval($rate));
            $stmt->execute();
            return true;
        }
        catch(PDOException $e){
            print $e;
            echo $e->getMessage();  
            return false;
        }
        
    }

    function getRate($currency){
        try {
            $q = "SELECT rate FROM exchange_rates WHERE currency=:currency LIMIT 1";
            $stmt =
                $this->conn->prepare($q);
            $stmt->bindparam(":currency", $currency);
            $stmt->execute();
            $exchange = $stmt->fetch();
            return $exchange['rate'];

        }
        catch(PDOException $e){
            echo $e->getMessage();  
            return false;
        }

    }


    function calculateUSD($s){
        $parts = explode(' ', $s);
        $currency = $parts[0];
        $amount = $parts[1];
        
        if(count($parts) != 2){
            throw new Exception("Not well formed input");
        }
        
        $rate = $this->getRate($currency);
        
        if(!$rate){
            throw new Exception("No currency with id $currency found");
        }
        $usd = floatval($amount) * $rate; 
        return "USD $usd";
    }


    public function getRemoteRates($url){
        $xml = simplexml_load_file($url);
        return $xml;
    }
    
    public function downloadRates($url){
        $xml = $this->getRemoteRates($url);
        $rates = $xml->conversion;
        foreach($rates as $r){
            $this->setRates($r->currency, $r->rate);
        }
    }

    
    public function convert($input){
        if(gettype($input) == 'string'){
            return $this->calculateUSD($input);
        }else if(gettype($input) == 'array'){
            $output = array();
            foreach ($input as $a){
                array_push($output, $this->calculateUSD($a));
            }
            return $output;
        }else{

            throw new Exception("Not well formed input");
        }
    }
}

?>