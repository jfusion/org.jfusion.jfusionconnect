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
	<legend><?php echo JText::_( 'SITE_CONFIG' ); ?></legend>
	<table class="admintable" style="border-spacing:1px;">
		<tbody>
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'OPENIDLISTTYPE' ); ?>::<?php echo JText::_( 'TIPOPENIDLISTTYPE' ); ?>">
						<?php echo JText::_( 'OPENIDLISTTYPE' ); ?>
					</span>
				</td>
				<td>
					<?php echo $this->lists['openid_listtype']; ?>						
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'OPENIDLIST' ); ?>::<?php echo JText::_( 'TIPOPENIDLIST' ); ?>">
						<?php echo JText::_( 'OPENIDLIST' ); ?>
					</span>
				</td>
				<td>
					<textarea name="settings[openid_list]" rows="5" cols="75"><?php echo $this->params->get('openid_list',''); ?></textarea>						
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>