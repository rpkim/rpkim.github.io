<?

//11st shopping

//set request address
$userkey = '74dc0b4d99a4472e4690c12367e62a3f';
$request = 'http://openapi.11st.co.kr/openapi/OpenApiService.tmall?key='.$userkey.'&apiCode=ProductSearch&option=Categories&keyword='.$_GET['search'];

//get response(get source)
$response = file_get_contents($request);

$phpobject = simplexml_load_string($response);

if ($phpobject === false) {
   die('Parsing failed');
}

// Output the data
// SimpleXML returns the data as a SimpleXML object

//get channel -> item
$Products = $phpobject->Products;



//productId
foreach($Products->Product as $value) 
{
   echo "<Item>";
   echo "<title>".htmlspecialchars($value->ProductName)."</title>";
   echo "<store>".htmlspecialchars($value->Seller)."(11번가)</store>";
   echo "<categori></categori>";
   echo "<imageurl>".htmlspecialchars($value->ProductImage)."</imageurl>";
   echo "<price_min>".htmlspecialchars($value->SalePrice)."</price_min>";
   echo "<price_max>".htmlspecialchars($value->SalePrice)."</price_max>";
   echo "<link>".htmlspecialchars($value->DetailPageUrl)."</link>";
   echo "</Item>";
}
?>
