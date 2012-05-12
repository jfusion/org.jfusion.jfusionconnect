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
    	$mainframe = JFactory::getApplication();
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
		$filter_userid = $mainframe->getUserStateFromRequest( "global.filter_userid", 'filter_userid', 0, 'string' );
		$filter_status = $mainframe->getUserStateFromRequest( "global.filter_status", 'filter_status', 0, 'int' );
		$search = $mainframe->getUserStateFromRequest( "global.search", 'search', '', 'string' );
    	if (strpos($search, '"') !== false) {
			$search = str_replace(array('=', '<'), '', $search);
		}
		$search = JString::strtolower($search);

		$logInstance =& JFusionConnectLog::getInstance();
		
		$where = null;
    	if (isset( $search ) && $search!= '') {	
			$where[] = 'realm LIKE '.$db->Quote('%'.$db->getEscaped( $search, true ).'%');
			$where[] = 'ipadress LIKE '.$db->Quote('%'.$db->getEscaped( $search, true ).'%');
		}

		$orderby['by'] = $filter_order;
		$orderby['dir'] = $filter_order_Dir;
		
		if (count($where)) {
			$where = implode(' OR ', $where);
		}
    	if ($filter_status) {
    		if ($where) {
				$where = '('.$where.' ) AND status = '.$db->Quote($filter_status);
    		} else {
    			$where = 'status = '.$db->Quote($filter_status);
    		}
		}
		if ($filter_userid) {
			$user =& JFactory::getUser($filter_userid);
			if ($where) {
				$where = '('.$where.' ) AND userid = '.$user->id;
    		} else {
    			$where = 'userid = '.$user->id;
    		}
		}
		
		jimport('joomla.html.pagination');
		$limmit   = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', 10, 'int'); //I guess getUserStateFromRequest is for session or different reasons
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		
		$pageNav = new JPagination($logInstance->count($where), $limitstart, $limmit);
		$footer = $pageNav->getListFooter(); //Displays a nice footer

		$log = $logInstance->get($limitstart,$limmit,$where,$orderby);

//		$lists['username'] = JHtml::_('list.users','filter_username',$filter_username,1,'onchange="this.form.submit();"','name',0);
		$db =& JFactory::getDBO();
		$query = 'SELECT id AS value, username AS text FROM #__users WHERE block = 0 ORDER BY username';
		$db->setQuery( $query );
		$users[] = JHTML::_('select.option',  '0', '- '. JText::_( 'No User' ) .' -' );
		$users = array_merge( $users, $db->loadObjectList() );

		$lists['username'] = JHTML::_('select.genericlist',   $users, 'filter_userid', 'class="inputbox" size="1" onchange="this.form.submit();"', 'value', 'text', $filter_userid);		

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
		
		// get list of Log Status for dropdown filter
		$logged[] = JHTML::_('select.option',  0, '- '. JText::_( 'Select Status' ) .' -');			
		$logged[] = JHTML::_('select.option',  JFusionConnectLog::STATUS_LOGIN_INPROGRESS, JText::_( 'STATUS_LOGIN_INPROGRESS' ));
		$logged[] = JHTML::_('select.option',  JFusionConnectLog::STATUS_LOGIN_SUCCESS, JText::_( 'STATUS_LOGIN_SUCCESS' ));
		$logged[] = JHTML::_('select.option',  JFusionConnectLog::STATUS_LOGIN_CANCEL, JText::_( 'STATUS_LOGIN_CANCEL' ) );
		$logged[] = JHTML::_('select.option',  JFusionConnectLog::STATUS_LOGIN_FAILED, JText::_( 'STATUS_LOGIN_FAILED' ));
		$logged[] = JHTML::_('select.option',  JFusionConnectLog::STATUS_SERVERLOG, JText::_( 'STATUS_SERVERLOG' ) );
		$logged[] = JHTML::_('select.option',  JFusionConnectLog::STATUS_AUTH_ERROR, JText::_( 'STATUS_AUTH_ERROR' ) );
		$logged[] = JHTML::_('select.option',  JFusionConnectLog::STATUS_FATAL_ERROR, JText::_( 'STATUS_FATAL_ERROR' ) );
		$logged[] = JHTML::_('select.option',  JFusionConnectLog::STATUS_INVALID_USER, JText::_( 'STATUS_INVALID_USER' ) );		
		$logged[] = JHTML::_('select.option',  JFusionConnectLog::STATUS_NOT_TRUSTED, JText::_( 'STATUS_NOT_TRUSTED' ) );
		
		$lists['status'] = JHTML::_('select.genericlist', $logged, 'filter_status', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $filter_status );
		
		$lists['search'] = $search;

		$this->assignRef('log', $log);
		$this->assignRef('footer', $footer);
		$this->assignRef('lists', $lists);

		JToolBarHelper::title(JText::_('LOG'));
		JToolBarHelper::back();
        parent::display($tpl);
    }
}

