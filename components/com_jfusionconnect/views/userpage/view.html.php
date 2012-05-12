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
class jfusionconnectViewuserpage extends JView
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
		$user	=& JFactory::getUser();

		if ( $user->get('guest') ) {
			// Redirect to login
			$uri		= JFactory::getURI();
			$return		= $uri->toString();

			$url  = 'index.php?option=com_user&view=login';
			$url .= '&return='.base64_encode($return);
			
			$mainframe = & JFactory::getApplication();
			$mainframe->redirect($url, JText::_('YOU MUST LOGIN FIRST'));
		} else {
			$params = &JComponentHelper::getParams('com_jfusionconnect');			
			$message = JText::_('OPENID_USERPAGE');
		    $this->assignRef('message', $message);

			$this->assignRef('username', $user->username);
			$this->assignRef('openid', JFusionConnect::userToURL($user));
			
			if ($params->get('allowopenidselect',true)) {
				$this->assignRef('openidselect', JFusionConnect::userToURL());
			}
		}
        parent::display($tpl);
    }
}
