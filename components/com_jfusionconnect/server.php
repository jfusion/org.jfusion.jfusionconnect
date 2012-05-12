<?php
/**
 * PHP version 5
 *
 * @category  JFusionConnect
 * @package   Models
 * @author    JFusion Team <webmaster@jfusion.org>
 * @copyright 2008 JFusion. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link      http://www.jfusion.org
 */
class JFusionConnectServer {
	var $inJoomla = false;
	
	var $xrdsURL = null;
	
	function JFusionConnectServer($gotojoomla=true) {

		$this->inJoomla = $this->startJoomla();
			
		if (isset($_GET['getxrads'])) {
			JFusionConnect::xrds($_GET['getxrads']);
		} else if (isset($_GET['openid_mode']) || isset($_POST['openid_mode'])) {
			$this->run();
		} elseif (!$this->inJoomla) {
			$url = JURI::getInstance();
			if (JFusionConnect::isOpenID($url->toString(),'signon')) {
				$this->xrads('signon');
				$this->gotoUserPage();
			} else if (JFusionConnect::isOpenID($url->toString(),'server')) {
				$this->xrads('server');
				$this->gotoUserPage();
			} else if ($gotojoomla) {
				$this->gotoJoomla();
			}
		}
	}
	
	function xrads($mode) {
		$this->xrdsURL = JFusionConnect::xrdsUrl($mode);
		if (!headers_sent()) {
			header('X-XRDS-Location: '.$this->xrdsURL);
		}
	}
	
	function startJoomla() {
        if (!defined('_JEXEC')) {
			// trick joomla into thinking we're running through joomla
			define('_JEXEC', true);
	            
			define('DS', DIRECTORY_SEPARATOR);
			define('JPATH_BASE', dirname(__FILE__).DS.'..'.DS.'..');
			// load joomla libraries
			require_once JPATH_BASE . DS . 'includes' . DS . 'defines.php';
			require_once JPATH_LIBRARIES . DS . 'loader.php';
	            
			spl_autoload_register('__autoload');
			jimport('joomla.base.object');
			jimport('joomla.factory');
			jimport('joomla.filter.filterinput');
			jimport('joomla.error.error');
			jimport('joomla.utilities.arrayhelper');
			jimport('joomla.environment.uri');
			jimport('joomla.environment.request');
			jimport('joomla.user.user');
			jimport('joomla.html.parameter');
			jimport( 'joomla.application.component.helper' );
			// JText cannot be loaded with jimport since it's not in a file called text.php but in methods
			JLoader::register('JText', JPATH_BASE . DS . 'libraries' . DS . 'joomla' . DS . 'methods.php');
			JLoader::register('JRoute', JPATH_BASE . DS . 'libraries' . DS . 'joomla' . DS . 'methods.php');
			//load JFusion's libraries
			require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusionconnect' . DS . 'models' . DS . 'model.connect.php';
        	$mainframe = & JFactory::getApplication('site');
        	$GLOBALS['mainframe'] = &$mainframe;
        	return false;
        } else {
        	return true;
        }
	}

	function run() {
		$server =& JFusionConnect::getServer();
		$request = JFusionConnect::getRequest(false);
		$user =& JFactory::getUser();
		
		$params = &JComponentHelper::getParams('com_jfusionconnect');
		if (!$params->get('enabled',false)) {
			$this->gotoLogin();
		}
		if (!Auth_OpenID_isError($request)) {
			if (in_array($request->mode, array('checkid_immediate', 'checkid_setup'))) {
				if (!JFusionConnect::isRealmAllowed($request->trust_root)) {
					JFusionConnect::cancelRequest(JFusionConnectLog::STATUS_NOT_TRUSTED);
				}
				$this->gotoLogin();
			} else {
				$response =& $server->handleRequest($request);
			}
			$openiduser = JFusionConnect::userFromRequest($request);
			$logInstance =& JFusionConnectLog::getInstance(null,$openiduser);
			$logInstance->add(JFusionConnectLog::STATUS_SERVERLOG,$request,$response);
			JFusionConnect::response($server,$response);
		}
		$this->gotoLogin();
	}	
	
	function gotoLogin() {
		$mainframe = & JFactory::getApplication();
		$mainframe->redirect(JFusionConnect::getLoginURL());
	}
	
	function gotoJoomla() {
		$mainframe = & JFactory::getApplication();
		$mainframe->redirect(JFusionConnect::getJoomlaURL());
	}
	function gotoUserPage() {
		$url = JFusionConnect::getJoomlaURL().'index.php?option=com_jfusionconnect&view=userpage';
		echo '<meta http-equiv="refresh" content="0;url='.$url.'">';
		echo '<a id="clickhere" href="'.$url.'">Click Here</a>';
		echo '<script>var el = document.getElementById(\'clickhere\'); if (el) { el.style.display = \'none\'; };</script>';
		$mainframe = & JFactory::getApplication();
		$mainframe->redirect($url);
	}
}
$jfcs = new JFusionConnectServer();