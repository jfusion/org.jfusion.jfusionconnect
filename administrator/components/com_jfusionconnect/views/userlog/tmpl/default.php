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

?>
<form id="adminForm" name="adminForm" action="" method="post">
	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_( 'Filter' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo htmlspecialchars($this->lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_username').value='0';this.form.getElementById('filter_status').value='0';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php echo $this->lists['username'];?>
				<?php echo $this->lists['status'];?>
			</td>
		</tr>
	</table>
	<br/><br/>
	<table style="width:100%;">
	 	<tr align="center">
	 		<td>
	 			<img alt="<?php echo JText::_('SITE'); ?>" src="administrator/../../components/com_jfusionconnect/images/application.png">
				<?php echo JHTML::_('grid.sort', 'SITE', 'realm', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> 		
	 		</td>
	 		<td>
	 			<img alt="<?php echo JText::_('USERNAME'); ?>" src="administrator/../../components/com_jfusionconnect/images/status_online.png">
	 			<?php echo JText::_('USERNAME'); ?> 		
	 		</td>
	 		<td>
	 			<img alt="<?php echo JText::_('STATUS'); ?>" src="administrator/../../components/com_jfusionconnect/images/exclamation.png">
				<?php echo JHTML::_('grid.sort', 'STATUS', 'status', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> 		
	 		</td>
	 		<td>
	 			<img alt="<?php echo JText::_('INFO'); ?>" src="administrator/../../components/com_jfusionconnect/images/information.png"> <?php echo JText::_('INFO'); ?>
	 		</td>
	 		<td>
	 			<img alt="<?php echo JText::_('IPADRESS'); ?>" src="administrator/../../components/com_jfusionconnect/images/connect.png">
	 			<?php echo JHTML::_('grid.sort', 'IPADRESS', 'ipadress', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> 			
	 		</td>
	 		<td>
	 			<img alt="<?php echo JText::_('TIME'); ?>" src="administrator/../../components/com_jfusionconnect/images/date.png">
				<?php echo JHTML::_('grid.sort', 'TIME', 'log.id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> 		 			
	 		</td>
	 	</tr>
		<?php foreach ($this->log as $key => $value) : ?>
			<?php $user =& JFactory::getUser(); ?>
			<?php $date = new JDate($value->date,$user->getParam('timezone')); ?> 
			<tr align="center">
				<td>
	  				<a href="<?php echo $value->realm; ?>" target="_blank"><?php echo $value->realm; ?></a>
				</td>
				<td>
					<?php $loguser =& JFactory::getUser($value->userid); ?>
	  				<?php echo $loguser->username; ?>
				</td>
				<td>
					<?php echo JFusionConnectUi::getStatusIcon($value->status); ?>
				</td>
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_jfusionconnect&view=request&id='.$value->id, true); ?>">
						<img alt="<?php echo JText::_('VIEW_LOG'); ?>" src="administrator/../../components/com_jfusionconnect/images/information.png">
					</a>
				</td>
				<td>
					<a href="http://www.ip-adress.com/whois/<?php echo $value->ipadress; ?>">
						<?php echo $value->ipadress; ?>
					</a>
				</td>
				<td>
					<?php echo $date->toFormat('%Y-%m-%d %H:%M:%S',true); ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo $this->footer; ?>
</form>	