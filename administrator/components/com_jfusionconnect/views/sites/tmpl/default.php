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
				<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
		</tr>
	</table>
	<br/><br/>
	<table>
	 	<tr>
	 		<td align="center">

				<?php echo JHTML::_('grid.sort', '#', 'id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>	 			
	 		</td>
	 		<td align="center">
	 			<img alt="<?php echo JText::_('SITE'); ?>" src="administrator/../../components/com_jfusionconnect/images/application.png">
				<?php echo JHTML::_('grid.sort', 'SITE', 'realm', @$this->lists['order_Dir'], @$this->lists['order'] ); ?> 		
	 		</td>
	 		<td align="center">
	 			<img alt="<?php echo JText::_('LANGUAGE'); ?>" src="administrator/../../components/com_jfusionconnect/images/application.png">
	 			<?php echo JText::_('LANGUAGE'); ?> 		
	 		</td>
	 	</tr>
		<?php foreach ($this->sites as $key => $value) : ?>
			<tr>
				<td align="center">
					<?php echo JHTML::_('grid.id', $value->id, $value->id ); ?>
				</td>
				<td>
	  				<?php echo $value->realm; ?>
				</td>
				<td align="center">
					<?php if ($value->language) : ?>
						<?php echo $value->language; ?>
					<?php else : ?>
						<?php  echo JText::_( 'SYSTEM_LANGUAGE' ); ?>
					<?php endif; ?>	
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<input type="hidden" name="task" value="" />
	<input type="hidden" value="0" name="boxchecked">
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo $this->footer; ?>
</form>	