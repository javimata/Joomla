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
class modFormularios
{
    /**
     * @param array $params An object containing the module parameters
     * @access public
     */    
    public static function getList( $params )
    {
        //return 'Hello, World!';
        //echo $params->get("titulo");

    }


    public static function getCampos( &$params )
    {


        $campos_lista = $params->get("campo_nombre");

        if ($campos_lista){

            $campos = explode(PHP_EOL, $campos_lista);

        }

        return $campos;

    }

}
?>