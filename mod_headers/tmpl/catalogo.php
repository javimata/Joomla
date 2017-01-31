<?php
defined('_JEXEC') or die('Restricted access');
$document = &JFactory::getDocument();
$document->addStylesheet(JURI::base(true) . "/modules/".$module->module."/css/style.css");

$id_div                = $params->get("id-div");
$posicion              = $params->get("posicion");
$texto                 = $params->get("texto");
$imagen                = $params->get("imagen");
$ancho                 = $params->get("ancho","100%");
$alto                  = $params->get("alto","auto");
$overflow              = $params->get("overflow");
$background_size       = $params->get("background-size");
$background_attachment = $params->get("background-attachment");
$repetir_imagen        = $params->get("repetir-imagen");
$posicion_imagen       = $params->get("posicion-imagen");
$customcode            = str_replace(chr(13), ",", $params->get("customcode"));

$catid = $_GET["catid"];

if ( !$catid ){

	$app    = JFactory::getApplication();
	$menus  = $app->getMenu();
	$itemId = $menus->getActive();
	$catid  = $itemId->params->get("category_id");

// echo $catid;
}

$customcode = str_replace("=", "=>", $customcode);
if ($customcode!=""):
function string2KeyedArray($string, $delimiter = ',', $kv = '=>') {
  if ($a = explode($delimiter, $string)) { // create parts
    foreach ($a as $s) { // each part
      if ($s) {
        if ($pos = strpos($s, $kv)) { // key/value delimiter
          $ka[trim(substr($s, 0, $pos))] = trim(substr($s, $pos + strlen($kv)));
        } else { // key delimiter not found
          $ka[] = trim($s);
        }
      }
    }
    return $ka;
  }
}

$customarray = string2KeyedArray($customcode);
// Si existe definicion de una imagen en el custom code
if ( $catid != "" && $customarray[$catid] != "" ){

	$imagenCustom = $customarray[$catid];

	if ( $imagenCustom !="" && JFILE::exists($imagenCustom) ){
		$imagen = $imagenCustom;
	}

}
endif;


// GENERA VARIABLE STYLE
$style  = '#' . $id_div . '{';

if ($imagen){
	$style .= 'background: url("'.JURI::base(true) .'/'.$imagen.'");';
}

$style .= 'width:'. $ancho .';';
$style .= 'height: '. $alto .';';

if ($overflow){ 
	$style .= 'overflow:'. $overflow .';';
}

if ($background_size){ 
	//$style .= 'background-attachment:fixed;';
	$style .= 'background-size:'. $background_size .';';
}

if ($background_attachment){
	$style .= 'background-attachment:'.$background_attachment.';';
}

if ($repetir_imagen){ 
	$style .= 'background-repeat:'. $repetir_imagen .';';
}

if ($posicion_imagen){ 
	$style .= 'background-position:'. $posicion_imagen .';';
}

$style .= '}';

if ($imagen!=""){
	$document = JFactory::getDocument();
	$document->addStyleDeclaration($style);		
}



echo "<div id='".$id_div."'>";
	echo "<div class='wrapper_content'>"."\n";
	if ($texto){
	echo "<div class='contenido_texto'>" . $texto . "</div>";
	};
	echo "</div>";
echo "</div>";
?>