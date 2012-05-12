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
	<legend><?php echo JText::_( 'PAPE' ); ?></legend>
	<table class="admintable" style="border-spacing:1px;">
		<tbody>
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'PAPEENABLED' ); ?>::<?php echo JText::_( 'TIPPAPEENABLED' ); ?>">
						<?php echo JText::_( 'PAPEENABLED' ); ?>
					</span>
				</td>				
				<td>
					<?php echo $this->lists['pape_enabled']; ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'PAPE_PHISHING_RESISTANT' ); ?>::<?php echo JText::_( 'TIPPAPE_PHISHING_RESISTANT' ); ?>">
						<?php echo JText::_( 'PAPE_PHISHING_RESISTANT' ); ?>
					</span>
				</td>
				<td>
					<?php echo $this->lists['pape_phishing-resistant']; ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'PAPE_MULTI_FACTOR' ); ?>::<?php echo JText::_( 'TIPPAPE_MULTI_FACTOR' ); ?>">
						<?php echo JText::_( 'PAPE_MULTI_FACTOR' ); ?>
					</span>
				</td>
				<td>
					<?php echo $this->lists['pape_multi-factor']; ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'PAPE_MULTI_FACTOR_PHYSICAL' ); ?>::<?php echo JText::_( 'TIPPAPE_MULTI_FACTOR_PHYSICAL' ); ?>">
						<?php echo JText::_( 'PAPE_MULTI_FACTOR_PHYSICAL' ); ?>
					</span>
				</td>
				<td>
					<?php echo $this->lists['pape_multi-factor-physical']; ?>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>