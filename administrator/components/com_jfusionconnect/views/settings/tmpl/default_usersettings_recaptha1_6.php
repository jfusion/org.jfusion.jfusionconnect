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
	<ul class="adminformlist">
		<li>
			<label title="<?php echo JText::_( 'RECAPTCHAENABLED' ).'::'.JText::_( 'TIPRECAPTCHAENABLED' ); ?>" class="hasTip"><?php echo JText::_( 'RECAPTCHAENABLED' ); ?>
			</label>
			<fieldset class="radio">
				<?php echo $this->lists['user_recaptchaenabled']; ?>
			</fieldset>
		</li>
		<li>
			<label title="<?php echo JText::_( 'RECAPTCHAALWAYS' ).'::'.JText::_( 'TIPRECAPTCHAALWAYS' ); ?>" class="hasTip"><?php echo JText::_( 'RECAPTCHAALWAYS' ); ?>
			</label>
			<fieldset class="radio">
				<?php echo $this->lists['user_recaptchaalways']; ?>
			</fieldset>
		</li>		
		<li>
			<label title="<?php echo JText::_( 'RECAPTCHAPUBLICKEY' ).'::'.JText::_( 'TIPRECAPTCHAPUBLICKEY' ); ?>" class="hasTip"><?php echo JText::_( 'RECAPTCHAPUBLICKEY' ); ?>
			</label>
			<input size="60" type="text" name="settings[user_recaptchapublickey]" value="<?php echo $this->lists['user_recaptchapublickey'] ?>"/>		
		</li>
		<li>
			<label title="<?php echo JText::_( 'RECAPTCHAPRIVATEKEY' ).'::'.JText::_( 'TIPRECAPTCHAPRIVATEKEY' ); ?>" class="hasTip"><?php echo JText::_( 'RECAPTCHAPRIVATEKEY' ); ?>
			</label>
			<input size="60" type="text" name="settings[user_recaptchaprivatekey]" value="<?php echo $this->lists['user_recaptchaprivatekey'] ?>"/>
		</li>
		<li>
			<label title="<?php echo JText::_( 'RECAPTCHATHEME' ).'::'.JText::_( 'TIPRECAPTCHATHEME' ); ?>" class="hasTip"><?php echo JText::_( 'RECAPTCHATHEME' ); ?>
			</label>
			<?php echo $this->lists['user_recaptchatheme']; ?>
		</li>	
	</ul>
</fieldset>