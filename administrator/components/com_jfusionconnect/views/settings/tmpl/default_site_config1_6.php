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
	<ul class="adminformlist">
		<li>
			<label title="<?php echo JText::_( 'OPENIDLISTTYPE' ).'::'.JText::_( 'TIPOPENIDLISTTYPE' ); ?>" class="hasTip"><?php echo JText::_( 'OPENIDLISTTYPE' ); ?>
			</label>
			<fieldset class="radio">
				<?php echo $this->lists['openid_listtype']; ?>
			</fieldset>
		</li>
		<li>
			<label title="<?php echo JText::_( 'OPENIDLIST' ).'::'.JText::_( 'TIPOPENIDLIST' ); ?>" class="hasTip"><?php echo JText::_( 'OPENIDLIST' ); ?>
			</label>
			<textarea name="settings[openid_list]" rows="5" cols="75"><?php echo $this->params->get('openid_list',''); ?></textarea>			
		</li>
	</ul>
</fieldset>