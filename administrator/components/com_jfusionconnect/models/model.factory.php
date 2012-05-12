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
// no direct access
defined('_JEXEC') or die('Restricted access');

class JFusionConnectFactory
{
    function &getPlugin()
    {
		static $instance;
        //only create a new plugin instance if it has not been created before
        if (!isset($instance)) {
            //load the Abstract Public Class
            include_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusionconnect' . DS . 'models' . DS . 'model.plugin.php';
            $instance = new JFusionConnectPlugin();
		}
		return $instance;
    }
}
