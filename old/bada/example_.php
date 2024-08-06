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
echo "[HTTP] Translating [$translate_string] German to English => ".$gt->german_to_english($translate_string)."\n";

       $gt = new Gtranslate;
	echo "[HTTP] Translating Korean to English => ".$gt->ko_to_eng("³ª")."\n";
	echo "zz";

	} catch (GTranslateException $ge)
 {
       echo $ge->getMessage();
 }

?>
