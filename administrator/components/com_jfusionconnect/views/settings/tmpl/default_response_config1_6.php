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
	<ul class="adminformlist">
		<li>
			<label title="<?php echo JText::_( 'SREGENABLED' ).'::'.JText::_( 'TIPSREGENABLED' ); ?>" class="hasTip"><?php echo JText::_( 'SREGENABLED' ); ?>
			</label>
			<fieldset class="radio">
				<?php echo $this->lists['sreg_enabled']; ?>
			</fieldset>
		</li>
		<li>
			<label title="<?php echo JText::_( 'AXENABLED' ).'::'.JText::_( 'TIPAXENABLED' ); ?>" class="hasTip"><?php echo JText::_( 'AXENABLED' ); ?>
			</label>
			<fieldset class="radio">
				<?php echo $this->lists['ax_enabled']; ?>
			</fieldset>
		</li>
	</ul>
</fieldset>