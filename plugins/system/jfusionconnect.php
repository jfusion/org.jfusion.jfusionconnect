<?php

/**
 * This is the jfusion user plugin file
 *
 * PHP version 5
 *
 * @category   JFusionConnect
 * @package    Plugins
 * @subpackage System
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
/**
 * Load the JFusion framework if installed
 */
jimport('joomla.plugin.plugin');

/**
 * JFusion System Plugin class
 *
 * @category   JFusionConnect
 * @package    Plugins
 * @subpackage System
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */

class plgSystemJfusionconnect extends JPlugin
{
    /**
     * Constructor
     *
     * For php4 compatability we must not use the __constructor as a constructor for plugins
     * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
     * This causes problems with cross-referencing necessary for the observer design pattern.
     *
     * @param object &$subject The object to observe
     * @param array  $config   An array that holds the plugin configuration
     *
     * @access protected
     * @since  1.0
     */
    function plgSystemJfusion(&$subject, $config)
    {
        parent::__construct($subject, $config);
    }
    /**
     * onAfterInitialise
     *
     * This function is called by joomla framework
     *
     * @since 1.0
     * @return void
     */
    function onAfterInitialise()
    {
    	$mainframe = JFactory::getApplication();
    	if (!$mainframe->isAdmin()) {
	        $task = JRequest::getVar('task');
	        $option = JRequest::getVar('option');
	        $view = JRequest::getVar('view');
	        $requestid = JRequest::getVar('requestid');
	        if ( $task || $option || $view ) {
	        	if ($option == 'com_jfusionconnect' && $view = 'login' && $requestid) {
					if ($this->params->get('language')) {
						require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusionconnect' . DS . 'models' . DS . 'model.connect.php';
						
						$server =& JFusionConnect::getServer();
						$request = JFusionConnect::getRequest();
						if (!Auth_OpenID_isError($request)) {
							$ruser = JFusionConnect::userFromRequest($request);
							if ($ruser->id) {
								$language = $ruser->getParam ('language');
								if ($language) {
		        					$l =& JFactory::getLanguage();
									$l->setLanguage($language);
								}
							}
						}
					}
	        	}
	        	return;
	        }
			$params = &JComponentHelper::getParams('com_jfusionconnect');
			if ($params->get('enabled',false)) {
		        $uri = JFactory::getURI();
				require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusionconnect' . DS . 'models' . DS . 'model.connect.php';	        
	
		        if (JFusionConnect::isOpenID($uri->toString(),'signon')) {
		       		$mode = 'signon';
					$user =& JFactory::getUser();
					if ( !$user->guest) {
						$redirectItemId = $this->params->get('redirectItemId');
						if ($redirectItemId) {
							$mainframe->redirect($uri->getScheme().'://'.$uri->getHost().JRoute::_('index.php?Itemid='.$redirectItemId, false));
						} else {
							$mainframe->redirect($uri->getScheme().'://'.$uri->getHost().JRoute::_('index.php?option=com_jfusionconnect&view=userpage', false));
						}
					}
		        } else if (JFusionConnect::isOpenID($uri->toString(),'server')) {
		        	if ($params->get('allowopenidselect',true)) {
		        		$mode = 'server';
		        	}
		        }
		        if (isset($mode)) {
					$menus	=& JSite::getMenu();
					$default = $menus->getDefault();
					$Itemid = JRequest::getInt( 'Itemid',$default->id);

					$user	=& JFactory::getUser();
					$aid	= $user->get('aid');
					if(!$menus->authorize($Itemid, $aid)) {
	        			$replaceItemId = $this->params->get('ItemId');
						$originialURI =& JURI::getInstance();
						if ($replaceItemId) {
							$fakeURI =& JURI::getInstance($originialURI->getScheme().'://'.$originialURI->getHost().JRoute::_('index.php?Itemid='.$replaceItemId, false));
							$fakeURI->setVar('Itemid', $replaceItemId);
							JRequest::setVar('Itemid',$replaceItemId);
						} else {
							$loginitemid = $params->get('loginitemid');
							if (JFusionConnect::isJoomlaVersion('1.6')) {
								$fakeURI =& JURI::getInstance($originialURI->getScheme().'://'.$originialURI->getHost().JRoute::_('index.php?option=com_users&view=login', false));
							} else {
								$fakeURI =& JURI::getInstance($originialURI->getScheme().'://'.$originialURI->getHost().JRoute::_('index.php?option=com_user&view=login', false));
							}
						}
						$originialURI = $fakeURI;
					}
					$xrds = JFusionConnect::xrdsUrl($mode);
					header('X-XRDS-Location: '.$xrds);
					$doc =& JFactory::getDocument();
					$doc->setMetaData( 'X-XRDS-Location', $xrds,true);
		        }
	    	}
    	}
    }
}