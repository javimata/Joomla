<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

$user = JFactory::getUser();
$lang = JFactory::getLanguage();

	$menu->addChild(new JMenuNode("Neill School", '#'), true);
	$createContent = $user->authorise('core.create', 'com_content');
	$menu->addChild(new JMenuNode("Administrar Padres", 'index.php?option=com_users&view=users', 'class:pages'));
	$menu->addChild(new JMenuNode("Administrar Alumnos", 'index.php?option=com_alumnos', 'class:page'));
	$menu->addChild(new JMenuNode("Sistema de reportes", 'http://www.neillschool.com/MC_campaigns.php',null,false,'blank'));

	$menu->getParent();
