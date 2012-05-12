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
	<legend><?php echo JText::_( 'RECAPTCHA' ); ?></legend>
	<table class="admintable" style="border-spacing:1px;">
		<tbody>
			<tr>
				<td width="185" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'RECAPTCHAENABLED' ); ?>::<?php echo JText::_( 'TIPRECAPTCHAENABLED' ); ?>">
						<?php echo JText::_( 'RECAPTCHAENABLED' ); ?>
					</span>
				</td>
				<td>
					<?php echo $this->lists['user_recaptchaenabled']; ?>
				</td>
			</tr>
			<tr>
				<td width="185" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'RECAPTCHAALWAYS' ); ?>::<?php echo JText::_( 'TIPRECAPTCHAALWAYS' ); ?>">
						<?php echo JText::_( 'RECAPTCHAALWAYS' ); ?>
					</span>
				</td>
				<td>
					<?php echo $this->lists['user_recaptchaalways']; ?>
				</td>
			</tr>
			<tr>
				<td width="185" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'RECAPTCHAPUBLICKEY' ); ?>::<?php echo JText::_( 'TIPRECAPTCHAPUBLICKEY' ); ?>">
						<?php echo JText::_( 'RECAPTCHAPUBLICKEY' ); ?>
					</span>
				</td>
				<td>
 					<input size="60" type="text" name="settings[user_recaptchapublickey]" value="<?php echo $this->lists['user_recaptchapublickey'] ?>"/>
				</td>
			</tr>
			<tr>
				<td width="185" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'RECAPTCHAPRIVATEKEY' ); ?>::<?php echo JText::_( 'TIPRECAPTCHAPRIVATEKEY' ); ?>">
						<?php echo JText::_( 'RECAPTCHAPRIVATEKEY' ); ?>
					</span>
				</td>
				<td>
 					<input size="60" type="text" name="settings[user_recaptchaprivatekey]" value="<?php echo $this->lists['user_recaptchaprivatekey'] ?>"/>
				</td>
			</tr>
			<tr>
				<td width="185" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'RECAPTCHATHEME' ); ?>::<?php echo JText::_( 'TIPRECAPTCHATHEME' ); ?>">
						<?php echo JText::_( 'RECAPTCHATHEME' ); ?>
					</span>
				</td>
				<td>
 					<?php echo $this->lists['user_recaptchatheme']; ?>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>