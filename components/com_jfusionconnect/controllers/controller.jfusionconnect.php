<?php

/**
 * jfusion frontend controller
 *
 * PHP version 5
 *
 * @category  JFusionConnect
 * @package   ControllerFront
 * @author    JFusion Team <webmaster@jfusion.org>
 * @copyright 2008 JFusion. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link      http://www.jfusion.org
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
jimport('joomla.application.component.view');

/**
 * JFusion Component Controller
 *
 * @category  JFusionConnect
 * @package   ControllerFront
 * @author    JFusion Team <webmaster@jfusion.org>
 * @copyright 2008 JFusion. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link      http://www.jfusion.org
 */
class JFusionconnectControllerFrontEnd extends JController
{  
    function xrds() {
		$mode = JRequest::getVar('getxrads');
		JFusionConnect::requireOnce('Auth/OpenID/Discover.php');
		JFusionConnect::xrds($mode);
    }
    
    function login() {
		// Check for request forgeries
		if (!JRequest::checkToken()) {
			JFusionConnect::redirect('Invalid Token');
			return;
		}	
		
		$server =& JFusionConnect::getServer();
		$request = JFusionConnect::getRequest();

		$msg  = $msgType = null;
    	if (!Auth_OpenID_isError($request)) {
    		if (in_array($request->mode, array('checkid_immediate', 'checkid_setup'))) {
				$password = JRequest::getVar('password');     			
    			$username = JRequest::getVar('username');
    			$remember = JRequest::getBool('remember',0);
    			
        		jimport( 'joomla.user.authentication');
				$authenticate = &JAuthentication::getInstance();
    			
				$credentials['username'] = $username;
				$credentials['password'] = $password;
		        
				$options = array();
	
				$auth = $authenticate->authenticate($credentials, $options);
				$status = $auth->status;
				
				if (!JFusionConnect::checkRecaptcha($request)) {
					JFusionConnect::redirect(JText::_('Invalid Recaptcha'));
				}
				if ($status === JAUTHENTICATE_STATUS_SUCCESS) {
					if ($id = JUserHelper::getUserId($auth->username) ) {
						$user =& JFactory::getUser($id);
						
						if (!$user->block) { 
							$response =& JFusionConnect::login($request,$user,false);
							
							$db = & JFactory::getDBO();
					        $query = 'SELECT * FROM #__jfusionconnect ' . 'WHERE realm=' . $db->Quote($request->trust_root) . 'AND userid='.$db->Quote($user->id);
					        $db->setQuery($query);
					        $result = $db->loadObject();
							if (!$result) {
								$realm = new stdClass;
								$realm->id = null;
								$realm->userid = $user->id;
								$realm->realm = $request->trust_root;
								$realm->remember = $remember;
								
								$db->insertObject('#__jfusionconnect', $realm, 'id');
							} else {
								$realm = new stdClass;
								$realm->id = $result->id;
								$realm->userid = $result->userid;
								$realm->realm = $result->realm;
								$realm->remember = $remember;
								
								$db->updateObject('#__jfusionconnect', $realm, 'id');
							}
						} else {
							$status = JAUTHENTICATE_STATUS_FAILURE;
						}
					} else {
						$status = JAUTHENTICATE_STATUS_FAILURE;
					}
    			}
    			
    			if ($status !== JAUTHENTICATE_STATUS_SUCCESS) {
					$id = JFusionConnect::getRequestID($request);
					
					$req =& JFusionConnectRequest::getInstance($id);
					$savedRequest = $req->get();
					
    				if ($id = JUserHelper::getUserId($username)) {
    					$user =& JFactory::getUser($id);
    					$logInstance =& JFusionConnectLog::getInstance(null,$user);
    				} else {
						$logInstance =& JFusionConnectLog::getInstance();    					
    				}
					$loginerror = $logInstance->countFailedLogins($savedRequest->id);
    				if ($loginerror<3) {
						$logInstance->add(JFusionConnectLog::STATUS_LOGIN_FAILED,$request);
						JFusionConnect::redirect(JText::_('E_LOGIN_AUTHENTICATE'));
    				} else {
						$response =& $request->answer(false);
						$logInstance->add(JFusionConnectLog::STATUS_LOGIN_FAILED,$request,$response);
    				}
    			}
		    } else {	    	
		        $response =& $server->handleRequest($request);
		        $logInstance =& JFusionConnectLog::getInstance();
    			$logInstance->add(JFusionConnectLog::STATUS_LOGIN_FAILED,$request,$response);
		    }
		    
		    if (!Auth_OpenID_isError($response)) {
	    		JFusionConnect::response($server,$response);
		    } else {
		    	$msg = $response->text;
				$this->setRedirect('index.php', $msg, $msgType);
		    }
    	} else {
        	$this->setRedirect('index.php?option=com_jfusionconnect&view=login', $msg, $msgType);
    	}
    }

    function changeuser() {
		$params = &JComponentHelper::getParams('com_jfusionconnect');
		$request = JFusionConnect::getRequest();
		if (!Auth_OpenID_isError($request)) {
			$requestid = JFusionConnect::getRequestID($request);
			if ($params->get('allowopenidselect',true)) {
				if (!Auth_OpenID_isError($request)) {
		    		$request->identity = $request->claimed_id = Auth_OpenID_IDENTIFIER_SELECT;
		    		JFusionConnect::saveRequest($request);
		    	}
			}
		}

		$mainframe = & JFactory::getApplication();
		$url = JRoute::_('index.php?option=com_jfusionconnect&view=login&requestid='.$requestid, false);
		$mainframe->redirect($url);
    }
    
    function cancel() {
    	JFusionConnect::cancelRequest(JFusionConnectLog::STATUS_LOGIN_CANCEL);
    }
    
    function manage_update() {
		$site = JRequest::getVar('site');
		$user =& JFactory::getUser();
		$db = & JFactory::getDBO();
        $query = 'SELECT * FROM #__jfusionconnect WHERE userid='.$db->Quote($user->id);
        $db->setQuery($query);
        $result = $db->loadObjectList();
        if ($result) {
	    	foreach ($result as $key => $value) {
	    		if (isset($site['remember'][$value->id]) || $site['remember'][$value->id] == 'on') {
	    			$site['remember'][$value->id] = true;
	    		} else {
	    			$site['remember'][$value->id] = false;
	    		}
	    		if ($value->remember != $site['remember'][$value->id]) {
					$realm = $value;
					$realm->remember = $site['remember'][$value->id];
					$db->updateObject('#__jfusionconnect', $realm, 'id');
	    		}
		    }
        }
        $this->setRedirect(JRoute::_('index.php?option=com_jfusionconnect&view=managesites', false), JText::_('UPDATED_SITES'));        
    }    
}
