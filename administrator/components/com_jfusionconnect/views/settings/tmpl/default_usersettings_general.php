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
	<legend><?php echo JText::_( 'GENERAL' ); ?></legend>
	<table class="admintable" style="border-spacing:1px;">
		<tbody>
			<tr>
				<td width="185" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ALLOWAUTOCONFIGM' ); ?>::<?php echo JText::_( 'TIPALLOWAUTOCONFIGM' ); ?>">
						<?php echo JText::_( 'ALLOWAUTOCONFIGM' ); ?>
					</span>
				</td>
				<td>
					<?php echo $this->lists['user_allowautoconfirm']; ?>
				</td>
			</tr>
			<tr>
				<td width="185" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'REDIRECINVALIDREQUEST' ); ?>::<?php echo JText::_( 'TIPREDIRECINVALIDREQUEST' ); ?>">
						<?php echo JText::_( 'REDIRECINVALIDREQUEST' ); ?>
					</span>
				</td>
				<td>
					<?php echo $this->lists['user_redirectinvalidrequest']; ?>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>