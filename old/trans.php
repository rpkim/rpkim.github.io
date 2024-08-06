<?

 $a = "³Ê";
 echo("<script language=javascript> gTranslate(\"$a\");</script>");

?>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>    
<script type="text/javascript">    
google.load("language", "1");   

function gTranslate(original) {
 var text = original.value;     
 google.language.detect(text, function(result) {
  if (!result.error && result.language) {          
   google.language.translate(text, result.language, "en",                                    
   function(result) {          
           if (result.translation) {
		alert(result.translation);
		return result.translation;
    }          
   });        
  }      
 });
}  

</script>