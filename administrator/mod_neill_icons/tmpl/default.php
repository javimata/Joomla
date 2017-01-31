<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

// JHtmlBootstrap::loadCss();
?>

<div id="sppagebuilder-wrap" class="clearfix">
	<div class="icon-wrapper">
		<div class="icon">
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=users'); ?>">
				<img alt="Administrar Padres" src="<?php echo JURI::root(true); ?>/administrator/modules/mod_neill_icons/tmpl/images/padres.jpg" />
				<span>Padres</span>
			</a>
		</div>
	</div>
	<div class="icon-wrapper">
		<div class="icon">
			<a href="<?php echo JRoute::_('index.php?option=com_alumnos'); ?>">
				<img alt="Administrar Alumnos" src="<?php echo JURI::root(true); ?>/administrator/modules/mod_neill_icons/tmpl/images/alumnos.jpg" />
				<span>Alumnos</span>
			</a>
		</div>
	</div>
</div>

