<?php
defined('_JEXEC') or die('Restricted access');
$document = &JFactory::getDocument();
$document->addStylesheet(JURI::base(true) . "/modules/".$module->module."/css/style.css");
$document->addScript(JURI::base(true)."/templates/protostar/js/jquery.carouFredSel-6.2.1-packed.js");


$id_div = $params->get("id-div");
$posicion = $params->get("posicion");
$texto = $params->get("texto");
$imagen = $params->get("imagen");
$ancho = $params->get("ancho","100%");
$alto = $params->get("alto","auto");
$overflow = $params->get("overflow");
$background_size = $params->get("background-size");
$repetir_imagen = $params->get("repetir-imagen");
$posicion_imagen = $params->get("posicion-imagen");


// GENERA VARIABLE STYLE
$style  = '#slider_header{';

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

if ($repetir_imagen){ 
	$style .= 'background-repeat:'. $repetir_imagen .';';
}

if ($posicion_imagen){ 
	$style .= 'background-position:'. $posicion_imagen .';';
}

$style .= '}';

/*
if ($imagen!=""){
	$document = JFactory::getDocument();
	$document->addStyleDeclaration($style);		
}
*/

$dbm=JFactory::getDBO();
$sql = 'SELECT * FROM #__content WHERE state=1 AND catid=2 ORDER BY RAND()';
$dbm->setQuery( $sql );
$items = $dbm->loadObjectList(); 

echo "<div id='slider_header'>";
	echo "<div id='slider_home_top'>";

	foreach ($items as $item){

		$imagenes = json_decode($item->images);
		$imagen   = $imagenes->image_fulltext;

		echo "<div class='item item-" . $item->id ."' data-image='". $imagen ."'>";
			echo "<div class='wrapper_content'>"."\n";
			if ($item->introtext){
			echo "<div class='contenido_texto'>" . $item->introtext . "</div>";
			};
			echo "</div>";
		echo "</div>";

	}
	echo "</div>";
	echo "<div id=\"bullet\" class=\"paginations\"></div>";
echo "</div>";
?>




    <?php if (count($items)>1){ ?>
    <script>
    jQuery(document).ready(function() {

	    jQuery('#slider_home_top').carouFredSel({
	        items  : {
	        	start 			: 1,
	        	visible			: 1
	        },
	        direction           : "left",
	        scroll : {
	            items           : 1,
	            fx				: "cover-fade",
	            easing          : "linear",
	            duration        : 1000,                         
	            pauseOnHover    : true,
	            onBefore: function(data) {
		        	var imagen = jQuery(data.items.visible).attr("data-image"); 
		        	jQuery('#slider_header').css({"background-image":"url("+imagen+")"});
				},
		        onAfter : function(data) {
		        	//var imagen = $(data.items.visible).attr("data-image"); 
		        	//$("#franja_imagen").css({"background-image":"url("+imagen+")"});
		        }
	        },
			circular: true,
			infinite: true,
			auto 	: true,
			padding : 0,
			width	: 960,
			height: 580,
		    pagination  : {
		        container       : "#bullet",
		        anchorBuilder   : function( nr ) {
		            return '<i class="fa fa-circle"></i>';
		        }
		    }

	    });



    });
    </script>
    <?php } ?>








