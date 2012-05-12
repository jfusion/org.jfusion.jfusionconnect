<?php
/**
 * Uninstaller file
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

/**
 * Get the extension id
 * Grabbed this from the JPackageMan installer class with modification
 *
 * @param string $type        type
 * @param int    $id          id
 * @param string $group       group
 * @param string $description description
 *
 * @return unknown_type
 */

require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jfusion'.DS.'models'.DS.'model.factory.php');

function _uninstallPlugin($type, $id, $group, $description)
{
    $db = & JFactory::getDBO();
    $result = $id;
    switch ($type) {
	    case 'plugin':
	        $db->setQuery('SELECT id FROM #__plugins WHERE folder = '.$db->Quote($group).' AND element = '.$db->Quote($id));
	        $result = $db->loadResult();
	        break;
	    case 'module':
	        $db->setQuery('SELECT id FROM #__modules WHERE module = '.$db->Quote($id));
	        $result = $db->loadResult();
	        break;
    }
    if ($result) {
        $tmpinstaller = new JInstaller();
        $installer_result = $tmpinstaller->uninstall($type, $result, 0);
        if (!$result) {
            ?>
            <table style="background-color:#f9ded9;width:100%;"><tr style="height:30px">
            <td><font size="2"><b><?php echo JText::_('UNINSTALL') . ' ' . $description . ' ' . JText::_('FAILED'); ?></b></font></td></tr></table>
            <?php
        } else {
            ?>
            <table style="background-color:#d9f9e2;width:100%;"><tr style="height:30px">
            <td><font size="2"><b><?php echo JText::_('UNINSTALL') . ' ' . $description . ' ' . JText::_('SUCCESS'); ?></b></font></td></tr></table>
            <?php
        }
    }
}

function com_uninstall() {
    $return = true;
    echo '<h2>JFusionConnect Uninstall</h2><br/>';
    //uninstall the JFusion Modules
    _uninstallPlugin('plugin', 'jfusionconnect', 'system', 'JFusionConnect System Plugin');

    //remove the jfusion tables.
    $db = & JFactory::getDBO();
    $query = 'DROP TABLE #__jfusionconnect';
    $db->setQuery($query);
    if (!$db->queryBatch()){
        echo $db->stderr() . '<br />';
        $return = false;
    }
    
    $query = 'DROP TABLE #__jfusionconnect_site';
    $db->setQuery($query);
    if (!$db->queryBatch()){
        echo $db->stderr() . '<br />';
        $return = false;
    }

    $query = 'DROP TABLE #__jfusionconnect_log';
    $db->setQuery($query);
    if (!$db->queryBatch()){
        echo $db->stderr() . '<br />';
        $return = false;
    }

    $query = 'DROP TABLE #__jfusionconnect_request';
    $db->setQuery($query);
    if (!$db->queryBatch()){
        echo $db->stderr() . '<br />';
        $return = false;
    }
    return $return;
}