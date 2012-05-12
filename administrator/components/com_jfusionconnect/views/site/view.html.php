<?php

/**
 * This is view file for cpanel
 *
 * PHP version 5
 *
 * @category   JFusionConnect
 * @package    ViewsFront
 * @subpackage Frameless
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * load the JFusion framework
 */
jimport('joomla.application.component.view');
jimport( 'joomla.utilities.date' );
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusionconnect' . DS . 'models' . DS . 'model.ui.php';
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusionconnect' . DS . 'models' . DS . 'model.site.php';

/**
 * Class that handles the framelesss integration
 * 
 * @category   JFusionConnect
 * @package    ViewsFront
 * @subpackage Frameless
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */
class jfusionconnectViewsite extends JView
{
     /**
     * displays the view
     *
     * @param string $tpl template name
     * 
     * @return string html output of view
     */
    function display($tpl = null)
    {
		JHTML::_('behavior.tooltip');
		$id = JRequest::getVar('id');
		$task = JRequest::getVar('task');
    	
		if ($id && $task == 'edit') {
	    	$site = JFusionConnectSite::getInstance($id);
	    	
			$result = $site->get();
	    	if ($result) {
		    	$result = $result[0];
		    	$realm = $result->realm;
	    	}
		}

//$document = JFactory::getDocument();
//echo $document->getLanguage ()  ;

//	var_dump(JFactory::getLanguage()->getKnownLanguages());
/*
			$user	=& JFactory::getUser();
			
			$test= $user->getParam ('language', );
			echo $test;
			die();
			echo $language;
*/
//die();
		jimport('joomla.registry.registry');
		$reg = new JRegistry();
		if (isset($result->params) ) {
			$reg->loadINI($result->params);
		}

		$document = JFactory::getDocument();
    	$text = $reg->getValue('text');
    	
    	$language = $reg->getValue('language',$document->getLanguage());
    	
		jimport('joomla.language.helper');
		$languages = JLanguageHelper::createLanguageList($language, JPATH_SITE, true);
		array_unshift($languages, JHTML::_('select.option', '', '- '.JText::_('SYSTEM_LANGUAGE').' -'));

		$language = JHTML::_('select.genericlist',  $languages, 'site[language]', 'class="inputbox"', 'value', 'text', $language, 'site[language]' );

    	$this->assignRef('id', $id);
    	$this->assignRef('language', $language);
    	$this->assignRef('realm', $realm);
    	
    	$editor = JFactory::getEditor();
    	$text = $editor->display('site[text]', $text, 500, 250, 50, 5);
    	$this->assignRef('text', $text);
    	
		JToolBarHelper::title(JText::_('SITE'));
		JToolBarHelper::save('savesite');
		JToolBarHelper::back();
        parent::display($tpl);
    }
}