<?php
/**
 * Ordering Element
 *
 * @package     Joomla.Plugin
 * @subpackage  Fabrik.element.hits
 * @copyright   Copyright (C) 2025 Jlowcode Org - All rights reserved.
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

/**
 * 	Plugin element to render a tree of the data that user can select the order of the elements
 * 
 * @package     	Joomla.Plugin
 * @subpackage  	Fabrik.element.hits
 * @since       	4.0
 */
class PlgFabrik_ElementHits extends PlgFabrik_ElementList
{
	/**
	 * Check user can view the read only element OR view in list view
	 *
	 * @param   	String 		$view 		View list/form
	 *
	 * @return  	Bool
	 */
	public function canView($view='form') 
	{
		return false;
	}

	/**
	 * Trigger executed when a list record is accessed.
	 * 
	 * Increments the access (views) counter
	 * each time the record is loaded.
	 * 
	 * @return void
	 */
	public function onLoad() 
	{
		$db = Factory::getContainer()->get('DatabaseDriver');
		$app = Factory::getApplication();
		$input = $app->getInput();

		$name = $this->getElement()->name;
		$table = $this->getListModel()->getTable()->db_table_name;
		$rowId = $input->getInt('rowid');

		$query = $db->getQuery(true);
		$query->select($db->qn($name))
			->from($db->qn($table))
			->where($db->qn('id') . ' = ' . $db->q($rowId));
		$db->setQuery($query);
		$hits = $db->loadResult()+1;

		$query = $db->getQuery(true);
		$query->update($db->qn($table))
			->set($db->qn($name) . ' = ' . $db->q($hits))
			->where($db->qn('id') . ' = ' . $db->q($rowId));
		$db->setQuery($query);
		$db->execute();
	}
	
}