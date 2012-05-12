<?php

/**
 * This is view file for cpanel
 *
 * PHP version 5
 *
 * @category   JFusionConnect
 * @package    ViewsAdmin
 * @subpackage Cpanel
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
//display the paypal donation button
?>
<fieldset class="adminform">
	<legend><?php echo JText::_( 'RESPONCECONFIG' ); ?></legend>
	<table class="admintable" style="border-spacing:1px;">
		<tbody>
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'SREGENABLED' ); ?>::<?php echo JText::_( 'TIPSREGENABLED' ); ?>">
						<?php echo JText::_( 'SREGENABLED' ); ?>
					</span>
				</td>
				<td>
					<?php echo $this->lists['sreg_enabled']; ?>
				</td>
			</tr>								
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'AXENABLED' ); ?>::<?php echo JText::_( 'TIPAXENABLED' ); ?>">
						<?php echo JText::_( 'AXENABLED' ); ?>
					</span>
				</td>
				<td>
					<?php echo $this->lists['ax_enabled']; ?>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>