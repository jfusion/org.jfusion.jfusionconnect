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
 
class jfusionconnectViewlogin extends JView
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
		$server =& JFusionConnect::getServer();
		$request = JFusionConnect::getRequest();
		$user =& JFactory::getUser();
		
		$message = '';
		$this->assignRef('message', $message);
    	$params = &JComponentHelper::getParams('com_jfusionconnect');
		if (!$params->get('enabled',false)) {
			$message = JText::_('OPENID_DISABLED');
	    	$this->setLayout('default_message');
			parent::display($tpl);
			return;
		}
    	if (!Auth_OpenID_isError($request)) {
    		if (in_array($request->mode, array('checkid_immediate', 'checkid_setup'))) {
	    	    if (!JFusionConnect::isRealmAllowed($request->trust_root)) {
	    	    	JFusionConnect::cancelRequest(JFusionConnectLog::STATUS_NOT_TRUSTED);
				}
				$requestid = JFusionConnect::getRequestID($request);
				$form_url = JRoute::_('index.php?option=com_jfusionconnect&view=login&requestid='.$requestid, true);
				
				$recaptcha='';
				$user_allowautoconfirm = JFusionConnect::isAllowingAutologin($request);
				$recaptcha = JFusionConnect::getRecaptcha($request);
				
				$trust_root = $request->trust_root;
				$username = null;

				$policy_url = null;
				$extensionsNS = $request->message->namespaces->getNamespaceURI('sreg');
	        	if ($request->message->hasKey($extensionsNS,'policy_url')) {
					$policy_url = $request->message->getArg($extensionsNS,'policy_url');
	        	}

				$request_info = JFusionConnect::requestedUserInfo($request);
				
				$changeuser = false;
				$idselect = false;
				
				$siteInstance = JFusionConnectSite::getInstance();

				$document = JFactory::getDocument();
				$language = $document->getLanguage();
				$site = $siteInstance->getByRealm($trust_root,$language);
				if (!$site || !$site->getValue('text')) {
					$site = $siteInstance->getByRealm('default',$language);
				}
				
				$this->assignRef('site', $site);
				$this->assignRef('form_url', $form_url);				
				$this->assignRef('recaptcha', $recaptcha);
				$this->assignRef('user_allowautoconfirm', $user_allowautoconfirm);				
				$this->assignRef('trust_root', $trust_root);				
				$this->assignRef('username', $username);
	        	$this->assignRef('policy_url', $policy_url);
				$this->assignRef('request_info', $request_info);
				$this->assignRef('changeuser', $changeuser);
				$this->assignRef('user', $user);
				$this->assignRef('idselect', $idselect);

				$allowopenidselect = $params->get('allowopenidselect',true);
				
		        if ($request->idSelect()) {
					$idselect = true;
		            // Perform IDP-driven identifier selection
		            if ($request->mode == 'checkid_immediate') {
		                $response =& $request->answer(false);
		            } else if (!$allowopenidselect) {
						$message = JText::_('OPENID_DISABLED');
	    				$this->setLayout('default_message');
				        return;
		            } else {
        				parent::display($tpl);
				        return;
		            }
		        } else if (!$request->identity) {
					$idselect = true;
		        	if (!$allowopenidselect) {
						$message = JText::_('OPENID_DISABLED');
	    				$this->setLayout('default_message');
		        	}
					parent::display($tpl);
					return;
		        } else if ($request->immediate) {
		        	$openiduser = JFusionConnect::userFromRequest($request);
					$username = $openiduser->username;
					if ($user_allowautoconfirm) {
						JFusionConnect::autoLogin($request,$user,$openiduser);
					}
		            $response =& $request->answer(false, JFusionConnect::getServerURL());
		        } else {
					$openiduser = JFusionConnect::userFromRequest($request);
					$username = $openiduser->username;
					
					if ($allowopenidselect) {
						$changeuser = JRoute::_('index.php?option=com_jfusionconnect&view=login&task=changeuser&requestid='.$requestid, true);
					}
					if ($user_allowautoconfirm) {
						JFusionConnect::autoLogin($request,$user,$openiduser);
					}
					parent::display($tpl);
					return;
		        }
		    } else {
		        $response =& $server->handleRequest($request);
		    }

			$openiduser = JFusionConnect::userFromRequest($request);
			$username = $openiduser->username;
			
			$logInstance =& JFusionConnectLog::getInstance(null,$openiduser);
			$logInstance->add(JFusionConnectLog::STATUS_SERVERLOG,$request,$response);
		    JFusionConnect::response($server,$response);
	    }
	    if ($params->get('user_redirectinvalidrequest',0)) {
			$mainframe = & JFactory::getApplication();
			$url = JURI::root();
			$mainframe->redirect($url,JText::_('OPENID_INVALID_REQUEST'));
	    }
	    $message = JText::_('OPENID_INVALID_REQUEST');
	    $this->setLayout('default_message');
        parent::display($tpl);
    }
}
