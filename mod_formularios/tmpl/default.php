<?php
defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
// $document->addStylesheet(JURI::base(true) . "/modules/".$module->module."/css/style.css");
// $document->addScript(JURI::base(true) . "/modules/".$module->module."/js/scripts.js");

//URL de procesamiento del envio del form
$link_proceso 		= JURI::base() . "modules/".$module->module."/bin/process.php";


//Obtiene el valor de ID si el modulo es insertado en sitios internos
$modulo_id 			= $module->id;


//Obtener parametros
$titulo              = $params->get("titulo");
$texto               = $params->get("texto");
$subtexto            = $params->get("subtexto");
$texto_btn           = $params->get("texto_btn");
$container_btn_class = $params->get("container_btn_class");
$btn_class           = $params->get("btn_class");
$imagen              = $params->get("imagen");
$UsaJQuery           = $params->get("UsaJQuery",0);
$texto_btn           = $params->get("texto_btn");
$className           = $params->get("className");
$idName              = $params->get("idName");
$texto_aviso         = $params->get("texto_aviso");
$link_aviso          = $params->get("link_aviso");
$header_tag          = $params->get("header","h3");
$descargar_archivo   = $params->get("descargar_archivo",0);
$requiere_aviso      = $params->get("requiere_aviso",0);
$muestra_error       = $params->get("muestra_error",0);

//Agrega jQuery al header, no verifica si ya fue agregado
if($UsaJQuery==1){
	$document->addScript("http://code.jquery.com/jquery-1.9.1.js");
}


//Cambio de tags
$arreglo_cambia 	= array("[","]");
$arreglo_cambia_por = array("<",">");

$titulo_form 		= str_replace($arreglo_cambia,$arreglo_cambia_por,$titulo);
$texto       		= str_replace($arreglo_cambia,$arreglo_cambia_por,$texto);
$subtexto    		= str_replace($arreglo_cambia,$arreglo_cambia_por,$subtexto);
$texto_aviso 		= str_replace($arreglo_cambia,$arreglo_cambia_por,$texto_aviso);
$texto_btn   		= str_replace($arreglo_cambia,$arreglo_cambia_por,$texto_btn);


//CAMPOS
$tipo_etiqueta= $params->get("tipo_etiqueta");

//Agregar script para placeholder
if( $tipo_etiqueta==2 || $tipo_etiqueta==3 ){
	$document->addScript(JURI::base(true) . "/modules/".$module->module."/js/placeholders.min.js");
}


// obtener seccion o sitio actual
$app     			= JFactory::getApplication();
$menu 	 			= $app->getMenu();
$seccion 			= $menu->getActive()->title;

// Si se agrega una imagen se inserta un DIV para poner la imagen como fondo de dicho DIV
/*
if ($imagen!=""){
	
	list($ancho, $alto) = getimagesize( JPATH_SITE .'/' . $imagen );

	$document = JFactory::getDocument();
	$style = '.imagen_boletin {'
        . 'background: url("'. JPATH_SITE .'/' . $imagen . '") no-repeat;'
        . 'width:'.$ancho.'px;'
        . 'height:'.$alto.'px;'
        . '}'; 
	$document->addStyleDeclaration($style);		
}
*/

echo "<div class='".$className."' id='".$idName."'>";

	echo "<div class='contenido_form'>";

	//Agrega imagen seleccionada
	if ($imagen) { echo "<div class='imagen_boletin'><img src='". JURI::BASE() . $imagen ."'></div>"; }

	echo "<form action='".$link_proceso."' name='form_".$className."' role='form' id='form_".$idName."' data-id='".$idName."'>"."\n";
	echo "<div id='caja_resultado_".$idName."'>"."\n";

	if ( $titulo_form != "" || $texto != "" ):
	echo "<div class='header-form'>\n";
	echo "<div class='titulo'><$header_tag>".$titulo_form."</$header_tag></div>"."\n";
	//Agrega texto
	if ($texto) { echo "<div class='texto'>".$texto."</div>"."\n" ;}

	if ($subtexto) { echo "<div class='subtexto'>".$subtexto."</div>"."\n" ;}

	echo "</div>\n";
	endif;

	echo "<div class='box-campos'>";
	// recorrido de campos
	foreach ($campos as $campo) {
		$cada        = explode("|", $campo);
		$nombre      = $cada[0];
		$tipo        = $cada[1];
		$valor       = $cada[2];
		$clase       = $cada[3];
		$obliga      = (int)$cada[4];
		if (isset($cada[5])):
			$placeholder = $cada[5];
		else:
			$placeholder = "";
		endif;
		$campoId     = JFilterOutput::stringURLSafe($nombre);
		$campoId     = str_replace("-", "_", $campoId);

		if (!$placeholder) { $placeholder = $nombre; }

		$muestra_obliga = "";
		if ($obliga == 1) { 
			$muestra_obliga = "required"; 
			$placeholder = $placeholder . " *";
		}

		$placeholder = str_replace($arreglo_cambia,$arreglo_cambia_por,$placeholder);

		if ($tipo=="text" || $tipo=="email"):

			echo "<div class='form-group ".$clase."'>";

			if ($tipo_etiqueta==1 || $tipo_etiqueta==3):
				echo "<label for='".$idName."_$campoId'>$placeholder";
				echo "</label>";
			endif;

			echo "<input type='$tipo' class='form-control' name='".$idName."_$campoId' id='".$idName."_$campoId' ";
			if($tipo_etiqueta==2 || $tipo_etiqueta==3){
				echo " placeholder='$placeholder'";
			}

			if($valor!=""){
				echo " value='$valor'";
			}

			echo $muestra_obliga;

			echo "></div>"."\n";

		//Si el tipo de campo es textarea
		elseif ($tipo=="textarea"):

			echo "<div class='form-group ".$clase."'>";

			if ($tipo_etiqueta==1 || $tipo_etiqueta==3){
				echo "<label for='".$idName."_$campoId'>$placeholder</label>\n";
			}

			echo "<$tipo class='form-control' name='".$idName."_$campoId' id='".$idName."_$campoId' ";
			if($tipo_etiqueta==2 || $tipo_etiqueta==3){
				echo " placeholder='$placeholder'";
			}

			echo $muestra_obliga;

			echo ">";
			if($valor!=""){
				echo " value='$valor'";
			}

			echo "</$tipo></div>"."\n";


		/**
		* Si el tipo de campo es select
		* Agrega las opciones en valor separadas por comas
		* Agrega como valor del option el texto limpio, sin espacios
		*/
		elseif ($tipo=="select"):

			echo "<div class='form-group ".$clase."'>";

			if ($tipo_etiqueta==1 || $tipo_etiqueta==3){
				echo "<label for='".$idName."_$campoId'>$placeholder</label>\n";
			}

			echo "<select class='form-control' name='".$idName."_$campoId' id='".$idName."_$campoId'>"."\n";
			echo "<option value=''>";
			
			if ($tipo_etiqueta==2):
				echo $placeholder;
			else:
				echo JTEXT::_("JGLOBAL_SELECT_AN_OPTION");
			endif;
			echo "</option>"."\n";

			// Obtiene el valor de las opciones del campo valor separado por comas (,)
			$posVal = strrpos($valor, ",");
			if ($posVal === false) {
				if ($valor!=""):
				echo "<option value='".JFilterOutput::stringURLSafe($valor)."'>$valor</option>"."\n";
				endif;

			}else{
				$valores = explode(",", $valor);
				foreach ($valores as $valor_select) {
					echo "<option value='".$valor_select."'>$valor_select</option>"."\n";
				}			
			}

			echo "</select>"."\n";
			echo "</div>"."\n";



		/**
		* Si el tipo de campo es component
		* Agrega las opciones en valor separadas por comas
		* Agrega como valor del option el texto limpio, sin espacios
		*/
		elseif ($tipo=="component"):

			$valores = explode(":", $valor);
			$valor_component = $valores[0];
			$valor_categoria = $valores[1];
			$valor_orden     = $valores[2];
			$sql = "";

			if ( $valor_component!="" ):

				$dbm=JFactory::getDBO();

				if ( $valor_component == "k2" ){
					$componente = "k2_items";
					$catfield   = "catid";
					$published  = "published";
					$orden      = "ordering";
					$idItem     = JRequest::getInt('id');

					if ( $valor_orden !="" ){ $orden = $valor_orden; }
					if ( $valor_categoria ){ $whereCat = " AND ".$catfield.'='.$valor_categoria; }
					$sql = 'SELECT * FROM #__'.$componente.' WHERE ( publish_down = "0000-00-00 00:00:00" OR publish_down >= NOW() ) AND '.$published.' = 1 '.$whereCat.' ORDER BY ' . $orden . ' ASC';
				}elseif ($valor_component == "content"){
					$componente = "content";
					$catfield   = "catid"; 
					$published  = "published";
					$orden      = "state";
					$idItem     = JRequest::getInt('id');
				}elseif ($valor_component == "contacts"){
					$componente = "contact_details";
					$catfield   = "catid"; 
					$published  = "published";
					$orden      = "ordering";
					$field      = 'name';

				}elseif ($valor_component == "categorias"){
					$componente = "categories";
					$catfield   = "extension";
					$published  = "published";
					$orden      = "lft";
					$field      = "title";

					if ( $valor_orden !="" ){ $orden = $valor_orden; }
					if ( $valor_categoria ){ $whereCat = " AND ".$catfield.'="'.$valor_categoria.'"'; }
					$sql = 'SELECT * FROM #__'.$componente.' WHERE '.$published.' = 1 '.$whereCat.' ORDER BY ' . $orden . ' ASC';

				}elseif ($valor_component == "alumnos"){
					$componente = "alumnos";
					$catfield   = "grado"; 
					$published  = "state";
					$orden      = "name";
					$field      = 'name';

				};

				if ( $sql == "" ):

					if ( $valor_orden !="" ){
						$orden = $valor_orden;
					}

					if ( $valor_categoria ){
						$whereCat = " AND ".$catfield.'='.$valor_categoria;
					}

					$sql = 'SELECT * FROM #__'.$componente.' WHERE '.$published.' = 1 '.$whereCat.' ORDER BY ' . $orden . ' ASC';
				endif;
				$dbm->setQuery( $sql );
				$lista = $dbm->loadObjectList(); 

			endif;

			echo "<div class='form-group ".$clase."'>";

			if ($tipo_etiqueta==1 || $tipo_etiqueta==3){
				echo "<label for='".$idName."_$campoId'>$placeholder</label>";
			}

			echo "<select class='form-control' name='".$idName."_$campoId' id='".$idName."_$campoId'>"."\n";
			echo "<option value=''>";
			
			if ($tipo_etiqueta==2):
				echo $placeholder;
			else:
				echo JTEXT::_("JGLOBAL_SELECT_AN_OPTION");
			endif;
			echo "</option>"."\n";

			foreach ($lista as $valor_select) {
				if ( $field == "name" ){
					$title_show = $valor_select->name;
				}else{
					$title_show = $valor_select->title;
				}

				if ( $idItem == $valor_select->id ) { $actualItem = "selected"; } else { $actualItem = ""; }

				echo "<option value='".$title_show."' $actualItem>".$title_show."</option>"."\n";
			}
		
			echo "</select>"."\n";
			echo "</div>"."\n";


		/**
		* Si el tipo de campo es checkbox
		* Agrega las opciones en valor separadas por comas
		* Agrega como valor del option el texto limpio, sin espacios
		*/
		elseif ($tipo=="checkbox"):

			$valores = explode(",", $valor);

			echo "<div class='checkbox-".JFilterOutput::stringURLSafe($nombre)." " . $clase . "'>";
			foreach ( $valores as $valor ):

				echo "<input type='checkbox' id='".JFilterOutput::stringURLSafe($nombre)."' name='".$nombre."'> <label for='".JFilterOutput::stringURLSafe($nombre)."'>" . $placeholder . "</label>";


			endforeach;
			echo "</div>";

		/**
		* Si el tipo de campo es hidden
		*/
		elseif ($tipo=="hidden"):

			echo "<input type='$tipo' name='".$idName."_$campoId' id='".$idName."_$campoId' ";

			if($valor!=""){

				if ( $valor == "userid" ){

					$user       = JFactory::getUser();
					$valor     = $user->get('id');

				}elseif ( $valor == "username" ){

					$user       = JFactory::getUser();
					$valor     = $user->get('name');

				}elseif ( $valor == "useruser" ){

					$user       = JFactory::getUser();
					$valor     = $user->get('username');

				}
				echo " value='$valor'";
			}

			echo ">"."\n";

		endif;
	}

	echo "<div class='form-group ".$container_btn_class."'>";
	echo "<div class='loading'><span class=\"fa fa-spinner fa-spin\"></span></div>";
	echo "<input type='hidden' name='module_id' value='".$modulo_id."' id='".$idName."_form_modulo'>"."\n";

	if ($seccion!=""){
	echo "<input type='hidden' name='area' value='".$seccion."' id='".$idName."_form_area'>"."\n";
	}

	if ($descargar_archivo==1):
	echo "<div class='link-descarga btn btn-default btn-lg'><a href='#' target='_blank'>Descargar folleto</a></div>";
	endif;

	echo "<div class='btn_container'>";
	echo "<button class='btn btn_".$idName." " . $btn_class. "' id='btn_id_".$idName."' type='submit'>".$texto_btn."</button>";
	echo "</div>";

	echo "</div>";

	if ($requiere_aviso==1):
  	echo "<div class='checkbox checkbox-aviso'><label><input type='checkbox'>"; 
	  	if ($link_aviso!="") { echo "<a href='$link_aviso'>"; }
  	echo $texto_aviso;
	  	if ($link_aviso!="") { echo "</a>"; }
  	echo "</label></div>";
  	endif;

  	if ($muestra_error==1):
	echo "<div class='". $idName ."_form_error'></div>\n";
	endif;

	echo "</div>";

	echo "</div>";
	echo "</form></div>"."\n";

echo "</div>";

?>