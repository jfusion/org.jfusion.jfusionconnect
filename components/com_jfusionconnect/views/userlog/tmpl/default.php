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
<div id="jfusionconnect">
	<?php JFusionConnectUi::renderMenuBar(); ?>
	
	<form id="adminForm" name="adminForm" action="<?php echo JRoute::_('index.php?option=com_jfusionconnect&view=userlog', true); ?>" method="post">
		<table>
			<tr>
				<td width="100%">
				</td>
				<td nowrap="nowrap">
					<?php echo $this->lists['realm'];?>
					<?php echo $this->lists['ipadress'];?>
					<?php echo $this->lists['status'];?>
				</td>
			</tr>
		</table>
		<table style="width:100%;">
		 	<tr align="center">
		 		<td>
		 			<img alt="<?php echo JText::_('SITE'); ?>" src="<?php echo JURI::root(true); ?>/components/com_jfusionconnect/images/application.png">
					<?php echo JHTML::_('grid.sort', 'SITE', 'realm', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> 		
		 		</td>
		 		<td>
		 			<img alt="<?php echo JText::_('STATUS'); ?>" src="<?php echo JURI::root(true); ?>/components/com_jfusionconnect/images/exclamation.png">
					<?php echo JHTML::_('grid.sort', 'STATUS', 'status', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> 		
		 		</td>
		 		<td>
		 			<img alt="<?php echo JText::_('INFO'); ?>" src="<?php echo JURI::root(true); ?>/components/com_jfusionconnect/images/information.png"> <?php echo JText::_('INFO'); ?>
		 		</td>		
		 		<td>
		 			<img alt="<?php echo JText::_('IPADRESS'); ?>" src="<?php echo JURI::root(true); ?>/components/com_jfusionconnect/images/connect.png">
		 			<?php echo JHTML::_('grid.sort', 'IPADRESS', 'ipadress', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> 			
		 		</td>
		 		<td>
		 			<img alt="<?php echo JText::_('TIME'); ?>" src="<?php echo JURI::root(true); ?>/components/com_jfusionconnect/images/date.png">
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
						<?php echo JFusionConnectUi::getStatusIcon($value->status); ?>
					</td>
					<td>
						<a href="<?php echo JRoute::_('index.php?option=com_jfusionconnect&view=request&id='.$value->id, true); ?>">
							<img alt="<?php echo JText::_('VIEW_LOG'); ?>" src="<?php echo JURI::root(true); ?>/components/com_jfusionconnect/images/information.png">
						</a>
					</td>
					<td>
						<a href="http://www.ip-adress.com/whois/<?php echo $value->ipadress; ?>" target="_blank">
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
</div>