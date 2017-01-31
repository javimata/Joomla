<?php
defined('_JEXEC') or die('Restricted access');


$clase 				= $params->get("moduleclass_sfx");
$id_mapa			= $params->get("id_contenedor");
$muestra_titulo 	= $params->get("muestra_titulo",1);
$iframe				= $params->get("iframe");
$muestra_marker		= $params->get("muestra_marker",1);
$titulo_marker		= $params->get("titulo_marker");
$muestra_texto		= $params->get("muestra_texto");
$texto_caja			= $params->get("texto_caja");
$agregar_script		= $params->get("agregar_script");
$script_google		= $params->get("script_google");
$latitud			= $params->get("latitud");
$longitud			= $params->get("longitud");
$alto				= $params->get("alto");
$ancho				= $params->get("ancho");
$zoom				= $params->get("zoom");


if($agregar_script):
	$document = &JFactory::getDocument();
	$document->addScript($script_google);
endif;

if ($ancho!="" || $alto!=""){
	$document = JFactory::getDocument();
	$style = '.'.$clase.' {'
        . 'width:'.$ancho.';'
        . 'height:'.$alto.';'
        . '}'; 
	$document->addStyleDeclaration($style);		
}


?>

<div class="<?php echo $clase; ?>" id="<?php echo $id_mapa; ?>">

	<?php echo $iframe; ?>

</div>



<script>
(function($)
{
	$(document).ready(function()
	{

		function initialize() {

		  var styles =   [
		    {
		      stylers: [
		        { hue: '#00ffe6' },
		        { saturation: -20 }
		      ]
		    },{
		      featureType: 'road',
		      elementType: 'geometry',
		      stylers: [
		        { lightness: 100 },
		        { visibility: 'simplified' }
		      ]
		    },{
		      featureType: 'road',
		      elementType: 'labels',
		      stylers: [
		        { visibility: 'off' }
		      ]
		    }
		  ];


			var styleArray = [
			  {
			    featureType: "all",
			    stylers: [
			      { saturation: -80 }
			    ]
			  },{
			    featureType: "road.arterial",
			    elementType: "geometry",
			    stylers: [
			      { hue: "#00ffee" },
			      { saturation: 50 }
			    ]
			  },{
			    featureType: "poi.business",
			    elementType: "labels",
			    stylers: [
			      { visibility: "off" }
			    ]
			  }
			];

		  var myLatlng = new google.maps.LatLng(<?php echo $latitud; ?>, <?php echo $longitud; ?>);
		  var mapOptions = {
		    zoom: <?php echo $zoom; ?>,
		    center: myLatlng,
		    disableDefaultUI: true,
		    mapTypeControl: false,
		    mapTypeControlOptions: {
		        style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
		        position: google.maps.ControlPosition.RIGHT_TOP
		    },
   		    zoomControl: false,
		    zoomControlOptions: {
		        style: google.maps.ZoomControlStyle.LARGE,
		        position: google.maps.ControlPosition.RIGHT_CENTER
		    },
			streetViewControl: false,
			streetViewControlOptions: {
			position: google.maps.ControlPosition.RIGHT_CENTER
		    },
		    styles: styleArray
		  };

		var map = new google.maps.Map(document.getElementById('<?php echo $id_mapa; ?>'),mapOptions);

		<?php if($muestra_texto==1): ?>
		var contentString = '<div id="content_mapa"><?php echo preg_replace(array('/\r/', '/\n/'), '', $texto_caja); ?></div>';
		var infowindow = new google.maps.InfoWindow({ content: contentString	});
		<?php endif; ?>

		<?php if($muestra_marker==1): ?>
		var marker = new google.maps.Marker({
		  position: myLatlng,
		  map: map,
		  title:"<?php echo $titulo_marker; ?>"
		});

		google.maps.event.addListener(marker, 'click', function() {
		  infowindow.open(map,marker);
		});
		
		infowindow.open(map,marker);
		<?php endif; ?>
		
		google.maps.event.addDomListener(window, 'resize', function() {
		    map.setCenter(myLatlng);
		});

		}

		google.maps.event.addDomListener(window, 'load', initialize);
	})
})(jQuery);

</script>
    