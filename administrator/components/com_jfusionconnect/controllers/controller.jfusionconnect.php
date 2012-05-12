<?php

 /**
 * This is the jfusion admin controller
 *
 * PHP version 5
 *
 * @category  JFusionConnect
 * @package   ControllerAdmin
 * @author    JFusion Team <webmaster@jfusion.org>
 * @copyright 2008 JFusion. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link      http://www.jfusion.org
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Load the JFusion framework
 */
jimport('joomla.application.component.controller');
jimport('joomla.application.component.view');

/**
 * JFusion Controller class
 *
 * @category  JFusionConnect
 * @package   ControllerAdmin
 * @author    JFusion Team <webmaster@jfusion.org>
 * @copyright 2008 JFusion. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link      http://www.jfusion.org
 */
class JFusionconnectController extends JController
{
	/**
     * Display the results of the wizard set-up
     *
     * @return void
     */
    function applysettings()
    {
		$this->_savesettings();
        $this->setRedirect('index.php?option=com_jfusionconnect&view=settings');
    }
    
	/**
     * Display the results of the wizard set-up
     *
     * @return void
     */
    function savesettings()
    {
		$this->_savesettings();
        $this->setRedirect('index.php?option=com_jfusionconnect&view=cpanel');
    }
    
	/**
     * Display the results of the wizard set-up
     *
     * @return void
     */
    function _savesettings()
    {
		$settings = JRequest::getVar('settings');

		jimport('joomla.registry.registry');
		$reg = new JRegistry();
		$reg->loadArray($settings);
				
		if(JFusionConnect::isJoomlaVersion('1.6')) {
			$component =& JTable::getInstance('extension');
			$componentid = $component->find(array('type' => 'component','element' => 'com_jfusionconnect'));
			$component->load($componentid);
			
			$plugin =& JTable::getInstance('extension');
			$pluginid = $plugin->find(array('type' => 'plugin','element' => 'jfusionconnect'));
			$plugin->load($pluginid);
			$key='enabled';
		} else {
			$component =& JTable::getInstance('component');
			$component->loadByOption('com_jfusionconnect');
			
			$plugin =& JTable::getInstance('plugin');
			$plugin->_tbl_key = 'element';
			$plugin->load('jfusionconnect');
			$key='published';
		}
		$component->params = $reg->toString();
		$component->store();
    	if ($settings['enabled']) {
			$plugin->$key = 1;
		} else {
			$plugin->$key = 0;
		}
		$plugin->store();
    }    
    
	/**
     * Display the results of the wizard set-up
     *
     * @return void
     */
    function cancelsettings()
    {
        $this->setRedirect('index.php?option=com_jfusionconnect&view=cpanel', $msg, $msgType);
    }
    
	/**
     * Display the results of the wizard set-up
     *
     * @return void
     */
    function editsite()
    {
    	$cid = JRequest::getVar('cid');
    	if ($cid[0]) {
        	$this->setRedirect('index.php?option=com_jfusionconnect&view=site&task=edit&id='.$cid[0], $msg, $msgType);
    	} else {
        	$this->setRedirect('index.php?option=com_jfusionconnect&view=sites'.$cid[0], $msg, $msgType);
    	}
    }    
    

    
	/**
     * Display the results of the wizard set-up
     *
     * @return void
     */
    function addsite()
    {
        $this->setRedirect('index.php?option=com_jfusionconnect&view=site&task=add', $msg, $msgType);
    }
    
	/**
     * Display the results of the wizard set-up
     *
     * @return void
     */
    function deletesite()
    {
		require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusionconnect' . DS . 'models' . DS . 'model.site.php';
    	$cid = JRequest::getVar('cid');
    	
    	foreach ($cid as $key => $value) {
    		$site = JFusionConnectSite::getInstance($value);
    		$site->delete();
    	}
        $this->setRedirect('index.php?option=com_jfusionconnect&view=sites', $msg, $msgType);
    }    
    
	/**
     * Display the results of the wizard set-up
     *
     * @return void
     */
    function savesite()
    {
		require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusionconnect' . DS . 'models' . DS . 'model.site.php';    	
    	$data = JRequest::getVar('site',null,'post','raw',4);
    	$text = JRequest::getVar('text',null,'post','raw',4);
    
    	$id = $data['id'];
    	$realm = $data['realm'];

    	if ($id) {
    		if (!$realm) {
    			$this->setRedirect('index.php?option=com_jfusionconnect&view=site&task=edit&id='.$id, $msg, $msgType);
				return;
    		}
    		$site = JFusionConnectSite::getInstance($id);
    	} else {
    		if (!$realm) {
    			$this->setRedirect('index.php?option=com_jfusionconnect&view=site&task=task=add', $msg, $msgType);
    			return;
    		}
    		$site = JFusionConnectSite::getInstance();
    	}
		unset($data['id'],$data['realm']);

    	jimport('joomla.registry.registry');
 		$reg = new JRegistry();
		$reg->loadArray($data);
		
		$data = array();
    	$data['id'] = $id;
    	$data['realm'] = $realm;
    	$data['params'] = $reg->toString();
    	
    	$site->add($data);
        $this->setRedirect('index.php?option=com_jfusionconnect&view=sites', $msg, $msgType);
    }    
}
