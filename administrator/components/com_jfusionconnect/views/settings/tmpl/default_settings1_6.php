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
	<ul class="adminformlist">
		<li>
			<label title="<?php echo JText::_( 'ENABLED' ).'::'.JText::_( 'TIPENABLED' ); ?>" class="hasTip"><?php echo JText::_( 'ENABLED' ); ?>
			</label>
			<fieldset class="radio">
				<?php echo $this->lists['enabled']; ?>
			</fieldset>
		</li>
		<li>
			<label title="<?php echo JText::_( 'SERVERURL' ).'::'.JText::_( 'TIPSERVERURL' ); ?>" class="hasTip"><?php echo JText::_( 'SERVERURL' ); ?>
			</label>
			<input size="100" type="text" name="settings[serverurl]" value="<?php echo $this->lists['serverurl'] ?>"/>
		</li>
		<li>
			<label title="<?php echo JText::_( 'JOOMLAURL' ).'::'.JText::_( 'TIPJOOMLAURL' ); ?>" class="hasTip"><?php echo JText::_( 'JOOMLAURL' ); ?>
			</label>
			<input size="100" type="text" name="settings[joomlaurl]" value="<?php echo $this->lists['joomlaurl'] ?>"/>
		</li>
		<li>
			<label title="<?php echo JText::_( 'OPENIDSITE' ).'::'.JText::_( 'TIPOPENIDSITE' ); ?>" class="hasTip"><?php echo JText::_( 'OPENIDSITE' ); ?>
			</label>
			<input size="100" type="text" name="settings[openidsite]" value="<?php echo $this->lists['openidsite'] ?>"/>
		</li>
		<li>
			<label title="<?php echo JText::_( 'OPENIDPREFIX' ).'::'.JText::_( 'TIPOPENIDPREFIX' ); ?>" class="hasTip"><?php echo JText::_( 'OPENIDPREFIX' ); ?>
			</label>
			<input size="20" type="text" name="settings[openidprefix]" value="<?php echo $this->lists['openidprefix'] ?>"/>
		</li>		
		<li>
			<label title="<?php echo JText::_( 'OPENID' ).'::'.JText::_( 'TIPOPENID' ); ?>" class="hasTip"><?php echo JText::_( 'OPENID' ); ?>
			</label>
			<?php echo $this->lists['openid']; ?>
		</li>
		<li>
			<label title="<?php echo JText::_( 'ALLOWOPENIDSELECT' ).'::'.JText::_( 'TIPALLOWOPENIDSELECT' ); ?>" class="hasTip"><?php echo JText::_( 'ALLOWOPENIDSELECT' ); ?>
			</label>
			<fieldset class="radio">
				<?php echo $this->lists['allowopenidselect']; ?>
			</fieldset>
		</li>
		<li>
			<label title="<?php echo JText::_( 'STOREPATH' ).'::'.JText::_( 'TIPSTOREPATH' ); ?>" class="hasTip"><?php echo JText::_( 'STOREPATH' ); ?>
			</label>
			<input size="100" type="text" name="settings[storepath]" value="<?php echo $this->lists['storepath'] ?>"/>
		</li>
		<li>
			<label title="<?php echo JText::_( 'LOGINITEMID' ).'::'.JText::_( 'TIPLOGINITEMID' ); ?>" class="hasTip"><?php echo JText::_( 'LOGINITEMID' ); ?>
			</label>
			<input size="5" type="text" name="settings[loginitemid]" value="<?php echo $this->lists['loginitemid'] ?>"/>
		</li>
		<li>
			<label title="<?php echo JText::_( 'APPLYFAVICON' ).'::'.JText::_( 'TIPAPPLYFAVICON' ); ?>" class="hasTip"><?php echo JText::_( 'APPLYFAVICON' ); ?>
			</label>
			<input size="100" type="text" name="settings[favicon]" value="<?php echo $this->lists['favicon'] ?>"/>
		</li>
	</ul>
</fieldset>