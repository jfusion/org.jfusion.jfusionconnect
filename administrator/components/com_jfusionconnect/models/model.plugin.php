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

class JFusionConnectPlugin
{
	var $plugins = array();
    function JFusionConnectPlugin()
    {
		$this->loadplugins();
    }
    
    function loadplugins()
    {
    	$plugins = JFolder::folders(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusionconnect' . DS. 'plugins');
		include_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusionconnect' . DS . 'models' . DS . 'model.abstractplugin.php';
    	foreach ($plugins as $plugin) {
			$file = JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusionconnect' . DS . 'plugins' . DS . $plugin . DS . $plugin .'.php';
			if (file_exists($file)) {
				include_once $file;
				$class = 'JFusionConnectPlugin_'.$plugin;				
				if (class_exists($class)) {
					$instance = new $class;
					if ($instance->name == $plugin) {
						$this->plugins[$plugin] = new $class;
					}
				}
			}
    	}
    }
    
    function getValue($user,$function)
    {
		foreach ($this->plugins as $plugin) {
			$value = $plugin->get($user,$function);
			if ($value!==null) {
				return $value;
			}
		}
    	return null;
    }
    
    function getConfig()
    {
    	$config = array();
		foreach ($this->plugins as $key => $plugin) {
			$config[$key] = $plugin->getConfig();
		}
    	return $config;
    }
    
    function isEnabled($field)
    {
    	$config = array();
		foreach ($this->plugins as $key => $plugin) {
            $field = $this->cleanKey($field);
			$value = $plugin->isEnabled($field);
			if ($value) {
				return true;
			}
		}
    	return false;
    }
    
    function cleanKey($key)
    {
        $key = str_ireplace('http://axschema.org/', '' , $key );
        $key = strtolower(str_replace( '_', '/' , $key));
        return $key;
    }    
}