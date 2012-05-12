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
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusionconnect' . DS . 'models' . DS . 'model.site.php';

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
class jfusionconnectViewsites extends JView
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
    	
		$filter_order = $mainframe->getUserStateFromRequest( "global.filter_order", 'filter_order', 'id', 'cmd' );
		//ensure filter_order has a valid value.
    	if (!in_array($filter_order, array('realm', 'id'))) {
			$filter_order = 'id';
		}
		$filter_order_Dir = $mainframe->getUserStateFromRequest( "global.filter_order_Dir", 'filter_order_Dir', 'asc', 'word' );
    	if ($filter_order_Dir !='desc' && $filter_order_Dir !='asc') {
			$filter_order_Dir = 'asc';
		}

		$search = $mainframe->getUserStateFromRequest( "global.search", 'search', '', 'string' );
    	if (strpos($search, '"') !== false) {
			$search = str_replace(array('=', '<'), '', $search);
		}
		$search = JString::strtolower($search);

		$orderby['by'] = $filter_order;
		$orderby['dir'] = $filter_order_Dir;
		
		$site = JFusionConnectSite::getInstance();
		
    	$where = null;
    	if (isset( $search ) && $search!= '') {	
			$where[] = 'realm LIKE '.$db->Quote('%'.$db->getEscaped( $search, true ).'%');
		}
		
    	if (count($where)) {
			$where = implode(' OR ', $where);
		}
		
		jimport('joomla.html.pagination');
		$limmit   = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', 10, 'int'); //I guess getUserStateFromRequest is for session or different reasons
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		
		$pageNav = new JPagination($site->count($where), $limitstart, $limmit);
		$footer = $pageNav->getListFooter(); //Displays a nice footer

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
		$lists['search'] = $search;
		
		$sites = $site->get($limitstart,$limmit,$where,$orderby);
		
		$this->assignRef('sites', $sites);
		$this->assignRef('footer', $footer);
		$this->assignRef('lists', $lists);

		JToolBarHelper::title(JText::_('SITES'));
		JToolBarHelper::deleteList(JText::_('DELETESITES'),'deletesite');
		JToolBarHelper::editList('editsite');
		JToolBarHelper::addNew('addsite');
		JToolBarHelper::back();
		
        parent::display($tpl);
    }
}

