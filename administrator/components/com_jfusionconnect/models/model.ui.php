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
defined('_JEXEC') or die('Restricted access');

class JFusionConnectUi
{	
	function getMenuBar()
	{
		$menubar = array();
		$i=0;

		$menubar[$i+1]['url'] = JRoute::_('index.php?option=com_jfusionconnect&view=userpage', true);
		$menubar[$i+1]['title'] = JText::_('USER_PAGE');
		$menubar[$i+1]['view'] = 'userpage';
		$menubar[$i+1]['icon'] = 'status_online.png';
		$i++;		
		
		$menubar[$i+1]['url'] = JRoute::_('index.php?option=com_jfusionconnect&view=managesites', true);
		$menubar[$i+1]['title'] = JText::_('SITE_MANAGER');
		$menubar[$i+1]['view'] = 'managesites';
		$menubar[$i+1]['icon'] = 'application_home.png';
		$i++;
		
		$menubar[$i+1]['url'] = JRoute::_('index.php?option=com_jfusionconnect&view=userlog', true);
		$menubar[$i+1]['title'] = JText::_('SITE_LOG');
		$menubar[$i+1]['view'] = 'userlog';
		$menubar[$i+1]['icon'] = 'book.png';
		$i++;
		return $menubar;
	}
	
	function renderMenuBar()
	{
		$view = JRequest::getVar('view');
		foreach (JFusionConnectUi::getMenuBar() as $key => $value) {
			if ($value['icon']) {
				$icon = '<img alt="'.$value['title'].'" src="'.JURI::root(true).'/components/com_jfusionconnect/images/'.$value['icon'].'">'; 
			} else {
				$icon = '';
			}
			if ( $value['view'] != $view ) {
				echo ' <a href="'.$value['url'].'">'.$icon.$value['title'].'</a>';
			} else {
				echo ' '.$icon.'<b>'.$value['title'].'</b>';
			}
		}
	}
	
	function getStatusIcon($status)
	{
		$image = $alt = '';
		switch ($status) {
		    case JFusionConnectLog::STATUS_LOGIN_INPROGRESS:
		    	$image = 'application_form_edit.png';
		    	$alt = JText::_('STATUS_LOGIN_INPROGRESS');
		        break;
		    case JFusionConnectLog::STATUS_LOGIN_SUCCESS:
		    	$image = 'tick.png';
		    	$alt = JText::_('STATUS_LOGIN_SUCCESS');
		        break;
		    case JFusionConnectLog::STATUS_LOGIN_CANCEL:
		    	$image = 'arrow_undo.png';
		        $alt = JText::_('STATUS_LOGIN_CANCEL');
		        break;
	        case JFusionConnectLog::STATUS_LOGIN_FAILED:
	        	$image = 'error.png';
		        $alt = JText::_('STATUS_LOGIN_FAILED');
		        break;
	        case JFusionConnectLog::STATUS_SERVERLOG:
	        	$image = 'database.png';
		        $alt = JText::_('STATUS_SERVERLOG');
		        break;
	        case JFusionConnectLog::STATUS_AUTH_ERROR:
	        	$image = 'bomb.png';
		        $alt = JText::_('STATUS_AUTH_ERROR');
		        break;
	        case JFusionConnectLog::STATUS_FATAL_ERROR:
	        	$image = 'exclamation.png';
		        $alt = JText::_('STATUS_FATALERROR');
		        break;
	        case JFusionConnectLog::STATUS_INVALID_USER:
	        	$image = 'bomb.png';
		        $alt = JText::_('STATUS_INVALID_USER');
		        break;
	        case JFusionConnectLog::STATUS_NOT_TRUSTED:
	        	$image = 'bomb.png';
		        $alt = JText::_('STATUS_NOT_TRUSTED');
		        break;
	        default:
	        	return;
		}
		$mainframe = & JFactory::getApplication();
		return '<img alt="'.$alt.'" src="'.JURI::root().'components/com_jfusionconnect/images/'.$image.'"> '.$alt;
	}
	
	function buildDebugRequest($log)
	{
		jimport('joomla.utilities.date');
		$user =& JFactory::getUser();
		
		if (!$log) {
			return array();
		}
		
		$date = new JDate($log->date,$user->getParam('timezone'));
		$debug[JText::_('TIME')] = $date->toFormat('%Y-%m-%d %H:%M:%S',true);
		
		switch ($log->status) {
		    case JFusionConnectLog::STATUS_LOGIN_SUCCESS:
		        $debug[JText::_('STATUS')] = JText::_('STATUS_LOGIN_SUCCESS');
		        break;
		    case JFusionConnectLog::STATUS_LOGIN_CANCEL:
		        $debug[JText::_('STATUS')] = JText::_('STATUS_LOGIN_CANCEL');
		        break;
	        case JFusionConnectLog::STATUS_LOGIN_FAILED:
		        $debug[JText::_('STATUS')] = JText::_('STATUS_LOGIN_FAILED');
		        break;
	        case JFusionConnectLog::STATUS_SERVERLOG:
		        $debug[JText::_('STATUS')] = JText::_('STATUS_SERVERLOG');
		        break;
	        case JFusionConnectLog::STATUS_AUTH_ERROR:
		        $debug[JText::_('STATUS')] = JText::_('STATUS_AUTH_ERROR');
		        break;
			case JFusionConnectLog::STATUS_FATAL_ERROR:
		        $debug[JText::_('STATUS')] = JText::_('STATUS_FATAL_ERROR');
		        break;
		}
		
		$debug[JText::_('OPENID')] = isset($log->request->claimed_id) ? $log->request->claimed_id : JText::_('EMPTY_IDENTETY');
		$debug[JText::_('IPADRESS')] = $log->ipadress;
		
		if ($log->response) {
			if (!Auth_OpenID_isError($log->response)) {
				JFusionConnect::requireOnce('Auth/OpenID/SReg.php');
				JFusionConnect::requireOnce('Auth/OpenID/AX.php');				
				$sreg = $log->response->fields->getArgs(Auth_OpenID_SREG_NS_URI_1_1);
				if ($sreg) {
					foreach($sreg as $key=>$value) {
						$debug[JText::_('RESPONCE')][JText::_('SREG')][JText::_(JFusionConnect::sregToAx($key))] = $value;
					}
				}
				$ax = $log->response->fields->getArgs(Auth_OpenID_AX_NS_URI);
				if ($ax) {
					$ax_keys = array();
					$ax_values = array();
					foreach($ax as $key=>$value) {
						if (strpos($key , 'type')===0) {
							$ax_keys[] = str_replace ('http://axschema.org/', '', $value);
						} elseif (strpos($key , 'value')===0) {
							$ax_values[] = $value;
						}
					}
					foreach($ax_keys as $key=>$value) {
						$debug[JText::_('RESPONCE')][JText::_('AX')][JText::_($value)] = $ax_values[$key];
					}
				}
			} else {
				if ($log->response->message) {
					$debug[JText::_('RESPONCE')][JText::_('ERRORMESSAGE')] = $log->response->message;	
				}
				if ($log->response->text) {
					$debug[JText::_('RESPONCE')][JText::_('ERRORTEXT')] = $log->response->text;	
				}
				if ($log->response->contact) {
					$debug[JText::_('RESPONCE')][JText::_('ERRORCONTACT')] = $log->response->contact;	
				}
				if ($log->response->reference) {
					$debug[JText::_('RESPONCE')][JText::_('ERRORREFERENCE')] = $log->response->reference;	
				}
			}
		}
		
		$debug[JText::_('SITE')] = $log->realm;
		if (isset($log->request->trust_root) && $log->realm != $log->request->trust_root) {
			$debug[JText::_('URL')] = $log->request->trust_root;
		}
		if (isset($log->request->return_to)) {
			$debug[JText::_('RETURN_URL')] = $log->request->return_to;
		}
		if (isset($log->request->namespace)) {
			$debug[JText::_('NAMESPACE')] = $log->request->namespace;
		}
		if (isset($log->request->mode)) {
			$debug[JText::_('MODE')] = JText::_($log->request->mode);
		}
		return $debug;
	}	
}