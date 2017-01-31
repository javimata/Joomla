<?php
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$module_id = $module->id;

// Obtener valores
$agrega_fontawesome = $params->get("agrega_fontawesome");
$agrega_nombre      = $params->get("agrega_nombre",0);
$url_fontawesome    = $params->get("url_fontawesome");
$agrega_css         = $params->get("agrega_css",1);
$color_base			= $params->get("color_base");
$color_fondo		= $params->get("color_fondo");
$colorear           = $params->get("colorear",0);
$agrega_fondo       = $params->get("agrega_fondo",0);
$iconos             = $params->get("iconos");
$moduleclass_sfx    = $params->get("moduleclass_sfx");


// Agrega el CSS de fontawesome tomando la url dada
if ( $agrega_fontawesome == 1 && $url_fontawesome != "" ){
	$document->addStylesheet($url_fontawesome);
}

// Agrega el CSS del modulo
if ($agrega_css==1){
	$document->addStylesheet(JURI::base(true) . "/modules/".$module->module."/css/style.css");
}

// Agrega la clase correspondiente para colorear los iconos
if ( $colorear == 1 ) { 
	$colorear = " colored"; 
}elseif ( $colorear == 2 ){
	$colorear = " colored-hover";
}else{
	$colorear = "";
}

if ( $agrega_fondo == 1 ){
	$clase_fondo = "circle";
}elseif ( $agrega_fondo == 2 ){
	$clase_fondo = "square";
}else{
	$clase_fondo = "";
}

if ( $color_base != ""){
	$style  = '.list-icons-socicons li i { color: ' . $color_base . '; }';
	$style .= '.list-icons-socicons li a.colored-hover i.circle { background: ' . $color_fondo . '; }';
	$style .= '.icons-'.$module_id. ' .fondo-icon { color: ' . $color_fondo . ' }';
	$document->addStyleDeclaration( $style );
}


echo '<div class="list-icons-socicons '.$moduleclass_sfx.' icons-' . $module_id . '">' . "\n";
echo '<ul>' . "\n";

$cual=0;
foreach ($icons as $icon => $icono) {

	if ( $icono[4] == 1 ) { $target = ' target="_blank"'; } else { $target = ''; }
	if ( $icono[3] != "" ) { $clase = $icono[3]; } else { $clase = ''; }
	?>

	<li class="li-<?php echo $icono[2]?>">
	<?php if ( $agrega_fondo == 1 || $agrega_fondo == 2 ): ?>
		<span class="fa-stack fa-lg con-fondo">
			<i class="fa fa-<?php echo $clase_fondo; ?> fondo-icon fondo-<?php echo $icono[2]; ?> fa-stack-2x"></i>
			<?php if ( $icono[1]!="" ): ?>
				<a href="<?php echo $icono[1]; ?>" <?php echo $target; ?> class="icono-social <?php echo $clase . ' ' . $colorear;?>">
			<?php endif; ?>
			<i class="fa fa-<?php echo $icono[2]?> icono-con-fondo fa-stack-1x"></i>
			<?php if( $icono[0] != "" && $agrega_nombre == 1 ): ?>
			<span class="icono-nombre"><?php echo $icono[0]; ?></span>
			<?php endif; ?>
			<?php if ( $icono[1]!="" ): ?></a><?php endif; ?>
		</span>
	<?php else: ?>

		<?php if ( $icono[1]!="" ): ?>
		<a href="<?php echo $icono[1]; ?>" <?php echo $target; ?> class="icono-social <?php echo $clase . ' ' . $colorear;?>">
		<?php endif; ?>
		<i class="fa fa-<?php echo $icono[2]?>"></i>
		<?php if( $icono[0] != "" && $agrega_nombre == 1 ): ?>
		<span class="icono-nombre"><?php echo $icono[0]; ?></span>
		<?php endif; ?>
		<?php if ( $icono[1]!="" ): ?></a><?php endif; ?>

	<?php endif; ?>
	</li>

	<?php
	$cual++;
}

echo '</ul>' . "\n";
echo '</div>' . "\n";