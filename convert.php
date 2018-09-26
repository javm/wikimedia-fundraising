#!/usr/bin/php -q
<?php
include_once('./exchange.php');
$exchange = new exchange();
$rates = 'https://wikitech.wikimedia.org/wiki/Fundraising/tech/Currency_conversion_sample?ctype=text/xml&action=raw';


if($argc == 2){
    $opt = intval($argv[1]);
    
    switch($opt) {
    case 1:
        $xml = $exchange->getRemoteRates($rates);
        echo $xml->asXML();
        break;
    case 2:
        $xml = $exchange->getRemoteRates($rates);
        echo var_dump($xml);
        break;
    case 3:
        $exchange->downloadRates($rates);
        echo "All rates saved to the exchange database\n";
        break;
    default:
        $input = $argv[1];
        if(preg_match('/array/', $input)){
            eval("\$input = $input;");
            $out = $exchange->convert($input); 
            //var_dump($out);
            echo "array('".implode("','", $out)."')\n";
        }else{
            $out = $exchange->convert($input); 
            echo "$out\n";
        }
        
    }
}else{
    echo "Bad number of arguments!\n";
}
?>