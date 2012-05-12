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
	<ul class="adminformlist">
		<li>
			<label title="<?php echo JText::_( 'ALLOWAUTOCONFIGM' ).'::'.JText::_( 'TIPALLOWAUTOCONFIGM' ); ?>" class="hasTip"><?php echo JText::_( 'ALLOWAUTOCONFIGM' ); ?>
			</label>
			<fieldset class="radio">
				<?php echo $this->lists['user_allowautoconfirm']; ?>
			</fieldset>
		</li>
		<li>
			<label title="<?php echo JText::_( 'REDIRECINVALIDREQUEST' ).'::'.JText::_( 'TIPREDIRECINVALIDREQUEST' ); ?>" class="hasTip"><?php echo JText::_( 'REDIRECINVALIDREQUEST' ); ?>
			</label>
			<fieldset class="radio">
				<?php echo $this->lists['user_redirectinvalidrequest']; ?>
			</fieldset>
		</li>
	</ul>
</fieldset>