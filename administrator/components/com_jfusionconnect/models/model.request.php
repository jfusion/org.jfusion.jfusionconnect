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

class JFusionConnectRequest
{
	var $_id = null;
	function __construct($id)
    {
    	$this->_id = $id;
		$server =& JFusionConnect::getServer();
    }
    
    function getInstance($id) {
		static $instances = array();
        if (!isset($instances[$id])) {
			return $instances[$id] = new JFusionConnectRequest($id);
        } else {
            return $instances[$id];
        }
    }    

	function buildResult($result) {
		if (isset($result->request) && $result->request) {
			$result->request = unserialize(base64_decode($result->request));
		}
		if (isset($result->response) && $result->response) {
			$response = unserialize(base64_decode($result->response));
			if (is_object($response)) {
				$result->response = $response;
			} else {
				$result->response = null;
			}
		}
		return $result;
	}

	function get() {
		if ($this->_id) {
			$db = & JFactory::getDBO();
	   		$query = 'SELECT * FROM #__jfusionconnect_request '.
	   					'WHERE hash='.$db->Quote($this->_id);
	        $db->setQuery($query);
	        $result = $db->loadObject();
	        if ($result) {
				$result = $this->buildResult($result);	        	
	        	return $result;
	        }
		}
		return null;
	}
	
	function getUnused() {
		if ($this->_id) {
			$this->clean();
			$request = $this->get();			
			if ($request) {
				// Must be newer than 6 hours
				if ($request->time > time()-(60*60*6)) {
					$log =& JFusionConnectLog::getInstance();
					$logresult = $log->getByRequestID($request->id);
		        	$failedcount=0;
		        	foreach ($logresult as $key => $value) {
		        		if ($value->status == JFusionConnectLog::STATUS_LOGIN_FAILED) {
							$failedcount++;
		        		} else if ( $value->status != JFusionConnectLog::STATUS_LOGIN_INPROGRESS) {
		        			return null;
		        		}
		        	}
		        	if ($failedcount>=3) {
		        		return null;
		        	}
		        	return $request;
				}
			}
		}
		return null;		
	}
	
	function clean() {
		//TODO: NEED TO BE CHANEGD
		return;
		//clean our unused requests older than 6 hours.
		$db = & JFactory::getDBO();
   		$query = 'DELETE request FROM #__jfusionconnect_request as request LEFT JOIN  #__jfusionconnect_log as log ON log.request_id = request.id '.
   					'WHERE log.request_id IS NULL AND request.time <='.(time()-(60*60*6));
        $db->setQuery($query);
        $db->query();
	}	
	
	function add($request,$response=null)
	{
		$db = & JFactory::getDBO();
		if ($request->mode == 'check_authentication') {
	    	if ($request->message->hasKey(Auth_OpenID_OPENID2_NS,'claimed_id')) {
				$request->claimed_id = $request->message->getArg(Auth_OpenID_OPENID2_NS,'claimed_id');
			} else if ($request->message->hasKey(Auth_OpenID_OPENID2_NS,'identity')) {
				$request->identity = $request->message->getArg(Auth_OpenID_OPENID2_NS,'identity');
			}
			if ($request->message->hasKey(Auth_OpenID_OPENID2_NS,'return_to')) {
				$return_to = $request->message->getArg(Auth_OpenID_OPENID2_NS,'return_to');
				$u =& JFactory::getURI($return_to);
				$u->setQuery(null);
				$u->setFragment(null);
				$request->trust_root = $u->toString();
			}			
			if ($request->message->hasKey(Auth_OpenID_OPENID2_NS,'response_nonce')) {
				$this->_id = md5($request->message->getArg(Auth_OpenID_OPENID2_NS,'response_nonce'));
			}
	    }

		$result = $this->get();
		if ($result) {
			$result->request = base64_encode(serialize($request));
			if ($response) {
				$result->response = base64_encode(serialize($response));
			} else {
				$result->response = null;
			}
			$result->time = time();
			$db->updateObject('#__jfusionconnect_request', $result, 'id');
		} else {
			$result = new stdClass;
			$result->id = null;
			$result->hash = $this->_id;
			
			$u =& JURI::getInstance($request->trust_root);
			$result->realm = $u->getHost();
			
			$result->request = base64_encode(serialize($request));
			if ($response) {
				$result->response = base64_encode(serialize($response));
			} else {
				$result->response = null;
			}
			$result->time = time();
			$db->insertObject('#__jfusionconnect_request', $result, 'id');		
		}
		return $result->id;
	}	
}
