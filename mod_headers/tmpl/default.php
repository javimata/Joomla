<?php
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$document->addStylesheet(JURI::base(true) . "/modules/".$module->module."/css/style.css");


$id_div = $params->get("id-div");
$posicion = $params->get("posicion");
$texto = $params->get("texto");
$imagen = $params->get("imagen");
$ancho = $params->get("ancho","100%");
$alto = $params->get("alto","auto");
$overflow = $params->get("overflow");
$background_size = $params->get("background-size");
$background_attachment = $params->get("background-attachment");
$repetir_imagen = $params->get("repetir-imagen");
$posicion_imagen = $params->get("posicion-imagen");


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