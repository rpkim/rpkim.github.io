<?
  header ('Content-Type: text/xml; charset=utf-8'); 
  echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>";
?><result><?
//if don't have search value.
if ($_GET['search'] == '')
{
    die('need search value');
}
?>
<? include $_SERVER['DOCUMENT_ROOT']."/bada/en/main/lastfm.php"; ?>
<? include "music_1.php"; ?>
</result>