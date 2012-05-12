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
	<form action="" method="post" name="edit" id="form-manage">
		<table style="width:100%;">
		 	<tr align="center">
		 		<td>
		 			<img alt="<?php echo JText::_('SITE'); ?>" src="<?php echo JURI::root(true); ?>/components/com_jfusionconnect/images/application.png"> <?php echo JText::_('SITE'); ?>
		 		</td>
		 		<td>
		 			<img alt="<?php echo JText::_('LOG'); ?>" src="<?php echo JURI::root(true); ?>/components/com_jfusionconnect/images/book.png"> <?php echo JText::_('LOG'); ?>
		 		</td>
		 		<td>
		 			<img alt="<?php echo JText::_('SITE'); ?>" src="<?php echo JURI::root(true); ?>/components/com_jfusionconnect/images/lock_open.png"> <?php echo JText::_('AUTO_CONDIRM'); ?>
		 		</td>
		 	</tr>
		 	<?php if (count($this->sites)) : ?>
				<?php foreach ($this->sites as $key => $value) : ?>
					<tr>
						<td>
							<a href="<?php echo $value->realm; ?>" target="_blank"><?php echo $value->realm; ?></a>    
						</td>
						<td align="center">
							<a href="<?php echo JRoute::_('index.php?option=com_jfusionconnect&view=userlog&filter_realm='.base64_encode($value->realm), true); ?>">
								<img alt="<?php echo JText::_('VIEW_LOG'); ?>" src="<?php echo JURI::root(true); ?>/components/com_jfusionconnect/images/book.png">
							</a>
						</td>
						<td align="center">
							<input type="checkbox" <?php echo $value->remember; ?> <?php echo $value->disabled; ?> name="site[remember][<?php echo $value->id; ?>]">
						</td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<td colspan="3" align="right">
						<button type="submit" name="task" value="manage_update">
							<div class="update">
								<?php echo JText::_('UPDATE'); ?>
							</div>
						</button>
					</td>
				</tr>
			<?php endif; ?>
		</table>
	</form>
</div>