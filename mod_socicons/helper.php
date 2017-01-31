<?php
/**
 * Helper class for Hello World! module
 * 
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @link http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
 * @license        GNU/GPL, see LICENSE.php
 * mod_helloworld is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
class modSocicons
{
    /**
     * @param array $params An object containing the module parameters
     * @access public
     */    

    public static function getIcons( &$params )
    {

        $iconos = $params->get("iconos");
        $iconos = json_decode($iconos);
        $icons = array();

        foreach ($iconos as $sub) 
        {
            foreach ($sub as $k => $v) 
            {
                $icons[$k][] = $v;
            }
        }
        return $icons;

    }



}