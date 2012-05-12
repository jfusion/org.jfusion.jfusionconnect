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
	<ul class="adminformlist">
		<li>
			<label title="<?php echo JText::_( 'PAPEENABLED' ).'::'.JText::_( 'TIPPAPEENABLED' ); ?>" class="hasTip"><?php echo JText::_( 'PAPEENABLED' ); ?>
			</label>
			<fieldset class="radio">
				<?php echo $this->lists['pape_enabled']; ?>
			</fieldset>
		</li>
		<li>
			<label title="<?php echo JText::_( 'PAPE_PHISHING_RESISTANT' ).'::'.JText::_( 'TIPPAPE_PHISHING_RESISTANT' ); ?>" class="hasTip"><?php echo JText::_( 'PAPE_PHISHING_RESISTANT' ); ?>
			</label>
			<fieldset class="radio">
				<?php echo $this->lists['pape_phishing-resistant']; ?>
			</fieldset>
		</li>
		<li>
			<label title="<?php echo JText::_( 'PAPE_MULTI_FACTOR' ).'::'.JText::_( 'TIPPAPE_MULTI_FACTOR' ); ?>" class="hasTip"><?php echo JText::_( 'PAPE_MULTI_FACTOR' ); ?>
			</label>
			<fieldset class="radio">
				<?php echo $this->lists['pape_multi-factor']; ?>
			</fieldset>
		</li>
		<li>
			<label title="<?php echo JText::_( 'PAPE_MULTI_FACTOR_PHYSICAL' ).'::'.JText::_( 'TIPPAPE_MULTI_FACTOR_PHYSICAL' ); ?>" class="hasTip"><?php echo JText::_( 'PAPE_MULTI_FACTOR_PHYSICAL' ); ?>
			</label>
			<fieldset class="radio">
				<?php echo $this->lists['pape_multi-factor-physical']; ?>
			</fieldset>
		</li>
	</ul>
</fieldset>