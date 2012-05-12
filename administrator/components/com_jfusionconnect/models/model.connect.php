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

require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusionconnect' . DS . 'models' . DS . 'model.factory.php';
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusionconnect' . DS . 'models' . DS . 'model.log.php';
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusionconnect' . DS . 'models' . DS . 'model.request.php';
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusionconnect' . DS . 'models' . DS . 'model.site.php';

class JFusionConnect
{
	function getApiPath()
	{
		$apipath = JPATH_LIBRARIES.DS.'openid'.DS;
		return $apipath;
	}
	
	function requireOnce($file)
	{
		$old = ini_set('include_path', JFusionConnect::getApiPath());
	    require_once $file;
	    ini_set('include_path', $old);
	}
	
	function getOpenIDStore()
	{
		$params = &JComponentHelper::getParams('com_jfusionconnect');
		JFusionConnect::requireOnce('Auth/OpenID/FileStore.php');
		$storepath = $params->get('storepath',JPATH_ROOT.DS.'tmp'.DS.'openid_store');
		$store = new Auth_OpenID_FileStore($storepath);
	    return $store;
	}
	
	function userFromURL($url,$mode=null)
	{
		jimport('joomla.user.user');
		$u =& JURI::getInstance($url);
		
		$params = &JComponentHelper::getParams('com_jfusionconnect');
		$openidprefix = $params->get('openidprefix',null);
		if ($mode === null) {
			$openid = $params->get('openid',0);
		} else {
			$openid = $mode;
		}

		if ($openid>=2) {
			if ($openidprefix) {
				$openidprefix .= '/';
			}
			$openidsite =& JURI::getInstance(JFusionConnect::getOpenIDSiteURL().$openidprefix);

			$path = $openidsite->toString();
			if (strpos ($u->toString(), $path) ===0 ) {
				$length = strlen($path);
				$userid = substr($u->toString(), $length);
			}
		} else {
			if (!$openidprefix) {
				$openidprefix = 'openid';
			}
			$userid = $u->getVar( $openidprefix );
		}
		if (!empty($userid)) {
			$userid = urldecode($userid);
			switch ($openid) {
				case 0:
				case 2:
					jimport('joomla.user.helper');
					$id = JUserHelper::getUserId($userid);
					if ($id) {
						$user = JFactory::getUser($id);
						if ($user) {
							$userurl = JFusionConnect::userToURL($user);
							if (!$user->block && $user->username == $userid) {
								if ($userurl == $url) {
									return $user;
								}
							}
						}
					}
				break;
				case 1:
				case 3:
					if(preg_match('@^[1-9][0-9]+$@',$userid) === 1) {
						$user = JUser::getInstance();
						$table 	=& $user->getTable();
						if($table->load($userid)) {
							$user = JFactory::getUser($userid);
							if ($user) {
								$userurl = JFusionConnect::userToURL($user);
								if (!$user->block && $user->id == $userid) {
									if ($userurl == $url) {
										return $user;
									}
								}
							}
						}
					}
				break;
			}
		}
		return JUser::getInstance();
	}
	
	function userFromRequest($request)
	{
		if (isset($request->claimed_id)) {
			$claimed_id = $request->claimed_id;
		} else {
	    	if ($request->message->hasKey(Auth_OpenID_OPENID2_NS,'claimed_id')) {
				$claimed_id = $request->message->getArg(Auth_OpenID_OPENID2_NS,'claimed_id');
			} else if ($request->message->hasKey(Auth_OpenID_OPENID2_NS,'identity')) {
				$claimed_id = $request->message->getArg(Auth_OpenID_OPENID2_NS,'identity');
			}
	    }
		return JFusionConnect::userFromURL($claimed_id);
	}	

	# gen-delims  = ":" / "/" / "?" / "#" / "[" / "]" / "@"
	#
	# sub-delims  = "!" / "$" / "&" / "'" / "(" / ")"
	#                  / "*" / "+" / "," / ";" / "="
	#
	# unreserved  = ALPHA / DIGIT / "-" / "." / "_" / "~"
	function Auth_OpenID_getURLIllegalCharRE()
	{
	    return "/([^-A-Za-z0-9:\/\?#\[\]@\!\$&'\(\)\*\+,;=\._~\%])/";
	}	
	
	function userToURL($user=null,$mode=null)
	{
		$u =& JFactory::getURI();
		$params = &JComponentHelper::getParams('com_jfusionconnect');
		$openidsite = JFusionConnect::getOpenIDSiteURL();
		$url = null;
		if ($user) {
			$openidprefix = $params->get('openidprefix',null);
			if ($mode === null) {
				$openid = $params->get('openid',0);
			} else {
				$openid = $mode;
			}
			if ($openid>=2) {
				if ($openidprefix!==null) {
					$openidprefix .= '/';
				}
			} else {
				if ($openidprefix===null) {
					$openidprefix = 'openid';
				}
			}
			switch ($openid) {
			    case 0:
					$username = $user->username;
					preg_match(JFusionConnect::Auth_OpenID_getURLIllegalCharRE(), $username, $illegal_matches);
					if ($illegal_matches) {
						$username = urlencode($username);
					}
			    	$url = '?'.$openidprefix.'='.$username;
			        break;
			    case 1:
			    	$url = '?'.$openidprefix.'='.$user->id;
			    	break;
				case 2:
					$username = $user->username;
					preg_match(JFusionConnect::Auth_OpenID_getURLIllegalCharRE(), $username, $illegal_matches);
					if ($illegal_matches) {
						$username = urlencode($username);
					}
					$url = $openidprefix.$username;
					break;
			    case 3:
					$url = $openidprefix.$user->id;
			        break;
			}
		}
		return JFusionConnect::buildURL($openidsite,$url,false);
	}
	
		/**
	 * Get the URL of the current script
	 */
	function isOpenID($url,$mode='signon')
	{
		$url = JURI::getInstance($url);
		$params = &JComponentHelper::getParams('com_jfusionconnect');		
		
		if ($params->get('enabled',false)) {
			if ($mode == 'signon') {
				$openiduser = JFusionConnect::userFromURL($url->toString());
				if (!$openiduser->guest) {
					return true;
				}
			} elseif ($mode == 'server') {
				if ($params->get('allowopenidselect',true)) {
					$openididurl = JFusionConnect::userToURL(null);
					if (rtrim($openididurl,'/') == rtrim($url->toString(),'/')) {
						return true;
					}
		        }
			}
		}
		return false;
	}	
	
	/**
	 * Get the URL of the current script
	 */
	function buildURL($domain,$url=null,$route=true)
	{
		$domain = rtrim($domain, '/');
		if (strpos( $domain, '?'  )) {
			$url = str_replace( '?' , '&' , $url);
		} else if (substr($domain, -4) != '.php') {
			$domain .= '/';
		}
		if ($url) {
			$url = ltrim($url, '/');
			if ($route) {
				$url = JRoute::_($domain.$url, false);
			} else {
				$url = $domain.$url;
			}
		} else {
			$url = $domain;
		}
		return $url;
	}
	
	/**
	 * Get the URL of the current script
	 */
	function getServerURL()
	{
		$params = &JComponentHelper::getParams('com_jfusionconnect');
		return $params->get('serverurl',JURI::root().'components/com_jfusionconnect/server.php');
	}
	
	/**
	 * Get the URL of the current script
	 */
	function getJoomlaURL()
	{
		$params = &JComponentHelper::getParams('com_jfusionconnect');
		return $params->get('joomlaurl',JURI::root());
	}
	
	/**
	 * Get the URL of the current script
	 */
	function getOpenIDSiteURL()
	{
		$params = &JComponentHelper::getParams('com_jfusionconnect');
		return $params->get('openidsite',JURI::root());
	}
	
	/**
	 * Get the URL of the current script
	 */
	function getLoginURL()
	{
		$request = JFusionConnect::getRequest();
		$requestid = JFusionConnect::getRequestID($request);
		
		$params = &JComponentHelper::getParams('com_jfusionconnect');

		$loginitemid = $params->get('loginitemid');
		if ($loginitemid) {
			$url = 'index.php?Itemid='.$loginitemid.'&option=com_jfusionconnect&view=login&requestid='.$requestid;			
		} else {
			$url = 'index.php?option=com_jfusionconnect&view=login&requestid='.$requestid;	
		}

		$url = JFusionConnect::buildURL(JFusionConnect::getJoomlaURL(),$url,false);	
		return $url;
	}	
	
	/**
	 * Get the URL of the current script
	 */
	function isRealmAllowed($realm)
	{
		$params = &JComponentHelper::getParams('com_jfusionconnect');
		$openid_listtype = $params->get('openid_listtype',1);
		$openid_list = $params->get('openid_list');
		if ($openid_list) {
			$openid_list = explode("\n", $openid_list);
			foreach($openid_list as $value) {
				$value = trim($value);
				if (preg_match( '#'.$value.'#is' , $realm)) {
					if ($openid_listtype) {
						return false;
					} else {
						return true;
					}
				}
			}
		}
		if ($openid_listtype) {
			return true;
		} else {
			return false;
		}
	}
	
	function sregToAx($value)
	{
    	# AX <-> SREG transform
    	$ax_to_sreg = array(
	        'namePerson/friendly'     => 'nickname',
	        'contact/email'           => 'email',
	        'namePerson'              => 'fullname',
	        'birthDate'               => 'dob',
	        'person/gender'           => 'gender',
	        'contact/postalCode/home' => 'postcode',
	        'contact/country/home'    => 'country',
	        'pref/language'           => 'language',
	        'pref/timezone'           => 'timezone',
        );
        $ax = array_keys($ax_to_sreg);
		$sreg =  array_values($ax_to_sreg);
		$n = array_search($value, $sreg);
		return $ax[$n];
	}
	
	function requestedUserInfo($request)
	{
        $request_info = array();
        $params = &JComponentHelper::getParams('com_jfusionconnect');
        $plugin = JFusionConnectFactory::getPlugin();
		if ($params->get('sreg_enabled',1)) {			
			JFusionConnect::requireOnce('Auth/OpenID/SReg.php');
			$URI = $request->message->namespaces->getNamespaceURI('sreg');
			
			if ($request->message->namespaces->contains(Auth_OpenID_SREG_NS_URI_1_0)) {
				$URI = Auth_OpenID_SREG_NS_URI_1_0;
			} else if ($request->message->namespaces->contains(Auth_OpenID_SREG_NS_URI_1_1)) {
				$URI = Auth_OpenID_SREG_NS_URI_1_1;
			}

			if (!$URI) {
				$URI = $request->message->namespaces->getNamespaceURI('e1');
			}
			$required = $optional = array();
			if ($request->message->hasKey($URI,'required')) {
				$required = explode (',' , $request->message->getArg($URI,'required'));
			}
			if ($request->message->hasKey($URI,'optional')) {
				$optional = explode (',' , $request->message->getArg($URI,'optional'));
			}
			$sreg = array_merge($required,$optional);
			if ($sreg) {
				foreach ($sreg as $k => $key) {
					$key = $plugin->cleanKey(JFusionConnect::sregToAx($key));
					if ($plugin->isEnabled($key)) {
						$request_info[$key] = $key;
					}
				}
			}
		}
		if ($params->get('ax_enabled',1)) {
			JFusionConnect::requireOnce('Auth/OpenID/AX.php');
			$required = $if_available = array();
			if ($request->message->hasKey(Auth_OpenID_AX_NS_URI,'required')) {
				$required = explode (',' , $request->message->getArg(Auth_OpenID_AX_NS_URI,'required'));
			}
			if ($request->message->hasKey(Auth_OpenID_AX_NS_URI,'if_available')) {
				$if_available = explode (',' , $request->message->getArg(Auth_OpenID_AX_NS_URI,'if_available'));
			}
			$ax = array_merge($required,$if_available);
			if ($ax) {
				foreach ($ax as $k => $key) {
					$key = $plugin->cleanKey($request->message->getArg(Auth_OpenID_AX_NS_URI,'type.'.$key));
					if ($plugin->isEnabled($key)) {
						$request_info[$key] = $key;
					}
				}
			}
		}
        return $request_info;
	}
	
	function requestedPAPE($request)
	{
        $request_pape = array();
        $params = &JComponentHelper::getParams('com_jfusionconnect');
		if ($params->get('pape_enabled',0)) {
			JFusionConnect::requireOnce('Auth/OpenID/PAPE.php');
			$required = $if_available = array();
			$pape = null;
			if ($request->message->hasKey(Auth_OpenID_PAPE_NS_URI,'preferred_auth_policies')) {
				$pape = explode (' ' , $request->message->getArg(Auth_OpenID_PAPE_NS_URI,'preferred_auth_policies'));
			}
			if ($pape) {
				foreach ($pape as $value) {
					$key = explode('/', $value);
					if (count($key) > 1) {
						$key = $key[count($key)-1];
						$request_pape[$key] = $value;
					}
				}
			}
		}
        return $request_pape;
	}	
	
	function getTimezone($offset) {
		$timezone = array();
		$timezone['-12'] = 'Pacific/Kwajalein';
		$timezone['-11'] = 'Pacific/Samoa';
		$timezone['-10'] = 'Pacific/Honolulu';
		$timezone['-9.5'] = 'Pacific/Marquesas';
		$timezone['-9'] = 'America/Anchorage';
		$timezone['-8'] = 'America/Ensenada';		
		$timezone['-7'] = 'America/Denver';
		$timezone['-6'] = 'America/Chicago';
		$timezone['-5'] = 'America/New_York';
		$timezone['-4.5'] = 'America/Caracas';		
		$timezone['-4'] = 'Atlantic/Bermuda';
		$timezone['-3.5'] = 'America/St_Johns';
		$timezone['-3.0'] = 'Brazil/East';		
		$timezone['-2'] = 'Atlantic/Azores';
		$timezone['-1'] = 'Atlantic/Cape_Verde';
		$timezone['0'] = 'Europe/London';
		$timezone['1'] = 'Europe/Brussels';
		$timezone['2'] = 'Africa/Cairo';
		$timezone['3'] = 'Europe/Moscow';
		$timezone['3.5'] = 'Asia/Tehran';
		$timezone['4'] = 'Asia/Dubai';
		$timezone['4.5'] = 'Asia/Kabul';
		$timezone['5'] = 'Asia/Karachi';
		$timezone['5.5'] = 'Asia/Kolkata';
		$timezone['5.75'] = 'Asia/Katmandu';
		$timezone['6'] = 'Asia/Dhaka';
		$timezone['6.5'] = 'Asia/Rangoon';
		$timezone['7'] = 'Asia/Bangkok';
		$timezone['8'] = 'Asia/Hong_Kong';
		$timezone['8.75'] = 'Australia/Eucla';
		$timezone['9'] = 'Asia/Tokyo';
		$timezone['9.5'] = 'Australia/Adelaide';
		$timezone['10'] = 'Australia/Brisbane';
		$timezone['10.5'] = '"Australia/Lord_Howe';
		$timezone['11'] = 'Asia/Magadan';
		$timezone['11.5'] = 'Pacific/Norfolk';
		$timezone['12'] = 'Pacific/Fiji';
		$timezone['12.75'] = 'Pacific/Chatham';
		$timezone['13'] = 'Pacific/Tongatapu';
		$timezone['14'] = 'Pacific/Kiritimati';
		
		if (isset($timezone[$offset])) {
			$timezone = $timezone[$offset];
		} else {
			$timezone = 'Europe/London';
		}
		return $timezone;
	}
	
	function getValue($user,$parameter) {
		$plugin = JFusionConnectFactory::getPlugin();
		$parameter = strtolower($parameter);
		
		return $plugin->getValue($user,$parameter);

		return null;
	}

	function addReplyInfo($request,$user)
	{
		if ($request->identity) {
			$response =& $request->answer(true, JFusionConnect::getServerURL(), JFusionConnect::userToURL($user));
			if (!Auth_OpenID_isError($response)) {
		        // Answer with some sample Simple Registration data.
				$params = &JComponentHelper::getParams('com_jfusionconnect');
				$plugin = JFusionConnectFactory::getPlugin();
				if ($params->get('sreg_enabled',1)) {
					JFusionConnect::requireOnce('Auth/OpenID/SReg.php');
					$required = $optional = array();
					if ($request->message->hasKey(Auth_OpenID_SREG_NS_URI_1_1,'required')) {
						$required = explode (',' , $request->message->getArg(Auth_OpenID_SREG_NS_URI_1_1,'required'));
					}
					if ($request->message->hasKey(Auth_OpenID_SREG_NS_URI_1_1,'optional')) {
						$optional = explode (',' , $request->message->getArg(Auth_OpenID_SREG_NS_URI_1_1,'optional'));
					}
					$sreg = array_merge($required,$optional);
					
					if ($sreg) {
						foreach ($sreg as $key => $value) {
							$sregToAx = strtolower(JFusionConnect::sregToAx($value));
							if ($plugin->isEnabled($sregToAx)) {
								$sreg_data[$value] = JFusionConnect::getValue($user,$sregToAx);
							}
						}
				        $sreg_request = Auth_OpenID_SRegRequest::fromOpenIDRequest($request);
				        $sreg_response = Auth_OpenID_SRegResponse::extractResponse($sreg_request, $sreg_data);
	
						$sreg_response->toMessage($response->fields);
					}
				}
				if ($params->get('ax_enabled',1)) {
					JFusionConnect::requireOnce('Auth/OpenID/AX.php');			
					$required = $if_available = array();
					if ($request->message->hasKey(Auth_OpenID_AX_NS_URI,'required')) {
						$required = explode (',' , $request->message->getArg(Auth_OpenID_AX_NS_URI,'required'));
					}
					if ($request->message->hasKey(Auth_OpenID_AX_NS_URI,'if_available')) {
						$if_available = explode (',' , $request->message->getArg(Auth_OpenID_AX_NS_URI,'if_available'));
					}
					$ax = array_merge($required,$if_available);
					
					if ($ax) {
						$ax_request = Auth_OpenID_AX_FetchRequest::fromOpenIDRequest($request);
			
						$ax_response = new Auth_OpenID_AX_FetchResponse();				
						foreach ($ax as $k => $key) {
							$key = str_replace( '_', '/' , $key);					
							if ($plugin->isEnabled($key)) {
								$value = JFusionConnect::getValue($user,$key);
								if ($value) {
									$ax_response->addValue('http://axschema.org/'.$key,$value);
								}
							}
						}
						
						$ax_response->toMessage($response->fields);
					}
		/*
		// name stuff
		$ax_response->addValue('http://axschema.org/namePerson/prefix', ...);
		$ax_response->addValue('http://axschema.org/namePerson/suffix', ...);
		
		// Work stuff
		$ax_response->addValue('http://axschema.org/company/name', ...);
		$ax_response->addValue('http://axschema.org/company/title', ...);
		$ax_response->addValue('http://axschema.org/namePerson/friendly', ...);
		
		// Date of Birth
		$ax_response->addValue('http://axschema.org/birthDate/birthYear', ...);
		$ax_response->addValue('http://axschema.org/birthDate/birthMonth', ...);
		$ax_response->addValue('http://axschema.org/birthDate/birthday', ...);
		
		//Telephone
		$ax_response->addValue('http://axschema.org/contact/phone/default', ...);
		$ax_response->addValue('http://axschema.org/contact/phone/home', ...);
		$ax_response->addValue('http://axschema.org/contact/phone/business', ...);
		$ax_response->addValue('http://axschema.org/contact/phone/cell', ...);
		$ax_response->addValue('http://axschema.org/contact/phone/fax', ...);
		
		//Address
		$ax_response->addValue('http://axschema.org/contact/postalAddress/home', ...);
		$ax_response->addValue('http://axschema.org/contact/postalAddressAdditional/home', ...);
		$ax_response->addValue('http://axschema.org/contact/city/home', ...);
		$ax_response->addValue('http://axschema.org/contact/state/home', ...);
		$ax_response->addValue('http://axschema.org/contact/country/home', ...);
		$ax_response->addValue('http://axschema.org/contact/postalCode/home', ...);
		$ax_response->addValue('http://axschema.org/contact/postalAddress/business', ...);
		$ax_response->addValue('http://axschema.org/contact/postalAddressAdditional/business', ...);
		$ax_response->addValue('http://axschema.org/contact/city/business', ...);
		$ax_response->addValue('http://axschema.org/contact/state/business', ...);
		$ax_response->addValue('http://axschema.org/contact/country/business', ...);
		$ax_response->addValue('http://axschema.org/contact/postalCode/business', ...);
		
		//Instant Messaging
		$ax_response->addValue('http://axschema.org/contact/IM/AIM', ...);
		$ax_response->addValue('http://axschema.org/contact/IM/ICQ', ...);
		$ax_response->addValue('http://axschema.org/contact/IM/MSN', ...);
		$ax_response->addValue('http://axschema.org/contact/IM/Yahoo', ...);
		$ax_response->addValue('http://axschema.org/contact/IM/Jabber', ...);
		$ax_response->addValue('http://axschema.org/contact/IM/Skype', ...);
		
		//Web Sites
		$ax_response->addValue('http://axschema.org/contact/web/default', ...);
		$ax_response->addValue('http://axschema.org/contact/web/blog', ...);
		$ax_response->addValue('http://axschema.org/contact/web/Linkedin', ...);
		$ax_response->addValue('http://axschema.org/contact/web/Amazon', ...);
		$ax_response->addValue('http://axschema.org/contact/web/Flickr', ...);
		$ax_response->addValue('http://axschema.org/contact/web/Delicious', ...);
		
		//Audio/Video Greetings
		$ax_response->addValue('http://axschema.org/media/spokenname', ...);
		$ax_response->addValue('http://axschema.org/media/greeting/audio', ...);
		$ax_response->addValue('http://axschema.org/media/greeting/video', ...);
		
		//Images
		$ax_response->addValue('http://axschema.org/media/image/default', ...);
		$ax_response->addValue('http://axschema.org/media/image/aspect11', ...);
		$ax_response->addValue('http://axschema.org/media/image/aspect43', ...);
		$ax_response->addValue('http://axschema.org/media/image/aspect34', ...);
		$ax_response->addValue('http://axschema.org/media/image/favicon', ...);
		
		//Other Personal Details/Preferences
		$ax_response->addValue('http://axschema.org/media/biography', ...);
		*/
				}
				if ($params->get('pape_enabled',0)) {
					JFusionConnect::requireOnce('Auth/OpenID/PAPE.php');
					$URI = $request->message->namespaces->getNamespaceURI('pape');
					$required = $if_available = array();
					$pape = null;
					if ($request->message->hasKey($URI,'preferred_auth_policies')) {
						$pape = explode (' ' , $request->message->getArg($URI,'preferred_auth_policies'));
					}
					if ($pape) {
						$pape_request = Auth_OpenID_PAPE_Request::fromOpenIDRequest($request);

						$pape_response = new Auth_OpenID_PAPE_Response();
						foreach ($pape as $value) {
							$key = explode('/', $value);
							if (count($key) > 1) {
								$key = $key[count($key)-1];
							} else {
								$key = null;
							}
							if ($params->get('pape_'.$key,0)) {
								$pape_response->addPolicyURI($value);
							}
						}
						$pape_response->toMessage($response->fields);
					}
				}
			}
		} else {
			$response =& $request->answer(true, JFusionConnect::getServerURL());			
		}
		return $response;
	}
	
	/**
	 * Instantiate a new OpenID server object
	 */
	function getServer()
	{
	    static $server = null;
	    if (!isset($server)) {
	    	JFusionConnect::requireOnce('Auth/OpenID/Server.php');
	        $server = new Auth_OpenID_Server(JFusionConnect::getOpenIDStore(), JFusionConnect::getServerURL());
	    }
	    return $server;
	}
	
	
	function redirect($message)
	{  
		$mainframe = & JFactory::getApplication();
		$requestid = JFusionConnect::getRequestID();
		$url = JRoute::_('index.php?option=com_jfusionconnect&view=login&requestid='.$requestid, false);
		$mainframe->redirect($url,$message);
	}
	
	function getRequestID($request=null)
	{
		$requestid = JRequest::getVar('requestid');
		if ($requestid) {
			return $requestid;
		} else if ($request !== null && !Auth_OpenID_isError($request)) {
  			if (in_array($request->mode, array('checkid_immediate', 'checkid_setup'))) {
  				$id = md5($request->return_to.uniqid());
  				JRequest::setVar('requestid',$id);
  				return $id;
  			}
	    }  			
  		return null;
	}
	
	function getRequest($redirect=true)
	{
		static $request;
		$server =& JFusionConnect::getServer();		
		if (isset($request)) {
			return $request;
		}
		
	    $request = $server->decodeRequest();
  		$session =& JFactory::getSession();
  		
		$requestid = JFusionConnect::getRequestID();
		$db = & JFactory::getDBO();
		if ($requestid) {

        	$req =& JFusionConnectRequest::getInstance($requestid);
        	$req->clean();
        	$result = $req->getUnused();
        	if ($result) {
	        	$request = $result->request;
        	}
  		} else if (!Auth_OpenID_isError($request) && $request) {
  			if (in_array($request->mode, array('checkid_immediate', 'checkid_setup'))) {
				$requestid = JFusionConnect::getRequestID($request);

  				if (!$request->idSelect()) {
					$user = JFusionConnect::userFromRequest($request);
					if ($user->guest) {
						$sucess = JFusionConnect::changeUser($request);
						if (!$sucess) {
							JFusionConnect::cancelRequest(JFusionConnectLog::STATUS_INVALID_USER);
						}
					}
				}
				
				$req =& JFusionConnectRequest::getInstance($requestid);
				$req->add($request);

				$openiduser = JFusionConnect::userFromRequest($request);
				$logInstance =& JFusionConnectLog::getInstance(null,$openiduser);
				$logInstance->add(JFusionConnectLog::STATUS_LOGIN_INPROGRESS,$request);
				
				if ($redirect) {
					$mainframe = & JFactory::getApplication();
					$url = JRoute::_('index.php?option=com_jfusionconnect&view=login&requestid='.$requestid, false);
					$mainframe->redirect($url);
				}
  			}
  		} else {
  			$request = new Auth_OpenID_ServerError();
  		}
  		return $request;
	}
	
	function changeUser(&$request)
	{
		if (!Auth_OpenID_isError($request)) {
			$params = &JComponentHelper::getParams('com_jfusionconnect');
			if ($params->get('allowopenidselect',true)) {
		    	$request->identity = $request->claimed_id = Auth_OpenID_IDENTIFIER_SELECT;
				return true;
			}
		}
		return false;
	}	
	
	function saveRequest($request)
	{
		$server =& JFusionConnect::getServer();
		$requestid = JFusionConnect::getRequestID($request);
		if ($requestid) {
			$req =& JFusionConnectRequest::getInstance($requestid);
			$req->add($request,null);
		}
	}

	function cancelRequest($level=null)
	{
		if (!isset($level)) {
			$level = JFusionConnectLog::STATUS_LOGIN_CANCEL;
		}
		$request = JFusionConnect::getRequest();
		if (!Auth_OpenID_isError($request)) {
			$url = $request->getCancelURL();

			$openiduser = JFusionConnect::userFromRequest($request);
			$logInstance =& JFusionConnectLog::getInstance(null,$openiduser);
			$logInstance->add($level,$request);

			$mainframe = & JFactory::getApplication();
			$mainframe->redirect($url);
			die();
		}
	}
	
	function login($request,$user,$output=false)
	{
		$response = new Auth_OpenID_ServerError(null,JText::_('EMPTY_OPENID_RESPONCE'));
		if (!Auth_OpenID_isError($request)) {
			$server =& JFusionConnect::getServer();
			
    		if (in_array($request->mode, array('checkid_immediate', 'checkid_setup'))) {
				$response =& JFusionConnect::addReplyInfo($request,$user);
				$logInstance =& JFusionConnectLog::getInstance(null,$user);				
				if (!Auth_OpenID_isError($response)) {
					$logInstance->add(JFusionConnectLog::STATUS_LOGIN_SUCCESS,$request,$response);
					if ($output) {
						JFusionConnect::response($server,$response);
					}
				} else {
					$logInstance->add(JFusionConnectLog::STATUS_FATAL_ERROR,$request,$response);					
				}
    		}
		}
		return $response;
	}
	
	function autoLogin($request,$user,$openiduser)
	{
		if (!$user->block && !$user->get('guest')) {
			if ($user->id && $user->id === $openiduser->id ) {
		   		$db = & JFactory::getDBO();
		    	$query = 'SELECT remember FROM #__jfusionconnect WHERE userid='.$db->Quote($user->id).' AND realm='.$db->Quote($request->trust_root).' LIMIT 1';
		        $db->setQuery($query);
		        $result = $db->loadResult();
		        if ($result) {
		        	JFusionConnect::login($request,$user,true);
		        }
			}
		}
	}
	
	function response($server,$response)
	{
		if (!Auth_OpenID_isError($response)) {
		    $webresponse =& $server->encodeResponse($response);

		    if ($webresponse->code != AUTH_OPENID_HTTP_OK) {
		        header(sprintf("HTTP/1.1 %d ", $webresponse->code), true, $webresponse->code);
		    }
		    foreach ($webresponse->headers as $k => $v) {
		        header("$k: $v");
		    }
		    header('Connection: close');
		    if (Auth_OpenID_ENCODE_HTML_FORM == $response->whichEncoding()) {
				echo Auth_OpenID::autoSubmitHTML($webresponse->body);
		    } else {
		    	echo $webresponse->body;
		    }
		    exit(0);
		}
	}
	
	function xrdsUrl($mode)
	{
		$serverurl = JFusionConnect::getServerURL();
		return JFusionConnect::buildURL($serverurl,'?task=xrds&getxrads='.$mode,false);
	}
	
	function xrds($type)
	{
	    JFusionConnect::requireOnce('Auth/OpenID/SReg.php');
	    JFusionConnect::requireOnce('Auth/OpenID/AX.php');
	    JFusionConnect::requireOnce('Auth/OpenID/PAPE.php');
		JFusionConnect::requireOnce('Auth/OpenID/Discover.php');
		$params = &JComponentHelper::getParams('com_jfusionconnect');
	    switch ($type) {
		    case 'signon':
		    	$type = Auth_OpenID_TYPE_2_0;
				break;
		    case 'server':
		    default:
		    	$type = Auth_OpenID_TYPE_2_0_IDP;
				break;
		}		
		
		if (!$params->get('enabled',false)) {
			JError::raiseError(404, JText::_('SERVER_OFF'));
			return false;
		}
		$openidmode = $params->get('openidmode',1);
		
		header('Content-type: application/xrds+xml');
	    header('Connection: close');
		
	    echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
		echo '<xrds:XRDS xmlns:xrds="xri://$xrds" xmlns="xri://$xrd*($v*2.0)">'."\n";
    	echo '<XRD>'."\n";
    	echo '<Service priority="0">'."\n";
    	echo '<MediaType>application/xrds+xml</MediaType>'."\n";
	    echo '<Type>'.$type.'</Type>'."\n";
	    if ($params->get('sreg_enabled',1)) {
	    	echo '<Type>'.Auth_OpenID_SREG_NS_URI_1_1.'</Type>'."\n";
	    }
	    if ($params->get('ax_enabled',1)) {
	    	echo '<Type>'.Auth_OpenID_AX_NS_URI.'</Type>'."\n";
	    }
	    if ($params->get('pape_enabled',0)) {
			echo '<Type>'.Auth_OpenID_PAPE_NS_URI.'</Type>'."\n";
	    }
		echo '<URI>'.JFusionConnect::getServerURL().'</URI>'."\n";
		echo '</Service>'."\n";
    	echo '</XRD>'."\n";
		echo '</xrds:XRDS>';
		die();
	}

	function isAllowingAutologin($request) {
		$params = &JComponentHelper::getParams('com_jfusionconnect');

		$user_recaptchaenabled = $params->get('user_recaptchaenabled',0);
		$user_recaptchaalways = $params->get('user_recaptchaalways',0);
		$user_recaptchaprivatekey = $params->get('user_recaptchaprivatekey','');
		$user_recaptchapublickey = $params->get('user_recaptchapublickey','');
		if ($user_recaptchaenabled && $user_recaptchaprivatekey && $user_recaptchapublickey) {
			if ($user_recaptchaalways) {
				return false;
			}
		}
		$request_pape = JFusionConnect::requestedPAPE($request);
		if($params->get('pape_multi-factor',0) && isset($request_pape['multi-factor'])) {
			return false;
		}
		if ($params->get('user_allowautoconfirm',1)) {
			return true;
		}
		return false;
	}	
	
	function useRecaptcha($request) {
		$params = &JComponentHelper::getParams('com_jfusionconnect');

		$user_recaptchaenabled = $params->get('user_recaptchaenabled',0);
		$user_recaptchaalways = $params->get('user_recaptchaalways',0);
		$user_recaptchaprivatekey = $params->get('user_recaptchaprivatekey','');
		$user_recaptchapublickey = $params->get('user_recaptchapublickey','');
		if ($user_recaptchaenabled && $user_recaptchaprivatekey && $user_recaptchapublickey) {
			if ($user_recaptchaalways) {
				return true;
			}
			$request_pape = JFusionConnect::requestedPAPE($request);
			if($params->get('pape_multi-factor',0) && isset($request_pape['multi-factor'])) {
				return true;
			}
		}
		return false;		
	}

	function getRecaptcha($request=null) {
		if (JFusionConnect::useRecaptcha($request) || $request===null) {
			if (!function_exists('recaptcha_get_html')) {
				require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusionconnect' . DS . 'models' . DS . 'recaptchalib.php';
			}
			$params = &JComponentHelper::getParams('com_jfusionconnect');
			$publickey = $params->get('user_recaptchapublickey','');
			
			$user_recaptchatheme = $params->get('user_recaptchatheme','red');
			if ($user_recaptchatheme) {
				$document =& JFactory::getDocument();
	
				$js = 'var RecaptchaOptions = {
							theme : \''.$user_recaptchatheme.'\'
						};';
	            $document->addScriptDeclaration($js);			
			}
			return recaptcha_get_html($publickey);
		} else {
			return '';
		}
	}
	
	function checkRecaptcha($request=null) {
		if (JFusionConnect::useRecaptcha($request) || $request===null) {
			if (!function_exists('recaptcha_check_answer')) {
				require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusionconnect' . DS . 'models' . DS . 'recaptchalib.php';
			}		
			$params = &JComponentHelper::getParams('com_jfusionconnect');
			$privatekey = $params->get('user_recaptchaprivatekey','');
			$resp = recaptcha_check_answer($privatekey,
			                                $_SERVER["REMOTE_ADDR"],
			                                $_POST["recaptcha_challenge_field"],
			                                $_POST["recaptcha_response_field"]);
			if (!$resp->is_valid) {
				return false;
			}
		}
		return true;
	}
	
    /**
     * Returns value from version_compare what version joomla is
     *
     * @param string $v version to check
     *
     * @return true/false
     */
    function isJoomlaVersion($v='1.6') {
        static $versions;
        if (!isset($versions[$v])) {
	    	$version = new JVersion;
	    	if (version_compare($version->getShortVersion(), $v) >= 0) {
	        	$versions[$v] = true;
	    	} else {
	        	$versions[$v] = false;
	    	}
        }
        return $versions[$v];
    }	
}
