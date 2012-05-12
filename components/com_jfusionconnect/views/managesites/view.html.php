<?php

/**
 * This is view file for cpanel
 *
 * PHP version 5
 *
 * @category   JFusionConnect
 * @package    ViewsFront
 * @subpackage Frameless
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * load the JFusion framework
 */
jimport('joomla.application.component.view');
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusionconnect' . DS . 'models' . DS . 'model.ui.php';

/**
 * Class that handles the framelesss integration
 * 
 * @category   JFusionConnect
 * @package    ViewsFront
 * @subpackage Frameless
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */
class jfusionconnectViewmanagesites extends JView
{
     /**
     * displays the view
     *
     * @param string $tpl template name
     * 
     * @return string html output of view
     */       
    function display($tpl = null)
    {
		$user	=& JFactory::getUser();
		if ( $user->get('guest') ) {
			JError::raiseError( 403, JText::_('Access Forbidden') );
			return;
		}
		
   		$db = & JFactory::getDBO();
    	$query = 'SELECT * FROM #__jfusionconnect WHERE userid='. $db->Quote($user->id).' ORDER BY realm DESC';
        $db->setQuery($query);
        $result = $db->loadObjectList();
		$params = &JComponentHelper::getParams('com_jfusionconnect');			
		$user_allowautoconfirm = $params->get('user_allowautoconfirm',1);

		foreach ($result as $key => $value) {
			if ($value->remember) {
				$value->remember = 'checked';
			} else {
				$value->remember = '';
			}
			if ($user_allowautoconfirm) {
				$value->disabled = '';
			} else {
				$value->disabled = 'disabled';
			}
		}
		$this->assignRef('sites', $result);
        parent::display($tpl);
    }
}
