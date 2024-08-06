<?php
echo "test";
require("GTranslate.php");

/**
* Example using RequestHTTP
*/

$translate_string = "Das ist wundersch?n";
$test = "³ª";

try{
       $gt = new Gtranslate;
	echo "[HTTP] Translating Korean to English => ".$gt->ko_to_en("³ª")."\n";
	echo $gt->it_to_en("Ciao mondo");

	} catch (GTranslateException $ge)
 {
       echo $ge->getMessage();
 }

?>
