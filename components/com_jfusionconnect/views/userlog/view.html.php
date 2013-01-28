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
jimport( 'joomla.utilities.date' );
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
class jfusionconnectViewuserlog extends JView
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
    	global $option;
		$mainframe = & JFactory::getApplication();
    	$user	=& JFactory::getUser();
		if ( $user->get('guest') ) {
			// Redirect to login
			$uri		= JFactory::getURI();
			$return		= $uri->toString();

			$url  = 'index.php?option=com_user&view=login';
			$url .= '&return='.base64_encode($return);

			$mainframe->redirect($url, JText::_('YOU MUST LOGIN FIRST'));
		} else {
			$db	=& JFactory::getDBO();    	
    	
			$filter_order = $mainframe->getUserStateFromRequest( "global.filter_order", 'filter_order', 'log.id', 'cmd' );
			//ensure filter_order has a valid value.
			if (!in_array($filter_order, array('realm', 'status', 'ipadress', 'log.id'))) {
				$filter_order = 'log.id';
			}
			$filter_order_Dir = $mainframe->getUserStateFromRequest( "global.filter_order_Dir", 'filter_order_Dir', 'desc', 'word' );
	    	if ($filter_order_Dir !='desc' && $filter_order_Dir !='asc') {
				$filter_order_Dir = 'desc';
			}

			$orderby['by'] = $filter_order;
			$orderby['dir'] = $filter_order_Dir;
			$filter_status = $mainframe->getUserStateFromRequest( "global.filter_status", 'filter_status', 0, 'int' );
						
			$filter_ipadress = $mainframe->getUserStateFromRequest( "global.filter_ipadress", 'filter_ipadress', 0, 'string' );
			$filter_realm = $mainframe->getUserStateFromRequest( "global.filter_realm", 'filter_realm', 0, 'string' );
			$realm = new JURI(base64_decode($filter_realm));
			$filter_realm = $realm->getHost();

			$logInstance =& JFusionConnectLog::getInstance();
			$where = 'userid = '.$user->id;

			$realmlist = $logInstance->getRealmList($where);
			$ipadresslist = $logInstance->getIPList($where);

			if ($filter_status) {
				$where .= ' AND status = '.$db->Quote($filter_status);
			}

			$listrealm[] = JHTML::_('select.option',  0, '- '. JText::_( 'SELECT_SITE' ) .' -');
			foreach ($realmlist as $value) {
				$value->realm = strpos($value->realm ,'?') ? substr ( $value->realm , 0 ,strpos($value->realm ,'?') ) : $value->realm;
				$listrealm[] = JHTML::_('select.option', base64_encode($value->realm), $value->realm);
			}
			$lists['realm'] = JHTML::_('select.genericlist', $listrealm, 'filter_realm', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', base64_encode($filter_realm) );
			if ($filter_realm) {
				$where .= ' AND realm = '.$db->Quote($filter_realm);
			}
			if ($filter_ipadress) {
   				$where .= ' AND ipadress = '.$db->Quote($filter_ipadress);
			}

			jimport('joomla.html.pagination');
			$limmit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', 10, 'int'); //I guess getUserStateFromRequest is for session or different reasons
			$limitstart = JRequest::getVar('limitstart', 0, '', 'int');

			$pageNav = new JPagination($logInstance->count($where), $limitstart, $limmit);
			$footer = $pageNav->getListFooter(); //Displays a nice footer

			$log = $logInstance->get($limitstart,$limmit,$where,$orderby);

			$listip[] = JHTML::_('select.option',  0, '- '. JText::_( 'SELECT_IPADRESS' ) .' -');
			foreach ($ipadresslist as $value) {
				$listip[] = JHTML::_('select.option', $value->ipadress, $value->ipadress);
			}
			$lists['ipadress'] = JHTML::_('select.genericlist', $listip, 'filter_ipadress', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', $filter_ipadress );			

			$lists['order_Dir']	= $filter_order_Dir;
			$lists['order'] = $filter_order;
			
			// get list of Log Status for dropdown filter
			$logged[] = JHTML::_('select.option',  0, '- '. JText::_( 'SELECT_STATUS' ) .' -');
			$logged[] = JHTML::_('select.option',  JFusionConnectLog::STATUS_LOGIN_INPROGRESS, JText::_( 'STATUS_LOGIN_INPROGRESS' ));
			$logged[] = JHTML::_('select.option',  JFusionConnectLog::STATUS_LOGIN_SUCCESS, JText::_( 'STATUS_LOGIN_SUCCESS' ));
			$logged[] = JHTML::_('select.option',  JFusionConnectLog::STATUS_LOGIN_CANCEL, JText::_( 'STATUS_LOGIN_CANCEL' ) );
			$logged[] = JHTML::_('select.option',  JFusionConnectLog::STATUS_LOGIN_FAILED, JText::_( 'STATUS_LOGIN_FAILED' ));
			$logged[] = JHTML::_('select.option',  JFusionConnectLog::STATUS_SERVERLOG, JText::_( 'STATUS_SERVERLOG' ) );			
			$lists['status'] = JHTML::_('select.genericlist', $logged, 'filter_status', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', $filter_status );
			
			$this->assignRef('log', $log);
			$this->assignRef('footer', $footer);
			$this->assignRef('lists', $lists);
		}
        parent::display($tpl);
    }
}
