<?php
echo "test";
require("GTranslate.php");

/**
* Example using RequestHTTP
*/

$translate_string = "Das ist wundersch?n";
$test = "우리";

try{
       $gt = new Gtranslate;
	echo "[HTTP] Translating Korean to English => ".$gt->ko_to_en($test)."\n";
	echo $gt->it_to_en("Ciao mondo");

	} catch (GTranslateException $ge)
 {
       echo $ge->getMessage();
 }

?>
