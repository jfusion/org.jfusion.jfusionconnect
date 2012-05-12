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
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusionconnect' . DS . 'models' . DS . 'model.ui.php';

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
class jfusionconnectViewrequest extends JView
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
		$mainframe =& JFactory::getApplication();
		$user =& JFactory::getUser();
		
		$params = &JComponentHelper::getParams('com_jfusionconnect');
		$message = JText::_('OPENID_USERPAGE');
	    $this->assignRef('message', $message);
	    
	    $id = JRequest::getInt('id',0);
		$logInstance =& JFusionConnectLog::getInstance();
	    $log = $logInstance->getByID($id);

		$debug = JFusionConnectUi::buildDebugRequest($log);
		if (!count($debug)) {
			$url = JRoute::_('index.php?option=com_jfusionconnect&view=userlog', false);
			$mainframe->redirect($url);
		}
		$this->assignRef('log', $debug);
		
		JToolBarHelper::title(JText::_('REQUEST'));
        parent::display($tpl);
    }
}