<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_related_items
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

//$list = modHomeProductos::getList($params);
$icons = modSocicons::getIcons($params);

require JModuleHelper::getLayoutPath('mod_socicons', $params->get('layout', 'default'));
