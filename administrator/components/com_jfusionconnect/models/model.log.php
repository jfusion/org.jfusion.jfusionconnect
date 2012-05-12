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

class JFusionConnectLog
{
	const STATUS_LOGIN_INPROGRESS = 1;
	const STATUS_LOGIN_SUCCESS = 2;
	const STATUS_LOGIN_CANCEL = 3;
	const STATUS_LOGIN_FAILED = 4;
	const STATUS_SERVERLOG = 5;
	const STATUS_AUTH_ERROR = 6;
	const STATUS_FATAL_ERROR = 7;
	const STATUS_INVALID_USER = 8;
	const STATUS_NOT_TRUSTED = 9;

	var $realm = null;
	var $userid = null;
	
    function __construct($realm,$userid)
    {
		$server =& JFusionConnect::getServer();
		$this->realm = $realm;
		$this->userid = $userid;
    }
    
    function getInstance($realm=null,$user=null) {
        static $instances = array();
        
        if ($user) {
        	$userid = $user->id;
        } else {
        	$userid = 0;
        }
        if (!isset($instances[$realm][$userid])) {
			return $instances[$realm][$userid] = new JFusionConnectLog($realm,$userid);
        } else {
            return $instances[$realm][$userid];
        }
    }
	
	function _where($w=null,$request=true)
	{
		if ($w) {
			if ($request) {
				return 'WHERE ( '.$w.' ) AND log.request_id=request.id';
			} else {
				return 'WHERE '.$w;
			}
		}
		
		$db = & JFactory::getDBO();
		$where = array();
		if ($this->userid) {
			$where[] = 'userid = '. $db->Quote($this->userid);
		}

		if ($this->realm) {
			$where[] = 'realm = '. $db->Quote($this->realm);
		}

		if (count($where)) {
			if ($request) {
				return 'WHERE '.implode(' AND ', $where). ' AND log.request_id=request.id';
			} else {
				return 'WHERE '.implode(' AND ', $where);
			}
		}
		if ($request) {
			return 'WHERE log.request_id=request.id';
		} else {
			return '';
		}
	}
	
	function _getfields() {
		$fields = 'log.id as id, log.userid as userid, log.ipadress as ipadress, log.date as date, log.status as status, log.request_id as request_id';
		$fields .= ', request.hash as hash, request.realm as realm, request.request as request, request.response as response';
		return $fields;
	}	

	function add($status,$request,$response=null)
	{
		if ($status) {
			$db =& JFactory::getDBO();

		 	if ($request->mode == 'check_authentication') {
				if ($request->message->hasKey(Auth_OpenID_OPENID2_NS,'response_nonce')) {
					$id = md5($request->message->getArg(Auth_OpenID_OPENID2_NS,'response_nonce'));
				}
				if ($id) {
					if ($status == JFusionConnectLog::STATUS_SERVERLOG) {
						$req =& JFusionConnectRequest::getInstance($id);
						if ($req->get()) {
							$status = JFusionConnectLog::STATUS_AUTH_ERROR;
						}
					}
				}
  			} else {
				$id = JFusionConnect::getRequestID($request);
  			}
			
			$req =& JFusionConnectRequest::getInstance($id);
			
			$log = new stdClass;
			$log->id = null;
			$log->userid = $this->userid;
			$log->status = $status;
			$log->request_id = $req->add($request,$response);
			$log->ipadress = $_SERVER['REMOTE_ADDR'];
			$db->insertObject('#__jfusionconnect_log', $log, 'id');
		}
	}
	
	function count($w)
	{
		$db = & JFactory::getDBO();
		$query = 'SELECT count(log.id) FROM #__jfusionconnect_log as log INNER JOIN #__jfusionconnect_request as request '.$this->_where($w);
        $db->setQuery($query);
        $count = $db->loadResult();
		return $count;
	}
	
	function countFailedLogins($requestid)
	{
		$count=0;
		$logs = $this->getByRequestID($requestid);
       	foreach ($logs as $key => $log) {
       		if ($log->status == JFusionConnectLog::STATUS_LOGIN_FAILED) {
				$count++;
       		} else {
       			return null;
       		}
       	}
		return $count;
	}	
	
	function get($start=null,$limmit=null,$w=null,$o=array())
	{
		$db = & JFactory::getDBO();
		$query = 'SELECT '.$this->_getfields().' FROM #__jfusionconnect_log as log INNER JOIN #__jfusionconnect_request as request '.$this->_where($w);
		if (isset($o['by']) && isset($o['dir']) ) {
			$query .= ' ORDER BY '.$o['by'].' '.$o['dir'];
		} else {
			$query .= ' ORDER BY log DESC';
		}

		if ($start||$limmit) {
			$query .= ' LIMIT '.$start.' , '.$limmit;
		}
        $db->setQuery($query);
        $result = $db->loadObjectList();
        if ($result) {
        	return $result;
        }
		return array();
	}
	
	function getByRequestID($id)
	{
		$db = & JFactory::getDBO();
   		$query = 'SELECT * FROM #__jfusionconnect_log as log '.$this->_where('request_id='.$db->Quote($id),false);
        $db->setQuery($query);
        $result = $db->loadObjectList();
        if ($result) {
        	return $result;
        }
		return array();
	}
	
	function getByID($id)
	{
		$db = & JFactory::getDBO();
   		$query = 'SELECT '.$this->_getfields().' FROM #__jfusionconnect_log as log INNER JOIN #__jfusionconnect_request as request '.$this->_where('log.id='.$db->Quote($id));
        $db->setQuery($query);
        $result = $db->loadObject();
        if ($result) {
        	$result = JFusionConnectRequest::buildResult($result);
        	return $result;
        }
		return null;
	}	
	
	function getRealmList($w=null)
	{
		$db = & JFactory::getDBO();
   		$query = 'SELECT DISTINCT realm FROM #__jfusionconnect_log as log INNER JOIN #__jfusionconnect_request as request '.$this->_where($w);		
        $db->setQuery($query);
        $result = $db->loadObjectList();

        if ($result) {
        	return $result;
        }
		return array();
	}	
	
	function getIPList($w=null)
	{
		$db = & JFactory::getDBO();
   		$query = 'SELECT DISTINCT ipadress FROM #__jfusionconnect_log as log INNER JOIN #__jfusionconnect_request as request '.$this->_where($w);
        $db->setQuery($query);
        $result = $db->loadObjectList();
        if ($result) {
        	return $result;
        }
		return array();
	}
}
