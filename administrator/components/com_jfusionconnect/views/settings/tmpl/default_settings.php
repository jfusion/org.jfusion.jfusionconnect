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
	<legend><?php echo JText::_( 'SETTINGS' ); ?></legend>
	<table class="admintable" style="border-spacing:1px;">
		<tbody>
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ENABLED' ); ?>::<?php echo JText::_( 'TIPENABLED' ); ?>">
						<?php echo JText::_( 'ENABLED' ); ?>
					</span>
				</td>
				<td>
					<?php echo $this->lists['enabled']; ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'SERVERURL' ); ?>::<?php echo JText::_( 'TIPSERVERURL' ); ?>">
						<?php echo JText::_( 'SERVERURL' ); ?>
					</span>
				</td>
				<td>
					<input size="100" type="text" name="settings[serverurl]" value="<?php echo $this->lists['serverurl'] ?>"/>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'JOOMLAURL' ); ?>::<?php echo JText::_( 'TIPJOOMLAURL' ); ?>">
						<?php echo JText::_( 'JOOMLAURL' ); ?>
					</span>
				</td>
				<td>
					<input size="50" type="text" name="settings[joomlaurl]" value="<?php echo $this->lists['joomlaurl'] ?>"/>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'OPENIDSITE' ); ?>::<?php echo JText::_( 'TIPOPENIDSITE' ); ?>">
						<?php echo JText::_( 'OPENIDSITE' ); ?>
					</span>
				</td>
				<td>
					<input size="50" type="text" name="settings[openidsite]" value="<?php echo $this->lists['openidsite'] ?>"/>					
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'OPENIDPREFIX' ); ?>::<?php echo JText::_( 'TIPOPENIDPREFIX' ); ?>">
						<?php echo JText::_( 'OPENIDPREFIX' ); ?>
					</span>
				</td>
				<td>
					<input size="20" type="text" name="settings[openidprefix]" value="<?php echo $this->lists['openidprefix'] ?>"/>					
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'OPENID' ); ?>::<?php echo JText::_( 'TIPOPENID' ); ?>">
						<?php echo JText::_( 'OPENID' ); ?>
					</span>
				</td>
				<td>
					<?php echo $this->lists['openid']; ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ALLOWOPENIDSELECT' ); ?>::<?php echo JText::_( 'TIPALLOWOPENIDSELECT' ); ?>">
						<?php echo JText::_( 'ALLOWOPENIDSELECT' ); ?>
					</span>
				</td>
				<td>
					<?php echo $this->lists['allowopenidselect']; ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'STOREPATH' ); ?>::<?php echo JText::_( 'TIPSTOREPATH' ); ?>">
						<?php echo JText::_( 'STOREPATH' ); ?>
					</span>
				</td>
				<td>
					<input size="100" type="text" name="settings[storepath]" value="<?php echo $this->lists['storepath'] ?>"/>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'LOGINITEMID' ); ?>::<?php echo JText::_( 'TIPLOGINITEMID' ); ?>">
						<?php echo JText::_( 'LOGINITEMID' ); ?>
					</span>
				</td>
				<td>
					<input size="5" type="text" name="settings[loginitemid]" value="<?php echo $this->lists['loginitemid'] ?>"/>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'APPLYFAVICON' ); ?>::<?php echo JText::_( 'TIPAPPLYFAVICON' ); ?>">
						<?php echo JText::_( 'APPLYFAVICON' ); ?>
					</span>
				</td>
				<td>
					<input size="100" type="text" name="settings[favicon]" value="<?php echo $this->lists['favicon'] ?>"/>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>