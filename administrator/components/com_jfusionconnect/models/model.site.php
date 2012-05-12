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

class JFusionConnectSite
{
	var $_id = null;
	function __construct($id)
    {
    	$this->id = $id;
    }
    
    function getInstance($id=null) {
		static $instances = array();
        if (!isset($instances[$id])) {
			return $instances[$id] = new JFusionConnectSite($id);
        } else {
            return $instances[$id];
        }
    }    
	
	function _where($w=null,$request=true)
	{
		if ($w) {
			return 'WHERE '.$w;
		}
		$db = & JFactory::getDBO();
		$where = array();
		if ($this->id) {
			$where[] = 'id = '. $db->Quote($this->id);
		}
		if (count($where)) {
			return 'WHERE '.implode(' AND ', $where);
		}
		return '';
	}    
    
	function get($start=null,$limmit=null,$w=null,$o=array())
	{
		$db = & JFactory::getDBO();
		$query = 'SELECT * FROM #__jfusionconnect_site '.$this->_where($w);
		if (isset($o['by']) && isset($o['dir']) ) {
			$query .= ' ORDER BY '.$o['by'].' '.$o['dir'];
		} else {
			$query .= ' ORDER BY id ASC';
		}

		if ($start||$limmit) {
			$query .= ' LIMIT '.$start.' , '.$limmit;
		}
        $db->setQuery($query);
        $result = $db->loadObjectList();
        if ($result) {
        	jimport('joomla.registry.registry');
        	foreach ($result as $key => $value) {
				$reg = new JRegistry();
		        if ($value) {
					$reg->loadINI($value->params);
		        	$result[$key]->language = $reg->getValue('language',null);
		        }
        	}
        	return $result;
        }
		return array();
	}
	
	function getByID($id)
	{
		$db = & JFactory::getDBO();
   		$query = 'SELECT * FROM #__jfusionconnect_site '.$this->_where('id='.$db->Quote($id));
        $db->setQuery($query);
        $result = $db->loadObject();
        if ($result) {
        	return $result;
        }
		return null;
	}
	
	function getByRealm($realm,$language=null)
	{
		$db = & JFactory::getDBO();
   		$query = 'SELECT params FROM #__jfusionconnect_site '.$this->_where('realm='.$db->Quote($realm));
        $db->setQuery($query);
        $result = $db->loadResult();
        $result = $db->loadObjectList();

        $default = new JRegistry();;
        $return = array();
        if ($result) {  
			jimport('joomla.registry.registry');
	        foreach ($result as $key => $value) {
				$reg = new JRegistry();
		        if ($value) {
					$reg->loadINI($value->params);
					if ($language) {
       					$lang = $reg->getValue('language',null);						
						if (strtolower($language) == strtolower($lang)) {
							$default = $reg;
							break;
						} else if ($lang == null) {
							$default = $reg;
						}
					}
		        	$return[] = $reg;
		        }
	        }
        }
        if (isset($language)) {
        	return $default;
        }
        
        
		return $return;
	}
	
	function count($w)
	{
		$db = & JFactory::getDBO();
		$query = 'SELECT count(id) FROM #__jfusionconnect_site '.$this->_where($w);
        $db->setQuery($query);
        $count = $db->loadResult();
		return $count;
	}	

	function delete() {
		if ($this->id) {
			//clean our unused requests older than 6 houers.
			$db = & JFactory::getDBO();
	   		$query = 'DELETE FROM #__jfusionconnect_site '.
	   					'WHERE id='.$db->Quote($this->id);
	        $db->setQuery($query);
	        $db->query();
		}
	}	
	
	function add($site)
	{
		$db = & JFactory::getDBO();
		$result = $this->getByID($site['id']);
		if ($result) {
			$result->realm = $site['realm'];
			$result->params = $site['params'];
			
			$db->updateObject('#__jfusionconnect_site', $result, 'id');
		} else {
			$result = new stdClass;
			$result->id = null;
			$result->realm = $site['realm'];
			$result->params = $site['params'];

			$db->insertObject('#__jfusionconnect_site', $result, 'id');
		}
		return $result->id;
	}	
}
