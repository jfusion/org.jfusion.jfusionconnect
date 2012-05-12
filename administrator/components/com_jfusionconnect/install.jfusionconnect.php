<?php

/**
 * Installer file
 *
 * PHP version 5
 *
 * @category  JFusionConnect
 * @package   Install
 * @author    JFusion Team <webmaster@jfusion.org>
 * @copyright 2008 JFusion. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link      http://www.jfusion.org
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


function com_install() {
    $return = true;

    //find out where we are
    $basedir = dirname(__FILE__);

    //load language file
    $lang = & JFactory::getLanguage();
    $lang->load('com_jfusionconnect', JPATH_BASE);

    //see if we need to create SQL tables
    $db = & JFactory::getDBO();
    $table_list = $db->getTableList();
    $table_prefix = $db->getPrefix();
    
    //NOTE moved these before the jfusion table as some tables did not exist in old verions of JFusion thus leading to errors during the upgrade process
    //create the jfusion_users table if it does not exist already
	$query = 'CREATE TABLE IF NOT EXISTS `#__jfusionconnect` (
				`id` int(11) NOT NULL auto_increment,
				`userid` int(11) NOT NULL,
				`realm` text NOT NULL,
				`remember` tinyint(1) NOT NULL,
				PRIMARY KEY  (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8;';
	$db->setQuery($query);
	if (!$db->query()) {
		echo $db->stderr() . '<br />';
		$return = false;
		return $return;
	}

	$query = 'CREATE TABLE IF NOT EXISTS `jos_jfusionconnect_site` (
  				`id` int(11) NOT NULL auto_increment,
  				`realm` text NOT NULL,
  				`params` text NOT NULL,
  				PRIMARY KEY  (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8;';
	$db->setQuery($query);
	if (!$db->query()) {
		echo $db->stderr() . '<br />';
		$return = false;
		return $return;
	}
	
	$query = 'CREATE TABLE IF NOT EXISTS `jos_jfusionconnect_log` (
  				`id` int(11) NOT NULL auto_increment,
  				`userid` int(11) NOT NULL,
  				`request_id` int(11) NOT NULL,
  				`status` smallint(2) NOT NULL,
  				`ipadress` text NOT NULL,
  				`date` timestamp NULL default CURRENT_TIMESTAMP,
  				PRIMARY KEY  (`id`),
  				KEY `request_id` (`request_id`),
  				KEY `userid` (`userid`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8;';
	$db->setQuery($query);
	if (!$db->query()) {
		echo $db->stderr() . '<br />';
		$return = false;
		return $return;
	}
	
	$query = 'CREATE TABLE IF NOT EXISTS `#__jfusionconnect_request` (
				`id` int(11) NOT NULL auto_increment,
				`hash` varchar(32) NOT NULL,
				`realm` text NOT NULL,
				`request` text,
				`response` text,
				`time` int(11) NOT NULL,
				PRIMARY KEY  (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8;	';
	$db->setQuery($query);
	if (!$db->query()) {
		echo $db->stderr() . '<br />';
		$return = false;
		return $return;
	}
    //output some info to the user
    ?>
    <table><tr><td width="100px">
    <img src="components/com_jfusionconnect/images/jfusion_large.png" height="75px" width="75px">
    </td><td width="100px">
    <img src="components/com_jfusionconnect/images/manager.png" height="75px" width="75px">
    <td><h2><?php echo JText::_('JFUSIONCONNECT') . ' 1.0.0 ' . JText::_('INSTALLATION'); ?></h2></td></tr></table>
    <h3><?php echo JText::_('STARTING') . ' ' . JText::_('INSTALLATION') . ' ...' ?></h3>

    <table style="background-color:#d9f9e2;width:100%;"><tr><td width="50px">
    <img src="components/com_jfusionconnect/images/check_good.png" height="20px" width="20px"></td>
    <td><font size="2"><b><?php echo JText::_('INSTALLED') . ' ' . JText::_('JFUSIONCONNECT') . ' ' . JText::_('COMPONENT'); ?> </b></font></td></tr></table>

    <?php
    //install the JFusion packages
    jimport('joomla.installer.helper');
    $packages['System Plugin'] = $basedir . DS . 'packages' . DS . 'jfusionconnect_plugin_system.zip';

	$version = new JVersion;
	if (version_compare($version->getShortVersion(), '1.6') >= 0) {
    	$packages['OpenID Libeary'] = $basedir . DS . 'packages' . DS . 'lib_openid.zip';
	} else {
		jimport( 'joomla.filesystem.archive' );
		if (JArchive::extract( $basedir . DS . 'packages' . DS . 'lib_openid.zip' , JPATH_LIBRARIES.DS.'openid')) {?>
            <table style="background-color:#d9f9e2;width:100%;"><tr style="height:30px"><td width="50px">
            <img src="components/com_jfusionconnect/images/check_good.png" height="20px" width="20px"></td>
            <td><font size="2"><b><?php echo JText::_('INSTALLED') . ' ' . JText::_('OPENIDAPI'); ?></b></font></td></tr></table>
		<?php } else { ?>
            <table style="background-color:#f9ded9;width:100%;"><tr style="height:30px"><td width="50px">
            <img src="components/com_jfusionconnect/images/check_bad.png" height="20px" width="20px"></td>
            <td><font size="2"><b><?php echo JText::_('ERROR') . ' ' . JText::_('INSTALLING') . ' ' . JText::_('OPENIDAPI'). ' ' .JText::_('CHECKCHMOD'); ?></b></font></td></tr></table>
		 <?php }
	}
    foreach ($packages as $name => $filename) {
        $package = JInstallerHelper::unpack($filename);
        $tmpInstaller = new JInstaller();
        if ($tmpInstaller->install($package['dir'])) { ?>
            <table style="background-color:#d9f9e2;width:100%;"><tr style="height:30px"><td width="50px">
            <img src="components/com_jfusionconnect/images/check_good.png" height="20px" width="20px"></td>
            <td><font size="2"><b><?php echo JText::_('INSTALLED') . ' ' . JText::_('JFUSIONCONNECT') . ' ' . $name; ?></b></font></td></tr></table>
      <?php
        } else { ?>
            <table style="background-color:#f9ded9;width:100%;"><tr style="height:30px"><td width="50px">
            <img src="components/com_jfusionconnect/images/check_bad.png" height="20px" width="20px"></td>
            <td><font size="2"><b><?php echo JText::_('ERROR') . ' ' . JText::_('INSTALLING') . ' ' . JText::_('JFUSIONCONNECT') . ' ' . $name; ?></b></font></td></tr></table>
      <?php
        }
        unset($package, $tmpInstaller);
    }
    
    echo '<br/>' . JText::_('INSTALLATION_INSTRUCTIONS') . '<br/><br/>';
    //cleanup the packages directory
    $package_dir = $basedir . DS . 'packages';
//  JFolder::delete($package_dir);
    return $return;
}
