<?php

// error_reporting(E_ALL);
// ini_set("display_errors", 1);

defined( '_JEXEC' ) or die( 'Restricted access' );
 
// require_once JPATH_SITE .  '/components/com_catalogo/helpers/route.php';
// JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/route.php');
 
class PlgSearchCatalogo extends JPlugin
{


	function onContentSearchAreas()
	{
		static $areas = array(
			'catalogo' => 'Catalogo'
		);
		return $areas;
	}
 

	function onContentSearch( $text, $phrase='', $ordering='', $areas=null )
	{

		$db = JFactory::getDbo();
 
		// If the array is not correct, return it:
		if (is_array( $areas )) {
			if (!array_intersect( $areas, array_keys( $this->onContentSearchAreas() ) )) {
				return array();
			}
		}

 
		// Now retrieve the plugin parameters like this:
		$limit = $this->params->get('search_limit', 20 );
 
		// Use the PHP function trim to delete spaces in front of or at the back of the searching terms
		$text = trim( $text );
 
		// Return Array when nothing was filled in.
		if ($text == '') {
			return array();
		}
 
		$wheres = array();
		switch ($phrase) {
 
			// Search exact
			case 'exact':
				$text		= $db->Quote( '%'.$db->escape( $text, true ).'%', false );
				$wheres2 	= array();
				$wheres2[] 	= 'LOWER(a.title) LIKE '.$text;
				$where 		= '(' . implode( ') OR (', $wheres2 ) . ')';
				break;
 

			// Set default
			default:
				$words 	= explode( ' ', $text );
				$wheres = array();
				foreach ($words as $word)
				{
					$word = $db->quote('%' . $db->escape($word, true) . '%', false);
					$wheres2 	= array();
					$wheres2[] 	= 'LOWER(a.title) LIKE '.$word;
					$wheres2[] 	= 'LOWER(a.introtext) LIKE '.$word;
					$wheres2[] 	= 'LOWER(a.descripcion) LIKE '.$word;
					$wheres[] 	= implode( ' OR ', $wheres2 );
				}
				$where = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
				break;
		}
 
		$order = 'a.title ASC';
 

		$query	= $db->getQuery(true);
		$query->select('a.title AS title, a.categoria AS catid, a.id AS id, a.alias AS alias, a.introtext, a.imagen, a.imagen_descripcion, a.images, c.title AS category, c.path, c.alias AS alias_cat, c.level AS catlevel');
				// $query->select($query->concatenate(array($this->db->Quote($section), 'c.title'), " / ").' AS section');
				$query->from('#__catalogo AS a');
				$query->join('INNER', '#__categories as c ON c.id = a.categoria');
				$query->where('('. $where .')');
				$query->order($order);
 
		// Set query
		$db->setQuery( $query, 0, $limit );
		$rows = $db->loadObjectList();

		// The 'output' of the displayed link. Again a demonstration from the newsfeed search plugin
		foreach($rows as $key => $row) {

			$cat     = $row->catid;
			$cattree = array();
			for ($i=$row->catlevel; $i > 0; $i--) { 
				$dbc =& JFactory::getDBO();
				$queryc = 'SELECT alias, parent_id FROM #__categories WHERE id = ' . $cat;
				$db->setQuery($queryc);  
				$ci = $dbc->loadObject(); 

				$cattree[] .= $ci->alias;
				$cat = $ci->parent_id;
	 		}

	 		$url_cats = "";
	 		foreach (array_reverse($cattree) as $ctree) {
	 			$url_cats .= "/" . $ctree;
	 		}

			// $rows[$key]->href = JRoute::_('index.php?option=com_catalogo&view=producto&catid='.$row->catid.'&id='.(int) $row->id);
			// $rows[$key]->href = JRoute::_(CatalogoHelperRoute::getProductRoute($row->id, 0,$row->alias));
			$rows[$key]->href = "." . $url_cats . "/producto/".$row->id."/".$row->alias;
		}
 
	//Return the search results in an array
	$results= $rows;
	return $results;
	}
}