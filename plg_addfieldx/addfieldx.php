<?php
// no direct access
defined('JPATH_BASE') or die;

jimport('joomla.utilities.date');

class plgContentAddfieldx extends JPlugin
{
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}

	function onContentPrepareForm($form, $data)
	{
		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');
			return false;
		}
		
		if ( in_array($form->getName(), array('com_content.article'))==true ) {
			$document = JFactory::getDocument();
			JForm::addFormPath(dirname(__FILE__) . '/forms');
			$form->loadFile('extrafields', false);
			return true;
		}elseif ( in_array($form->getName(), array('com_categories.categorycom_content'))==true ){
			$document = JFactory::getDocument();
			JForm::addFormPath(dirname(__FILE__) . '/forms');
			$form->loadFile('extrafieldscats', false);
			return true;

		}
	}
}


