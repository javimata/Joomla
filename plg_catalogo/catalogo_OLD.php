<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Search.content
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Content search plugin.
 *
 * @since  1.6
 */
class PlgSearchCatalogo extends JPlugin
{
	/**
	 * Determine areas searchable by this plugin.
	 *
	 * @return  array  An array of search areas.
	 *
	 * @since   1.6
	 */
	public function onContentSearchAreas()
	{
		static $areas = array(
			'catalogo' => 'Catalogo'
		);

		return $areas;
	}

	/**
	 * Search content (articles).
	 * The SQL must return the following fields that are used in a common display
	 * routine: href, title, section, created, text, browsernav.
	 *
	 * @param   string  $text      Target search string.
	 * @param   string  $phrase    Matching option (possible values: exact|any|all).  Default is "any".
	 * @param   string  $ordering  Ordering option (possible values: newest|oldest|popular|alpha|category).  Default is "newest".
	 * @param   mixed   $areas     An array if the search it to be restricted to areas or null to search all areas.
	 *
	 * @return  array  Search results.
	 *
	 * @since   1.6
	 */
	public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		$db = JFactory::getDbo();
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$groups = implode(',', $user->getAuthorisedViewLevels());
		$tag = JFactory::getLanguage()->getTag();

		require_once JPATH_SITE . '/components/com_catalogo/router.php';
		require_once JPATH_ADMINISTRATOR . '/components/com_search/helpers/search.php';

		$searchText = $text;

		if (is_array($areas))
		{
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas())))
			{
				return array();
			}
		}

		$sContent = $this->params->get('search_content', 1);
		$sArchived = $this->params->get('search_archived', 1);
		$limit = $this->params->def('search_limit', 50);

		$nullDate = $db->getNullDate();
		$date = JFactory::getDate();
		$now = $date->toSql();

		$text = trim($text);

		if ($text == '')
		{
			return array();
		}

		switch ($phrase)
		{
			case 'exact':
				$text = $db->quote('%' . $db->escape($text, true) . '%', false);
				$wheres2 = array();
				$wheres2[] = 'a.title LIKE ' . $text;
				$wheres2[] = 'a.introtext LIKE ' . $text;
				$wheres2[] = 'a.descripcion LIKE ' . $text;
				$where = '(' . implode(') OR (', $wheres2) . ')';
				break;

			case 'all':
			case 'any':
			default:
				$words = explode(' ', $text);
				$wheres = array();

				foreach ($words as $word)
				{
					$word = $db->quote('%' . $db->escape($word, true) . '%', false);
					$wheres2 = array();
					$wheres2[] = 'LOWER(a.title) LIKE LOWER(' . $word . ')';
					$wheres2[] = 'LOWER(a.introtext) LIKE LOWER(' . $word . ')';
					$wheres2[] = 'LOWER(a.descripcion) LIKE LOWER(' . $word . ')';
					$wheres[] = implode(' OR ', $wheres2);
				}

				$where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
				break;
		}

		switch ($ordering)
		{
			// case 'oldest':
			// 	$order = 'a.created ASC';
			// 	break;

			// case 'popular':
			// 	$order = 'a.hits DESC';
			// 	break;

			// case 'alpha':
			// 	$order = 'a.title ASC';
			// 	break;

			// case 'category':
			// 	$order = 'c.title ASC, a.title ASC';
			// 	break;

			// case 'newest':
			default:
				$order = 'a.title DESC';
				break;
		}

		$rows = array();
		$query = $db->getQuery(true);

		// Search articles.
		if ($sContent && $limit > 0)
		{
			$query->clear();

			$query->select('a.title AS title, a.categoria, a.alias AS alias_com, a.id AS id_com')
				->select($query->concatenate(array('a.introtext', 'a.descripcion')) . ' AS text')
				->select('c.title AS section')

				->from('#__catalogo AS a')
				->join('INNER', '#__categories AS c ON c.id=a.categoria')
				->where(
					'(' . $where . ') AND a.state=1 AND c.published = 1 '
				)
				//->group('a.id, a.title, a.categoria, a.introtext, a.descripcion, c.title, a.alias, c.alias, c.id')
				->order($order);

			$db->setQuery($query, 0, $limit);
			try
			{
				$list = $db->loadObjectList();
			}
			catch (RuntimeException $e)
			{
				$list = array();
				JFactory::getApplication()->enqueueMessage(JText::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
			}
			$limit -= count($list);

			if (isset($list))
			{
				foreach ($list as $key => $item)
				{
					$list[$key]->href = JRoute::_('index.php?option=com_catalogo&view=producto&id='.(int) $item->id_com."&alias=".$item->alias_com);
				}
			}

			$rows[] = $list;
		}

		// echo $query;
		$results = array();

		if (count($rows))
		{
			foreach ($rows as $row)
			{
				$new_row = array();

				foreach ($row as $article)
				{
					if (SearchHelper::checkNoHtml($article, $searchText, array('text', 'title')))
					{
						$new_row[] = $article;
					}
				}

				$results = array_merge($results, (array) $new_row);
			}
		}

		return $results;
	}
}
