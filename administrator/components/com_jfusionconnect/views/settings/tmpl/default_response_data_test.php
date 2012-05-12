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
<?php foreach($this->lists['returndatatest'] as $key=>$plugin) : ?>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'RETURNDATA' ). ' - '.$key; ?>
			<span class="error hasTip" title="<?php echo JText::_( 'Warning' );?>::<?php echo JText::_( 'WARNPATHCHANGES' ); ?>">
				<?php //echo ConfigApplicationView::WarningIcon(); ?>
			</span>
		</legend>
		<table class="admintable" style="border-spacing:1px;">
			<tbody>
				<?php foreach($plugin as $key=>$value) : ?>
					<tr>
						<td width="185" class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_($key).'::'.JText::_( 'TIP'.$key ); ?>">
								<?php echo JText::_($key); ?>
							</span>
						</td>
						<td>
							<?php echo $value; ?>
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</fieldset>
<?php endforeach ?>